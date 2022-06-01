<?php

declare(strict_types=1);

namespace icanhazstring\SymfonyConsoleSpinner;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class SpinnerProgress
{
    private const CHARS = ['⠏', '⠛', '⠹', '⢸', '⣰', '⣤', '⣆', '⡇'];

    /**
     * @var ProgressBar
     */
    private $progressBar;
    /**
     * @var int
     */
    private $step;

    public function __construct(OutputInterface $output, int $max = 0)
    {
        $this->progressBar = new ProgressBar($output->section(), $max);
        $this->progressBar->setBarCharacter('<fg=green;options=bold>✔</>');
        $this->progressBar->setFormat('%bar%  %message%');
        $this->progressBar->setBarWidth(1);
        $this->progressBar->maxSecondsBetweenRedraws(0.02);
        $this->progressBar->minSecondsBetweenRedraws(0.005);
        $this->progressBar->setRedrawFrequency(31);

        $this->step = 0;
    }

    private function rand_color() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    public function advance(int $step = 1): void
    {
        $this->step += $step;
        $color = $this->rand_color();
        $this->progressBar->setProgressCharacter("<fg=$color>" . self::CHARS[$this->step % 8] . "</>");
        $this->progressBar->advance($step);
    }

    public function setMessage(string $message): void
    {
        $this->progressBar->setMessage($message, 'message');
    }

    public function finish(): void
    {
        $this->progressBar->finish();
    }
}
