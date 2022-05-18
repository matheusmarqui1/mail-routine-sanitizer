<?php
namespace MailRoutine\Util;

class KeyValue{
    private $key;
    private $value;

    private function __construct() {}

    public static function builder() {
        return new self;
    }

    public function key(string $key) {
        $this->key = $key;
        return $this;
    }

    public function value(string $value) {
        $this->value = $value;
        return $this;
    }

    public function unset() {
        unset($this->value);
        unset($this->key);
    }

    public function getKey() {
        return $this->key;
    }

    public function getValue() {
        return $this->value;
    }

    public function build() {
        return $this;
    }

}
?>