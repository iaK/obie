<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\ShoppingList;
use Illuminate\Foundation\Testing\WithFaker;
use App\Conversations\CreateUserConversation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_can_set_its_active_list()
    {
        $firstList = make(ShoppingList::class);
        $secondList = make(ShoppingList::class);
        $user = $this->signIn();
        $user->createList($firstList);
        $user->createList($secondList);

        $this->assertEquals($user->fresh()->activeList->id, $secondList->id);

        $this->bot
            ->setUser([
                "id" => $user->facebook_id
            ])
            ->receives("använd {$firstList->name}")
            ->assertReply("{$firstList->name} är nu satt som aktiv!");

        $this->assertEquals($user->fresh()->activeList->id, $firstList->id);
    }

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

}
