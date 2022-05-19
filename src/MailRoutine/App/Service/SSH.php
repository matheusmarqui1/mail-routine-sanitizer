<?php
declare(strict_types = 1);

namespace MailRoutine\App\Service;
use phpseclib3\Crypt\PublicKeyLoader;
use MailRoutine\Config\SSHConfiguration;
use phpseclib3\Net\SFTP;
use RuntimeException;

class SSH{

    private static SFTP $connection;
    private static object $sshConfig;

    public function __construct() {
        self::$sshConfig = SSHConfiguration::retrieve();
        self::$connection = self::getConnection();
        self::authenticate();
    }

    private static function getConnection() : SFTP {
        return new SFTP(self::$sshConfig->host, self::$sshConfig->port);
    }

    private static function authByRsaKey() : void {
        self::$connection->login(
            self::$sshConfig->user, PublicKeyLoader::load(
                file_get_contents(self::$sshConfig->rsa_key)));
    }

    private static function authByLogin() : void {
        self::$connection->login(
            self::$sshConfig->user, self::$sshConfig->pass);
    }

    private static function authenticate() : bool {
        self::$sshConfig->method === 'RSA_KEY' ?
            self::authByRsaKey() : self::authByLogin();

        return (self::$connection->isConnected() and self::$connection->isAuthenticated());
    }
       
    // TODO: implement the logic to get lines form SMTP logs
    public function getSmtpLogLines() {
        return (self::$connection->isConnected() and self::$connection->isAuthenticated());
    }
}
?>