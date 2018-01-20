<?php

namespace Tests\Feature;

use App\Item;
use App\User;
use Tests\TestCase;
use App\ShoppingList;
use App\Events\ListJoined;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ShoppingListTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;


    /**
    * @test
    */
    public function it_can_display_a_list()
    {
        $user = $this->signIn();
        $list = make(ShoppingList::class);
        $user->createList($list);
        $item = create(Item::class, [
            "shopping_list_id" => $list->id,
            "user_id" => $user->id,
        ]);

        $this->bot
            ->setUser([
                "id" => $user->facebook_id
            ])
            ->receives("visa lista")
            ->assertReply(view("showList", ["list" => $list->fresh()])->render());
    }


    /**
    * @test
    */
    public function it_instructs_the_user_when_it_has_more_than_one_list()
    {
        $user = $this->signIn();

        $this->bot
            ->setUser([
                "id" => $user->id
            ])
            ->receives("initialize conversation");

        $lists = create(ShoppingList::class, [], 2);

        event(new ListJoined($lists, resolve("botman")));

        $this->bot->assertReply("Nu är du ansluten till fler listor. För att välja vilken lista som ska vara aktiv - skriv \"lista [lista]\". Du kan bara ha en aktiv lista itaget :)");
    }

    /**
    * @test
    */
    public function it_can_delete_a_list()
    {
        $user = $this->signIn();
        $list = make(ShoppingList::class);
        $user->createList($list);

        $this->bot
            ->setUser([
                "id" => $user->facebook_id
            ])
            ->receives("radera lista {$list->name}")
            ->assertReply("Listan borttagen.");

        $this->assertEmpty(ShoppingList::all());
        $this->assertNull($user->fresh()->activeList);
        $this->assertEmpty($user->shoppingLists);
    }

    /**
    * @test
    */
    public function a_user_cannot_remove_a_list_he_dosent_own()
    {
        $user = $this->signIn();
        $owner = create(User::class);
        $list = make(ShoppingList::class);
        $owner->createList($list);
        $user->joinList($list);

        $this->assertEquals($user->activeList->id, $list->id);

        $this->bot
            ->setUser([
                "id" => $user->facebook_id
            ])
            ->receives("radera lista {$list->name}")
            ->assertReply("Du har ingen lista som heter {$list->name}");

        $this->assertCount(1, ShoppingList::all());
    }

    /**
    * @test
    */
    public function a_user_can_leave_a_list()
    {
        $user = $this->signIn();
        $owner = create(User::class);
        $list = make(ShoppingList::class);
        $owner->createList($list);
        $user->joinList($list);

        $this->bot
            ->setUser([
                "id" => $user->facebook_id
            ])
            ->receives("lämna lista {$list->name}")
            ->assertReply("ok!");

        $this->assertNull($user->fresh()->activeList);
        $this->assertCount(0, $user->fresh()->shoppingLists);
    }
}
