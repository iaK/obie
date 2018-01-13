<?php

namespace Tests\Unit;

use App\Item;
use App\User;
use Tests\TestCase;
use App\ShoppingList;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemsTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_can_belong_to_a_user()
    {
        $user = create(User::class);
        $item = create(Item::class, ["user_id" => $user->id]);

        $this->assertEquals($item->user->id, $user->id);
    }

    /**
    * @test
    */
    public function it_can_belong_to_a_list()
    {
        $list = create(ShoppingList::class);
        $item = create(Item::class, ["shopping_list_id" => $list->id]);

        $this->assertEquals($item->shoppingList->id, $list->id);
    }
}
