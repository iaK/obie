<?php

namespace Tests\Unit;

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
        $list = make(ShoppingList::class);

        $user->createList($list);

        $this->assertCount(1, $user->shoppingLists);
    }

    /**
    * @test
    */
    public function it_can_get_its_first_list()
    {
        $user = create(User::class);

        $list = make(ShoppingList::class);
        $user->createList($list);
        $this->assertEquals($list->fresh()->id, $user->getFirstList()->id);
    }

    /**
    * @test
    */
    public function it_can_get_its_current_list()
    {
        $list = create(ShoppingList::class);
        $user = create(User::class, ["active_shopping_list_id" => $list->id]);

        $this->assertEquals($user->activeList->id, $list->id);
    }

    /**
    * @test
    */
    public function it_can_set_its_active_list()
    {
        $list = create(ShoppingList::class);
        $user = create(User::class);

        $user->setActiveList($list);
        $this->assertEquals($user->fresh()->activeList->id, $list->id);
    }

    public function when_it_creates_a_list_it_owns_it()
    {
        $list = make(ShoppingList::class);
        $user = create(User::class);

        $user->createList($list);

        $this->assertEquals($user->id, $list->fresh()->owner->id);
    }
}
