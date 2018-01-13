<?php

namespace App;

use App\ShoppingList;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function shoppingLists()
    {
        return $this->hasMany(ShoppingList::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getFirstList()
    {
        return $this->shoppingLists()->first() ?? [];
    }



}
