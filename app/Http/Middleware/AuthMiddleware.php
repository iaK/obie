<?php

namespace App\Http\Middleware;

use App\User;
use BotMan\BotMan\BotMan;
use Illuminate\Support\Facades\Auth;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Interfaces\Middleware\Captured;
use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class AuthMiddleware implements Received, Captured, Matching, Heard, Sending
{
    /**
     * Handle a captured message.
     *
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $message
     * @param BotMan $bot
     * @param $next
     *
     * @return mixed
     */
    public function captured(IncomingMessage $message, $next, BotMan $bot)
    {
        return $next($message);
    }

    /**
     * Handle an incoming message.
     *
     * @param IncomingMessage $message
     * @param BotMan $bot
     * @param $next
     *
     * @return mixed
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        $user = $bot->getUser();
        if(!$user) {
            $this->sayNotAuthenticated($bot);
        }

        $id = $user->getId();

        if(!$id) {
            $this->sayNotAuthenticated($bot);
        }

        $user = User::whereFacebookId($id)->first();

        if(!$user) {
            $this->sayNotAuthenticated($bot);
        }

        Auth::login($user);

        return $next($message);
    }


    public function sayNotAuthenticated($bot)
    {
        $bot->reply("Du verkar inte vara registrerad hos oss. skriv \"hej\" för att registrera dig.");
        throw new \Exception("Unauthorized");
    }

    /**
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $message
     * @param string $pattern
     * @param bool $regexMatched Indicator if the regular expression was matched too
     * @return bool
     */
    public function matching(IncomingMessage $message, $pattern, $regexMatched)
    {
        return true;
    }

    /**
     * Handle a message that was successfully heard, but not processed yet.
     *
     * @param \BotMan\BotMan\Messages\Incoming\IncomingMessage $message
     * @param BotMan $bot
     * @param $next
     *
     * @return mixed
     */
    public function heard(IncomingMessage $message, $next, BotMan $bot)
    {
        return $next($message);
    }

    /**
     * Handle an outgoing message payload before/after it
     * hits the message service.
     *
     * @param mixed $payload
     * @param BotMan $bot
     * @param $next
     *
     * @return mixed
     */
    public function sending($payload, $next, BotMan $bot)
    {
        return $next($payload);
    }
}