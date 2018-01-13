<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Conversations\CreateUserConversation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserConversationTest extends TestCase
{
    use RefreshDatabase;

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
}
