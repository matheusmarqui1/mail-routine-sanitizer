<?php

declare(strict_types=1);

namespace MailRoutine\Config;

use Dotenv\Dotenv;
use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputOption;

use InvalidArgumentException;
use MailRoutine\Util\TextParser;
use stdClass;

class SSHConfiguration{
    private static Configuration $cgf;

    private static object $sshConfig;
    
    private static function getAuthMethod() : string {
        return self::$cgf->getEnvironmentVar('SSH_AUTH_METHOD');
    }

    private static function getRSAConfig() : object {
        self::$sshConfig->rsa_key = posix_getpwuid(posix_geteuid())['dir'] . '/.ssh/id_rsa';
        return self::$sshConfig;
    }

    private static function getLoginConfig() : object {
        self::$sshConfig->pass = self::$cgf->getEnvironmentVar('SSH_PASS');
        return self::$sshConfig;
    }

    public static function retrieve() : object {
        
        self::$cgf = Configuration::getInstance();
        self::$sshConfig = (object) array(
            'user' => self::$cgf->getEnvironmentVar('SSH_USER'),
            'host' => self::$cgf->getEnvironmentVar('SSH_HOST'),
            'port' => self::$cgf->getEnvironmentVar('SSH_PORT'),
            'method' => self::$cgf->getEnvironmentVar('SSH_AUTH_METHOD')
        ); 

        return self::getAuthMethod() === 'RSA_KEY' ?
            self::getRSAConfig() : self::getLoginConfig();
    }
}
?>