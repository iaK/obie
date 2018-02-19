<?php

namespace App;

use App\ShoppingList;
use App\Events\ListJoined;
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

    protected $driver;

    public function setDriver($driver)
    {
        $this->driver = strtolower($driver);

        return $this;
    }

    public static function getById($id)
    {
        if (config("botman.config.default_driver")) {
            return self::find($id);
        }

        return self::where(config("botman.config.default_driver") . "_id", $id)->first();
    }


    public function ownedShoppingLists()
    {
        return $this->hasMany(ShoppingList::class, "owner_id");
    }

    public function shoppingLists()
    {
        return $this->belongsToMany(ShoppingList::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getFirstList()
    {
        return $this->shoppingLists()->first() ?? [];
    }

    public function deleteList(ShoppingList $list)
    {
        if ($list->owner->id == $this->id) {
            $list->delete();
        }

        return $this;
    }

    public function ownsList(ShoppingList $list)
    {
        return $list->owner_id == $this->id;
    }

    public function ownsActiveList()
    {
        return $this->ownsList($this->activeList);
    }




    public function activeList()
    {
        return $this->belongsTo(ShoppingList::class, "active_shopping_list_id");
    }

    public function joinList(ShoppingList $list)
    {
        $this->shoppingLists()->save($list);
        $this->setActiveList($list);

        event(new ListJoined($this->shoppingLists, resolve("botman")));

        return $this;
    }

    public function leaveList(ShoppingList $list)
    {
        $this->shoppingLists()->detach($list->id);

        if ($this->isActiveList($list)) {
            $this->removeActiveList();
        }

        $this->save();

        return $this;
    }

    public function isActiveList(ShoppingList $list)
    {
        return $this->active_shopping_list_id == $list->id;
    }


    public function createList(ShoppingList $list)
    {
        if (! $list->id) {
            $list->owner_id = $this->id;
        }

        return $this->joinList($list);
    }

    public function setActiveList(ShoppingList $list)
    {
        $this->activeList()->associate($list);

        $this->save();

        return $this;
    }

    public function removeActiveList()
    {
        $this->activeList()->dissociate();

        $this->save();

        return $this;
    }

}
