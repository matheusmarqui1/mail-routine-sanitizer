<?php
    class NotAuthenticatedException extends RuntimeException{
        public function __construct(string $message)
        {
            parent::__construct($message);
        }
    }
?>