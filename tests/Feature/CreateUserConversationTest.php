<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Conversations\CreateUserConversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CreateUserConversationTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    /**
     * @test
     */
    public function it_can_create_a_user()
    {
        $this->bot->receives("hej")
            ->assertReply(CreateUserConversation::$askUsernameText)
            ->receives("isak")
            ->assertReplies([
                sprintf(
                    CreateUserConversation::$confirmUsernameText,
                    "isak"
                ),
                CreateUserConversation::$instructionText,
            ]);

        $this->assertEquals(User::first()->username, "isak");
    }

    /**
    * @test
    */
    public function you_cant_pick_a_username_that_already_exists()
    {
        create(User::class, ["username" => "isak"]);

        $this->bot->receives("hej")
            ->assertReply(CreateUserConversation::$askUsernameText)
            ->receives("isak")
            ->assertReplies([
                sprintf(
                    CreateUserConversation::$usernameIsTakenText,
                    "isak"
                ),
                CreateUserConversation::$askUsernameText
            ]);

        $this->assertCount(1, User::all());

        $this->bot
            ->receives("olof")
            ->assertReplies([
                sprintf(
                    CreateUserConversation::$confirmUsernameText,
                    "olof"
                ),
                CreateUserConversation::$instructionText,
            ]);

        $this->assertCount(2, User::all());

    }
}
