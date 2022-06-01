<?php

declare(strict_types = 1);
namespace MailRoutine\Model;

class Email{
    private string $username;
    private string $domain;

    public function __construct(string $email)
    {
        list($this->username, $this->domain) = explode('@', $email);
    }

    public function setUsername(string $username) : void {
        $this->username = $username;
    }

    public function setDomain(string $domain) : void {
        $this->domain = $domain;
    }

    public function getUsername() : string {
        return $this->username;
    }

    public function getDomain() : string {
        return $this->domain;
    }

    public function getEmail() : string {
        return implode('@', [$this->username, $this->domain]);
    }
}
?>