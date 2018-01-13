<?php

namespace Tests\Feature;

use App\Item;
use App\User;
use Tests\TestCase;
use App\ShoppingList;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShoppingListTest extends TestCase
{
    use RefreshDatabase;
    /**
    * @test
    */
    public function it_can_have_items()
    {
        $list = create(ShoppingList::class);

        create(Item::class, ["shopping_list_id" => $list->id], 2);

        $this->assertCount(2, $list->items);
    }

    /**
    * @test
    */
    public function it_belongs_to_a_user()
    {
        $user = create(User::class);
        $list = create(ShoppingList::class, ["user_id" => $user->id]);

        $this->assertEquals($user->id, $list->user->id);
    }

    /**
    * @test
    */
    public function items_can_be_added()
    {
        $this->signIn();

        $list = create(ShoppingList::class);

        $list->addItem("ost");

        $this->assertCount(1, Item::all());
        $this->assertEquals($list->items->first()->name, Item::first()->name);
    }

    /**
    * @test
    */
    public function items_can_be_removed()
    {
        $this->signIn();

        $list = create(ShoppingList::class);

        $list->addItem("ost");
        $list->removeItem("ost");

        $this->assertCount(0, $list->fresh()->items);
    }

    /**
    * @test
    */
    public function it_can_be_cleared()
    {
        $this->signIn();

        $list = create(ShoppingList::class);
        $items = create(Item::class, ["shopping_list_id" => $list->id], 5);

        $list->clear();

        $this->assertCount(0, $list->items);
    }
}
