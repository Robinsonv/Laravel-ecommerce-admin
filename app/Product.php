<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Product extends Model{

    use SearchableTrait;

    // protected $fillable = [
    //     'quantity', 
    // ];

    protected $guarded = [];

        /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'products.name' => 10,
            'products.details' => 5,
            'products.description' => 2,
            'products.price' => 2,
        ],
    ];


    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function presetPrice(){
        return money_format('$%i', $this->price / 100);
    }

    public function scopeMightAlsoLike($query, $num){
        return $query->inRandomOrder()->take($num);
    }
}
