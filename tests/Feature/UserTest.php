<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\ShoppingList;
use Illuminate\Foundation\Testing\WithFaker;
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

}
