<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Jobs\UpdateCoupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    
    public function store(Request $request)
    {
        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if(! $coupon ){
            return redirect()->route('cart.index')->withErrors('Cupon invalido. Por favor intente de nuevo !');
        }

        dispatch_now(new UpdateCoupon($coupon));

        return redirect()->route('cart.index')->with('success_message','Se ha usado el cupo con éxito !');

    }

    public function destroy()
    {
        session()->forget('coupon');
        return redirect()->route('cart.index')->with('success_message','El cupo se ha removido con éxito !');

    }
}
