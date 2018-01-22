<?php

namespace App;

use App\Item;
use App\Events\ListDeleted;
use Illuminate\Database\Eloquent\Model;

class ShoppingList extends Model
{
    protected $dispatchesEvents = [
        'deleted' => ListDeleted::class,
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function owner()
    {
        return $this->BelongsTo(User::class, "owner_id");
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function addItem($item)
    {
        return $this->items()->create([
            "user_id" => auth()->id(),
            "name" => trim($item),
        ]);
    }

    public function removeItem($item)
    {
        return $this->items()->whereName(trim($item))->delete();
    }

    public function clear()
    {
        $this->items()->delete();
    }


}
