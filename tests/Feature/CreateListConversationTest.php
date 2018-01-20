<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\ShoppingList;
use Illuminate\Foundation\Testing\WithFaker;
use App\Conversations\CreateListConversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CreateListConversationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_can_create_a_list()
    {
        $user = create(User::class, [
            "facebook_id" => 999
        ]);

        $this->bot
            ->setUser([
                "id" => $user->facebook_id,
                "username" => $user->username,
            ])
            ->receives("skapa lista")
            ->assertReply(
                sprintf(
                    CreateListConversation::$askListnameText,
                    $user->username
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
                        $user->username,
                        "dunderlistan"
                    ]
                )
            ]);

        $this->assertEquals($user->username, User::first()->username);
        $this->assertEquals("dunderlistan", ShoppingList::first()->name);
        $this->assertEquals($user->id, shoppingList::first()->owner->id);
        $this->assertEquals($user->id, shoppingList::first()->users()->first()->id);
    }

    /**
    * @test
    * @expectedException Exception
    */
    public function non_users_cant_create_lists()
    {
        $this->withExceptionHandling();

        $this->bot
            ->receives("skapa lista")
            ->assertReply(
                "Du verkar inte vara registrerad hos oss. skriv \"hej\" för att registrera dig."
            );

        $this->assertEmpty(ShoppingList::all());
    }

}
