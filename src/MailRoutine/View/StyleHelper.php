<?php

declare(strict_types=1);

namespace MailRoutine\View;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use icanhazstring\SymfonyConsoleSpinner\SpinnerProgress;

class StyleHelper extends SymfonyStyle{
    private static $inputInterface;
    private static $outputInterface;

    public function __construct(OutputInterface $outputInterface, InputInterface $inputInterface) {
        self::$inputInterface = $inputInterface;
        self::$outputInterface = $outputInterface;

        $this->configureCustomStyle();

        parent::__construct(self::$inputInterface, self::$outputInterface);
    }

    private function configureCustomStyle() {
        self::$outputInterface
            ->getFormatter()
                ->setStyle('logo', new OutputFormatterStyle('#dd4baa', null, ['bold', 'blink']));
        self::$outputInterface
            ->getFormatter()
                ->setStyle('version', new OutputFormatterStyle('#ebc034', null, ['bold']));
    }

    public static function getIO() {
        return new SymfonyStyle(self::$inputInterface, self::$outputInterface);
    }

    /* @override */
    public function createProgressBar(int $max = 0) : ProgressBar {
        $progressBar = parent::createProgressBar($max);

        if ('\\' !== \DIRECTORY_SEPARATOR || 'Hyper' === getenv('TERM_PROGRAM')) {
            $progressBar->setFormat('%message%' . PHP_EOL . '%current%/%max% [%bar%]' . PHP_EOL);
            $progressBar->setBarCharacter('<fg=green>▓</>');
            $progressBar->setEmptyBarCharacter("<fg=red>▒</>");
            $progressBar->setProgressCharacter("<fg=white>░</>");
            $progressBar->setRedrawFrequency(100);
            $progressBar->maxSecondsBetweenRedraws(0.000002);
            $progressBar->minSecondsBetweenRedraws(0.000001);
            $progressBar->setMessage('Loading...');
        }

        return $progressBar;
    }

    public function createSpinnerProgress(int $max = 0) : SpinnerProgress {
        return new SpinnerProgress(self::$outputInterface, $max);
    }
}
?>