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
        $this->signInUserWithList();

        $this->bot
            ->receives("handla ost")
            ->assertReply("ok");

        $this->bot
            ->receives("köp smör")
            ->assertReply("ok");

        $this->bot
            ->receives("lägg till bröd")
            ->assertReply("ok");

        $items = Item::all()->toArray();
        $this->assertCount(3, $items);
        $this->assertEquals("ost", $items[0]["name"]);
        $this->assertEquals("smör", $items[1]["name"]);
        $this->assertEquals("bröd", $items[2]["name"]);
    }

    /**
    * @test
    */
    public function several_items_can_be_added()
    {
        $this->signInUserWithList();

        $this->bot
            ->receives("handla ost, en liter mjölk");

        $this->assertCount(2, $this->list->items);
    }

    /**
    * @test
    */
    public function it_can_remove_an_item_from_a_list()
    {
        $this->signInUserWithList();

        create(Item::class, [
            "shopping_list_id" => $this->list->id,
            "user_id" => $this->user->id,
            "name" => "ost",
        ]);

        $this->assertCount(1, $this->list->items);

        $this->bot
            ->receives("ta bort ost")
            ->assertReply("ok");

        $this->assertCount(0, $this->list->fresh()->items);
    }

    /**
    * @test
    */
    public function several_items_can_be_removed()
    {
        $this->signInUserWithList();

        $items = create(Item::class, [
            "shopping_list_id" => $this->list->id,
            "user_id" => $this->user->id,
        ], 3);

        $this->assertCount(3, $this->list->items);

        $this->bot
            ->receives("ta bort {$items[0]->name}, {$items[1]->name}")
            ->assertReply("ok");

        $this->assertCount(1, $this->list->fresh()->items);
    }

    /**
    * @test
    */
    public function it_can_clear_a_list()
    {
        $this->signInUserWithList();

        create(Item::class, [
            "shopping_list_id" => $this->list->id,
            "user_id" => $this->user->id,
            "name" => "ost",
        ], 5);

        $this->bot
            ->receives("töm");

        $this->assertCount(0, $this->list->fresh()->items);
    }

/**
    * @test
    */
    public function it_can_display_a_list()
    {
        $this->signInUserWithList();

        create(Item::class, [
            "shopping_list_id" => $this->list->id,
            "user_id" => $this->user->id,
        ]);

        $this->bot
            ->receives("visa lista")
            ->assertReply(view("showList", ["list" => $this->list->fresh()])->render());
    }

}
