<?php
declare(strict_types = 1);

namespace MailRoutine\App\Service;

use icanhazstring\SymfonyConsoleSpinner\SpinnerProgress;
use phpseclib3\Crypt\PublicKeyLoader;
use MailRoutine\Config\SSHConfiguration;
use NotAuthenticatedException;
use phpseclib3\Net\SFTP;
use RuntimeException;

class SSH{

    private static SFTP $connection;
    private static object $sshConfig;
    
    private SpinnerProgress $spinnerProgress;

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

    private static function authenticate() : void {
        self::$sshConfig->method === 'RSA_KEY' ?
            self::authByRsaKey() : self::authByLogin();
    }

    private function isConnectedAndAuthenticated() : bool {
        return self::$connection->isConnected() and self::$connection->isAuthenticated();
    }

    private function createTempLogFile() {
        self::$connection->exec('rm /tmp/mail.log');
        self::$connection->exec('scp ' . self::$sshConfig->sshOverSshHost . ':/var/log/mail.log /tmp/mail.log');
    }
       
    // TODO: implement the logic to get lines form SMTP logs
    public function getSmtpLogLines() {
        if(!$this->isConnectedAndAuthenticated())
            throw new NotAuthenticatedException('You\'re no connected or authenticated.');

        $useSshOverSsh = self::$sshConfig->sshOverSsh;
        $logFile = '/var/log/mail.log';

        if($useSshOverSsh) {
            $this->createTempLogFile();
            $logFile = '/tmp/mail.log';
        }

        $this->spinnerProgress = Sanitizer::$sh->createSpinnerProgress(PHP_INT_MAX, "Downloading log throug SFTP...");

        $lines =  array_filter(
            preg_split("/(\r\n|\n|\r)/", self::$connection->get($logFile, false, 0, -1, function($offset) {
                $this->spinnerProgress->advance();
                $this->spinnerProgress->setMessage("Downloading log throug SFTP... <logo>" . round($offset/1000000, 2) . "mb</> downloaded.");
            })),
            function($line){
                return strpos($line, 'status=bounced') !== false or strpos($line, 'status=deferred') !== false;
            }
        );
        
        $this->spinnerProgress->setMessage("Log downloaded!");
        $this->spinnerProgress->finish();

        return $lines;
    }
}
?>