<?php
namespace MailRoutine\Util;
use MailRoutine\Util\KeyValue;

class TextParser{
    public static function replace(string $text, KeyValue ...$keyValues) : string {
        $newText = $text;
        foreach($keyValues as $keyValue)
            $newText = str_replace('{{' . $keyValue->getKey() . '}}', $keyValue->getValue(), $newText);
        
        return $newText; 
    }

    public static function toBoolean($value) : bool {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
?>