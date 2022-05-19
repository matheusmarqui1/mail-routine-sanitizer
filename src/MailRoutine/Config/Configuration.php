<?php

declare(strict_types=1);

namespace MailRoutine\Config;

use Dotenv\Dotenv;
use \Symfony\Component\Console\Command\Command;
use \Symfony\Component\Console\Input\InputOption;

use InvalidArgumentException;

class Configuration{
    private static Dotenv $environmentVars;
    private static Configuration $instance;

    private function __construct() {
        self::$environmentVars = Dotenv::createImmutable(MAILROUTINE_BASE_PATH);
        self::$environmentVars->load();
    }

    public static function getInstance() : self {
        return self::$instance === null ? new self : self::$instance;
    }

    public static function getResourcePath(string $resourceFolder, ?string $resourceFilename = '') {
        $possibleDir = MAILROUTINE_BASE_PATH . '/src/MailRoutine/' . $resourceFolder . '/';
        if( !is_dir($possibleDir) || 
            ( $resourceFilename != '' && !is_file($possibleDir . $resourceFilename) ) 
        ) throw new InvalidArgumentException(
            'The resource directory or filename doesn\'t exist ' . $possibleDir . $resourceFilename);

        return $possibleDir . $resourceFilename;
            
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

    private function parseBooleanVarFromEnvironment(string $envKey) : bool{
        return filter_var($this->getEnvironmentVar($envKey), FILTER_VALIDATE_BOOLEAN);
    }

    public function getOptions() {
        return [
            ['keywords', 'k', InputOption::VALUE_OPTIONAL, 'Defined keywords to search in.', 
                $this->parseBooleanVarFromEnvironment('USE_DEFINED_KEYWORDS')],
            ['file', 'f', InputOption::VALUE_OPTIONAL, 'Filename to search in.', 
                $this->parseBooleanVarFromEnvironment('USE_DEFINED_FILE')],
            ['period', 'p', InputOption::VALUE_REQUIRED, 'Period to search in - Format ex.: May 13 to May 14', false],
            ['ssh', 's', InputOption::VALUE_OPTIONAL, 
            'Use ssh to speed up the process connecting directly to the SMTP server.', 
                $this->parseBooleanVarFromEnvironment('USE_SSH')],
            ['auto-import', 'a', InputOption::VALUE_OPTIONAL, 
            'Import contacts to defined Mautic\' segments after the process.', 
                $this->parseBooleanVarFromEnvironment('IMPORT_TO_MAUTIC')],
            ['log-email', 'l', InputOption::VALUE_OPTIONAL, 'Send log e-mail.', 
                $this->parseBooleanVarFromEnvironment('SEND_LOG_EMAIL')]
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