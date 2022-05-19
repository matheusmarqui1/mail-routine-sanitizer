<?php
namespace MailRoutine\Util;
use MailRoutine\Util\KeyValue;

class TextParser{
    public static function parse(string $text, KeyValue ...$keyValues) : string {
        $newText = "";
        foreach($keyValues as $keyValue)
            $newText = str_replace('{{' . $keyValue->getKey() . '}}', $keyValue->getValue(), $text);
        
        return $newText; 
    }
}
?>