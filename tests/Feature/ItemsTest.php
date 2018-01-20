<?php

namespace Tests\Feature;

use App\Item;
use Tests\TestCase;
use App\ShoppingList;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ItemsTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();
    }

    /**
    * @test
    */
    public function it_can_add_to_a_shopping_list()
    {
        $user = $this->signIn();
        $list = make(ShoppingList::class);
        $user->createList($list);


        $this->bot
            ->setUser([
                "id" => $user->facebook_id
            ])
            ->receives("handla ost")
            ->assertReply("ok");

        $this->assertCount(1, $list->items);
        $this->assertEquals("ost", Item::first()->name);
    }

    /**
    * @test
    */
    public function several_items_can_be_added()
    {
        $user = $this->signIn();
        $list = make(ShoppingList::class);
        $user->createList($list);

        $this->bot
            ->setUser([
                "id" => $user->facebook_id
            ])
            ->receives("handla ost, en liter mjölk");

        $this->assertCount(2, $list->items);
    }

    /**
    * @test
    */
    public function it_can_remove_an_item_from_a_list()
    {
        $user = $this->signIn();
        $list = make(ShoppingList::class);
        $user->createList($list);

        $item = create(Item::class, [
            "shopping_list_id" => $list->id,
            "user_id" => $user->id,
            "name" => "ost",
        ]);

        $this->assertCount(1, $list->items);

        $this->bot
            ->setUser([
                "id" => $user->facebook_id
            ])
            ->receives("ta bort ost")
            ->assertReply("ok");

        $this->assertCount(0, $list->fresh()->items);
    }

    /**
    * @test
    */
    public function it_can_clear_a_list()
    {
        $user = $this->signIn();
        $list = make(ShoppingList::class);
        $user->createList($list);

        $item = create(Item::class, [
            "shopping_list_id" => $list->id,
            "user_id" => $user->id,
            "name" => "ost",
        ], 5);

        $this->bot
            ->setUser([
                "id" => $user->facebook_id
            ])
            ->receives("töm");

        $this->assertCount(0, $list->fresh()->items);
    }
}
