<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\ShoppingList;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use App\Conversations\CreateListConversation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Conversations\ChangeListPasswordConversation;

class ChangeListPasswordConversationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function it_can_change_a_list_password()
    {
        $this->signInUserWithList();

        $this->bot
            ->receives("ändra lösenord")
            ->assertReply(ChangeListPasswordConversation::$askNewPasswordText)
            ->receives("nytt lösenord")
            ->assertReply(ChangeListPasswordConversation::$confirmPasswordText);

        $this->assertTrue(
            Hash::check("nytt lösenord", $this->list->fresh()->password)
        );
    }

    /**
    * @test
    */
    public function a_user_cant_change_password_to_a_list_he_dosent_own()
    {
        $userNotOwningList = $this->signIn();
        $list = make(ShoppingList::class);
        $userWhoOwnsList = create(User::class);
        $userWhoOwnsList->createList($list);
        $userNotOwningList->joinList($list->fresh());


        $this->bot
            ->receives("ändra lösenord")
            ->assertReply(ChangeListPasswordConversation::$notYourListText);
    }

}
