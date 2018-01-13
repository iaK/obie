<?php

namespace Tests;

use App\User;
use App\BotManTester;
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

    public function signIn($args = [])
    {
        $user = create(User::class, $args);

        $this->actingAs($user);

        return $user;
    }
}
