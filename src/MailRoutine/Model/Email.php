<?php

declare(strict_types = 1);
namespace MailRoutine\Model;

class Email{
    private string $username;
    private string $domain;

    public function __construct(string $email)
    {
        $this->setupEmailParts($email);
    }

    private function setupEmailParts(string $email) : void {
        $emailParts =  explode('@', $email);
        $this->setUsername($emailParts[0]);
        $this->setDomain($emailParts[1]);
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