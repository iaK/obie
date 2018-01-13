<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shoppingList()
    {
        return $this->belongsTo(ShoppingList::class);
    }

}
