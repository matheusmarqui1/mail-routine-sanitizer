<?php
namespace MailRoutine\App\Service;

use MailRoutine\View\StyleHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Sanitizer{
    
    private static StyleHelper $sh;

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
        self::$sh->text("Hello world.");
        return Command::SUCCESS;
    }
}

?>