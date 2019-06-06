<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\OrderProduct;
use App\Mail\OrderPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CheckoutRequest;
use Gloudemans\Shoppingcart\Facades\Cart;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Cartalyst\Stripe\Exception\CardErrorException;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Cart::instance('default')->count() == 0 ){
            return redirect()->route('shop.index');
        }

        if( auth()->user() && request()->is('guestCheckout') ){
            return redirect()->route('checkout.index');
        }
        
        return view('checkout')->with([
            'discount' => getNumbers()->get('discount'),
            'newSubtotal' => getNumbers()->get('newSubtotal'),
            'newTax' => getNumbers()->get('newTax'),
            'newTotal' => getNumbers()->get('newTotal'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckoutRequest $request)
    {

        //Verificar la catidad del producto, si es cero no procesar la compra ni hacer la resta en el stock
        if( $this->productAreNoLogerAvailable() ){
            return back()->withErrors('Sorry! One of the items in your cart is no loger avialble.');
        }

        
        $contents = Cart::content()->map(function ($item)
        {
            return $item->model->slug.', '.$item->qty;
        })->values()->toJson();

        try {

            $charge = Stripe::charges()->create([
                'amount' => getNumbers()->get('newTotal') / 100,
                'currency' => 'CAD',
                'source' => $request->stripeToken,
                'description' => 'Order',
                'receipt_email' => $request->email,
                'metadata' => [
                    'contents' => $contents,
                    'quatity' => Cart::instance('default')->count(),
                    'discount' => collect(session()->get('coupon'))->toJson(),
                ]
            ]);

            $order = $this->addToOrdersTables($request, null);
            Mail::send(new OrderPlace($order));

            //decrease the quantity de product in cart
            $this->decreaseQueantities();

            //SUCCESSFUL
            Cart::instance('default')->destroy();
            session()->forget('coupon');

            // return back()->with('success_message','Gracias! el pago ha sido aceptado');
            return redirect()->route('confirmation.index')->with('success_message','Gracias! el pago ha sido aceptado');

        } catch (CardErrorException $e) {
            $this->addToOrdersTables($request, $e->getMessage());
            return back()->withErrors('Error! ' . $e->getMessage() );
        }
    }

    protected function addToOrdersTables($request, $error)
    {
        //insert into  in orders table
        $order = Order::create([
            'user_id' => auth()->user() ? auth()->user()->id : null,
            'billing_email' => $request->email,
            'billing_name' => $request->name,
            'billing_address' => $request->address,
            'billing_city' => $request->city,
            'billing_province' => $request->province,
            'billing_postalcode' => $request->postalcode,
            'billing_phone' => $request->phone,
            'billing_name_on_card' => $request->name_on_card,
            'billing_discount' => getNumbers()->get('discount'),
            'billing_discount_code' => getNumbers()->get('code'),
            'billing_subtotal' => getNumbers()->get('newSubtotal'),
            'billing_tax' => getNumbers()->get('newTax'),
            'billing_total' => getNumbers()->get('newTotal'),
            'error' => $error,

        ]);
        //insert into  order_product table
        foreach (Cart::content() as $item) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $item->model->id,
                'quantity' => $item->qty,
            ]);
        }

        return $order;
    }

    protected function decreaseQueantities(){
        foreach (Cart::content() as $item) {
            $product = Product::find($item->model->id);

            $product->update(['quantity' => $product->quantity - $item->qty]);
        }
    }
    
    protected function productAreNoLogerAvailable()
    {
        foreach (Cart::content() as $item) {
            $product = Product::find($item->model->id);

            if( $product->quantity < $item->qty ){
                return true;
            }
        }

        return false;
    }
}
