<?php
namespace MailRoutine\App;

use MailRoutine\View\Interact;
use InteractInput;
use MailRoutine\App\Service\Sanitizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MailRoutine\Config\Configuration;
use MailRoutine\Util\KeyValue;
use MailRoutine\Util\TextParser;
use Symfony\Component\Console\Style\SymfonyStyle;
use MailRoutine\View\StyleHelper;

class MailRoutine extends Command
{
    private static $io;

    public function __construct() {
        parent::__construct();
    }

    protected function configure() {
        Configuration::getInstance()->configureCommand($this);
    }

    protected function interact(InputInterface $input, OutputInterface $output) {
        $interact = new Interact($input, $output);
        $interact->verifyInputsAndAskForRequiredOnes();
    }


    protected function execute(InputInterface $input, OutputInterface $output) {
        (new Sanitizer($input, $output))->begin();
        
        return Command::SUCCESS;
    }
}
