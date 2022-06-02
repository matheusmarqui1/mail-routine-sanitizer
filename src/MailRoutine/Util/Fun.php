<?php
namespace MailRoutine\Util;

use MailRoutine\App\Service\Sanitizer;
use Symfony\Component\HttpClient\HttpClient;
class Fun{
    public static function getJoke() : array {
        $client = HttpClient::create();
        return $client->request('GET', 'https://v2.jokeapi.dev/joke/Programming')->toArray();
    }

    public static function showFormattedJoke() : void {
        $joke = self::getJoke();

        if($joke['type'] == 'single') {
            $joke = $joke['joke'];
        }else{
            $joke = trim($joke['setup'] . "\n ... \n " . $joke['delivery']);
        }

        Sanitizer::$sh->text(['<fg=white;options=bold>[INFO] </>This can take a while... 😴 Do u wanna hear a joke?', '<fg=yellow;options=bold>' . $joke . '</>', "\n"]);
    }
}
?>