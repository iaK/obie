<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\ShoppingList;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Conversations\JoinShoppingListConversation;

class JoinShoppingListConversationTest extends TestCase
{
    use RefreshDatabase;
    /**
    * @test
     */
    public function a_user_can_join_a_list()
    {
        $userWithList = create(User::class, ["username" => "isak"]);

        $list = make(ShoppingList::class, [
            "password" => bcrypt("secret"),
            "name" => "testlist",
        ]);

        $userWithList->createList($list);


        $joiningUser = $this->signIn();

        $this->bot
            ->setUser([
                "id" => $joiningUser->facebook_id
            ])
            ->receives("anslut till gösta lista testlist")
            ->assertReply(JoinShoppingListConversation::$usernameUnknownText)
            ->receives("anslut till isak lista unknown")
            ->assertReply(JoinShoppingListConversation::$listUnknownText)
            ->receives("anslut till isak lista testlist")
            ->assertReply(JoinShoppingListConversation::$askPasswordText)
            ->receives("worngpassword")
            ->assertReply(JoinShoppingListConversation::$wrongPasswordText)
            ->receives("secret")
            ->assertReply(JoinShoppingListConversation::$confirmListJoinedText);

        $this->assertCount(1, $joiningUser->shoppingLists);
    }
}
