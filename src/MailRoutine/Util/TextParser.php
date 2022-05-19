<?php
namespace MailRoutine\Util;
use MailRoutine\Util\KeyValue;

class TextParser{
    public static function parse(string $text, KeyValue ...$keyValues) : string {
        $newText = $text;
        foreach($keyValues as $keyValue)
            $newText = str_replace('{{' . $keyValue->getKey() . '}}', $keyValue->getValue(), $newText);
        
        return $newText; 
    }
}
?>