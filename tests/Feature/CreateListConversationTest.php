<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\ShoppingList;
use Illuminate\Foundation\Testing\WithFaker;
use App\Conversations\CreateListConversation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateListConversationTest extends TestCase
{
    use RefreshDatabase;
    /**
    * @test
    */
    public function it_can_create_a_list()
    {
        $this->signIn(["username" => "isak"]);

        $this->bot->receives("skapa lista")
            ->assertReply(
                sprintf(
                    CreateListConversation::$askListnameText,
                    "isak"
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
                        "isak",
                        "dunderlistan"
                    ]
                )
            ]);

        $this->assertEquals("isak", User::first()->username);
        $this->assertEquals("dunderlistan", ShoppingList::first()->name);
        $this->assertEquals(User::first()->id, shoppingList::first()->user->id);
    }
}
