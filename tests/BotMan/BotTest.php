<?php

namespace Tests\Feature;

use App\SmsBot;
use Tests\TestCase;
use BotMan\BotMan\BotManFactory;
use BotMan\Studio\Testing\BotManTester;
use BotMan\BotMan\Drivers\Tests\FakeDriver;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BotTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_add_a_grocery_to_a_list()
    {
        //$bot = $this->createFakeBot();

        $this->bot->receives('handla vatten')
            ->assertReply("Ok, vatten!");
    }

}
