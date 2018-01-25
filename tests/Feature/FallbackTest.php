<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Conversations\CreateUserConversation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FallbackTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function if_not_authenticated_it_will_try_to_sign_you_up()
    {
        $this->bot->receives("lalala")
            ->assertReply(CreateUserConversation::$askUsernameText);
    }

    /**
    * @test
    */
    public function if_authenticated_and_no_command_is_matched_a_help_message_is_displayed()
    {
        $this->signIn();

        $this->bot->receives("lalala")
            ->assertReply("Jag känner inte igen det kommandot. skriv \"hjälp\" för att se alla mina kommandon");
    }
    /**
    * @test
    */
    public function it_displays_a_list_of_all_commands_on_demand()
    {
        $this->signIn();

        $this->bot->receives("hjälp")
            ->assertReply(view("showCommands")->render());
    }
}
