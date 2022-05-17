<?php
namespace MailRoutine\App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MailRoutine\Config\Configuration;
use Symfony\Component\Console\Style\SymfonyStyle;
use MailRoutine\View\StyleHelper;

class MailRoutine extends Command
{
    private static $io;
    private const APP_BASEPATH = MAILROUTINE_BASE_PATH . 'src/MailRoutine/';

    public function __construct() {
        parent::__construct();
    }

    protected function configure() {
        Configuration::getInstance()->configureCommand($this);
    }

    protected function interact(InputInterface $input, OutputInterface $output) {

    }


    protected function execute(InputInterface $input, OutputInterface $output) {
        self::$io = new StyleHelper($output, $input);
        self::$io->text(file_get_contents(self::APP_BASEPATH . 'View/templates/logo.template'));
        return Command::SUCCESS;

    }
}
