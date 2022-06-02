<?php
namespace MailRoutine\App\Service;

use MailRoutine\View\StyleHelper;
use MailRoutine\App\Service\SSH;
use MailRoutine\Util\TextParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mime\Part\TextPart;
use MailRoutine\App\Service\DateFiltering;
use MailRoutine\App\Service\KeywordFiltering;
use MailRoutine\Util\Fun;
use Psr\Log\Test\TestLogger;
use Twig\Node\Expression\TestExpression;

class Sanitizer{
    
    public static StyleHelper $sh;
    
    private static array $lines = [];

    public function __construct(
        InputInterface $inputInterface,
        OutputInterface $outputInterface
    ) {
        self::$sh = new StyleHelper(
            $inputInterface,
            $outputInterface
        );
        
    }

    private function doPipeline(array $lines) : void {

        $pipeline = [
            new KeywordFiltering(),
            new DateFiltering()
        ];

        foreach($pipeline as $action) {
            call_user_func($action, self::$lines);
        }

    }

    public function begin() : int {
        //cases: --ssh=true (boolean true), --ssh (null) => should use
        $stringShouldUseSSH = self::$sh->getOptionFromInput('ssh');
        $shoudUseSSH = $stringShouldUseSSH === null || $stringShouldUseSSH == 'true';

        if($shoudUseSSH) {
            Fun::showFormattedJoke();
            try{
                self::$lines = (new SSH)->getSmtpLogLines();
            }catch(\Exception $e) {
                self::$sh->error($e->getMessage());
            }
        }

        $this->doPipeline(self::$lines);
        
        return Command::SUCCESS;
    }
}

?>