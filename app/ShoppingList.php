<?php

namespace App;

use App\Item;
use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }


    public function addItem($item)
    {
        return $this->items()->create([
            "user_id" => auth()->id(),
            "name" => $item,
        ]);
    }

    public function removeItem($item)
    {
        return $this->items()->where("name", $item)->delete();
    }

    public function clear()
    {
        $this->items()->delete();
    }


}
