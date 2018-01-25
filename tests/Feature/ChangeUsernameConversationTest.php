<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Conversations\ChangeUsernameConversation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangeUsernameConversationTest extends TestCase
{
    use RefreshDatabase;
    /**
    * @test
    */
    public function it_can_change_username()
    {
        $this->signIn();

        $this->bot
            ->receives("ändra användarnamn")
            ->assertReply(ChangeUsernameConversation::$askNewUsernameText)
            ->receives($this->user->username)
            ->assertReply(ChangeUsernameConversation::$usernameAlreadyInUse)
            ->receives("hejcon_bacon")
            ->assertReply(ChangeUsernameConversation::$confirmUsernameText);

        $this->assertEquals(
            $this->user->fresh()->username, "hejcon_bacon"
        );
    }
}
