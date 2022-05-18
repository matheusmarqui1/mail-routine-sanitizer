<?php
namespace MailRoutine\View;
use MailRoutine\Config\Configuration;
use MailRoutine\View\StyleHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use MailRoutine\Util\KeyValue;
use MailRoutine\Util\TextParser;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class Interact{
    private InputInterface $inputInterface;
    private StyleHelper $styleHelper;

    public function __construct(InputInterface $inputInterface, OutputInterface $outputInterface)
    {
        $this->inputInterface = $inputInterface;
        $this->styleHelper =  new StyleHelper($outputInterface, $inputInterface);
        $this->init();
    }

    private function init() {
        $this->styleHelper->text(
            TextParser::parse(
                file_get_contents(Configuration::getResourcePath('View/templates', 'logo.template')),
                KeyValue::builder()->key('mr.version')->value('1.0.0')->build()
            )
        );
    }

    private function askForRequiredInput($name, $description) {
        return $this->styleHelper
            ->ask('The value of \'' . $name . '\' is required (' . $description . ')');
    }

    public function verifyInputsAndAskForRequiredOnes() {
        foreach(Configuration::getInstance()->getOptions() as $option) {
           list($name, $alias, $inputRequirement, $description, $defaultValue) = $option;
           $value = $this->inputInterface->getOption($name);
           if( $inputRequirement === InputOption::VALUE_REQUIRED && (!$value || $value === '' || $value === null) )
                $this->inputInterface->setOption($name, $this->askForRequiredInput($name, $description));
        }
    }
}
?>