<?php 

use Illuminate\Support\Carbon;

function presetPrice($price){
    $realPrice = (int)$price;

    return money_format('$%i', $realPrice / 100);
}

function setActiveCategory($category)
{
    return request()->category == $category ? 'active' : '';
}
function productImage($img)
{
    return ($img != NULL) && file_exists('storage/'.$img) ? asset('storage/'.$img) : asset('img/not-found.png');

}

function getNumbers()
{
    $tax = config('cart.tax') / 100;
    $discount = session()->get('coupon')['discount'] ?? 0;
    $code = session()->get('coupon')['name'] ?? null;
    $newSubtotal = (Cart::subtotal() - $discount);
    if ($newSubtotal < 0) {
        $newSubtotal = 0;
    }
    $newTax = $newSubtotal * $tax;
    $newTotal = $newSubtotal * (1 + $tax);
    return collect([
        'tax' => $tax,
        'discount' => $discount,
        'code' => $code,
        'newSubtotal' => $newSubtotal,
        'newTax' => $newTax,
        'newTotal' => $newTotal,
    ]);
}

function getStockLevel($quantity)
{
    if( $quantity > setting('site.stock_thresshold') ){
        $stockLevel = '<span class="badge badge-success">In stock</span>';
        
    }elseif( $quantity < setting('site.stock_thresshold') && $quantity > 0 ){
        $stockLevel = '<span class="badge badge-warning">Low stock</span>';
    }
    else{
        $stockLevel = '<span class="badge badge-danger">Not available</span>';

    }

    return $stockLevel;
}

function presentDate($date)
{
    return Carbon::parse($date)->format('M d, Y');
}