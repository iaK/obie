<?php

namespace Tests\Feature;

use App\Item;
use App\User;
use Tests\TestCase;
use App\ShoppingList;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_can_have_items()
    {
        $user = create(User::class);
        create(Item::class, ["user_id" => $user->id], 2);

        $this->assertCount(2, $user->items);
    }

    /**
    * @test
    */
    public function it_can_have_lists()
    {
        $user = create(User::class);
        create(ShoppingList::class, ["user_id" => $user->id], 2);

        $this->assertCount(2, $user->shoppingLists);
    }

    /**
    * @test
    */
    public function it_can_get_its_first_list()
    {
        $user = create(User::class);

        $list = create(ShoppingList::class, ["user_id" => $user->id]);

        $this->assertEquals($list->id, $user->getFirstList()->id);
    }
}
