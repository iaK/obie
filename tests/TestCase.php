<?php

namespace Tests;

use App\User;
use App\BotManTester;
use App\ShoppingList;
use BotMan\BotMan\BotMan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var BotMan
     */
    protected $botman;

    /**
     * @var BotManTester
     */
    protected $bot;

    protected $user;
    protected $list;

    public function signIn($args = [])
    {
        $this->user = create(User::class, $args);

        $this->actingAs($this->user);

        return $this->user;
    }

    public function signInUserWithList()
    {
        $this->user = $this->signIn();
        $this->list = make(ShoppingList::class);
        $this->user->createList($this->list);

        return $this->user;
    }
}
