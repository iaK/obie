<?php

namespace Tests\Feature;

use App\Item;
use App\User;
use Tests\TestCase;
use App\ShoppingList;
use App\Events\ListJoined;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use App\Conversations\CreateListConversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ShoppingListTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;


    /**
    * @test
    */
    public function it_instructs_the_user_when_it_has_more_than_one_list()
    {
        $user = $this->signInUserWithList();

        $this->bot
            ->receives("skapa lista")
            ->assertReply(
                sprintf(
                    CreateListConversation::$askListnameText,
                    $this->user->username
                )
            )
            ->receives("dunderlistan")
            ->assertReplies([
                sprintf(
                    CreateListConversation::$confirmListnameText,
                    "dunderlistan"
                ),
                CreateListConversation::$askPasswordText
            ])
            ->receives("lösenord")
            ->assertReplies([
                CreateListConversation::$confirmPasswordText,
                CreateListConversation::$instructionText,
                vsprintf(
                    CreateListConversation::$shareInstructionText,
                    [
                        $this->user->username,
                        "dunderlistan",
                    ]
                ),
                "Nu är du ansluten till fler listor. För att välja vilken lista som ska vara aktiv - skriv \"använd [lista]\". Du kan bara ha en aktiv lista itaget :)"
            ]);
    }

    /**
    * @test
    */
    public function it_can_delete_a_list()
    {
        $this->signInUserWithList();

        $this->bot
            ->receives("radera lista {$this->list->name}")
            ->assertReply("Listan borttagen.");

        $this->assertEmpty(ShoppingList::all());
        $this->assertNull($this->user->fresh()->activeList);
        $this->assertEmpty($this->user->shoppingLists);
    }

    /**
    * @test
    */
    public function a_user_cannot_remove_a_list_he_dosent_own()
    {
        $owner = create(User::class);
        $list = make(ShoppingList::class);
        $owner->createList($list);

        $user = $this->signIn();
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
        $owner = create(User::class);
        $list = make(ShoppingList::class);
        $owner->createList($list);

        $user = $this->signIn();
        $user->joinList($list);

        $this->bot
            ->receives("lämna lista {$list->name}")
            ->assertReply("ok!");

        $this->assertNull($user->fresh()->activeList);
        $this->assertCount(0, $user->fresh()->shoppingLists);
    }

    /**
    * @test
    */
    public function it_can_display_all_your_lists()
    {
        $this->signInUserWithList();
        $this->user->shoppingLists(make(ShoppingList::class));

        $this->bot
            ->receives("visa listor")
            ->assertReply(
                view("showLists", [
                    "lists" => $this->user->shoppingLists
                ])
            ->render());
    }
}
