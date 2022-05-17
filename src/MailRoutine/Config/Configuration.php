<?php

declare(strict_types=1);

namespace MailRoutine\Config;

use Dotenv\Dotenv;
use \Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

use InvalidArgumentException;

class Configuration{
    private static $environmentVars;
    private static $instance;

    private function __construct() {
        self::$environmentVars = Dotenv::createImmutable(MAILROUTINE_BASE_PATH);
        self::$environmentVars->load();
    }

    public static function getInstance() : self {
        return self::$instance === null ? new self : self::$instance;
    }

    public function getEnvironment() : Dotenv {
        return self::$environmentVars;
    }

    private function environmentKeyExists(string $envKey) : bool {
        return array_key_exists($envKey, $_ENV);
    }

    public function getEnvironmentVar(string $envKey) : string {
        if($this->environmentKeyExists($envKey)) return $_ENV[$envKey];
            else throw new InvalidArgumentException('The environment key doesn\'t exist.');
            
    }

    private function getOptions() {
        return [
            ['keywords', 'k', InputOption::VALUE_OPTIONAL, 'Defined keyword to search.', false],
            ['file', 'f', InputOption::VALUE_OPTIONAL, 'Filename to search in.', false],
            ['period', 'p', InputOption::VALUE_OPTIONAL, 'Period to search in.', false],
            ['ssh', 's', InputOption::VALUE_OPTIONAL, 
            'Use ssh to speed up the process connecting directly to the SMTP server.', false],
            ['auto-import', 'a', InputOption::VALUE_OPTIONAL, 
            'Import contacts to defined Mautic\' segments after the process.', true],
            ['log-email', 'l', InputOption::VALUE_OPTIONAL, 'Send log e-mail.', true]
        ];
    }

    public function configureCommand(Command $command) : void {
        $command->setName('sanitize')
            ->setDescription('Check SMTP e-mail logs and apply rules to sanitize e-mail database.');

        foreach($this->getOptions() as $option) {
            $command->addOption(...$option);
        }        
    }
}
?>