<?php
namespace MailRoutine\Util;
use MailRoutine\Util\KeyValue;

class TextParser{
    public static function parse(string $text, KeyValue $keyValue) : string {
        return str_replace('{{' . $keyValue->getKey() . '}}', $keyValue->getValue(), $text);
    }
}
?>