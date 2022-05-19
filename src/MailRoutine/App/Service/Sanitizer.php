<?php
namespace MailRoutine\App\Service;

use MailRoutine\View\StyleHelper;
use MailRoutine\App\Service\SSH;
use MailRoutine\Util\TextParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mime\Part\TextPart;

class Sanitizer{
    
    private static StyleHelper $sh;
    
    private static $lines = []; 

    public function __construct(
        InputInterface $inputInterface,
        OutputInterface $outputInterface
    ) {
        self::$sh = new StyleHelper(
            $inputInterface,
            $outputInterface
        );
        
    }

    public function begin() : int {
        /*
            cases: --ssh=true (boolean true), --ssh (null) => should use
        */
        $shoudUseSSH = TextParser::toBoolean(self::$sh->getOptionFromInput('ssh'));
        if($shoudUseSSH || $shoudUseSSH === null) {
            if((new SSH)->getSmtpLogLines()) self::$sh->success('We\'re connected!');
        }
        return Command::SUCCESS;
    }
}

?>