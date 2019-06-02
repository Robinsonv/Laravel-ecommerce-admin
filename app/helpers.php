<?php 

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