<?php
namespace MailRoutine\View;
use MailRoutine\Config\Configuration;
use MailRoutine\View\StyleHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use MailRoutine\Util\KeyValue;
use MailRoutine\Util\TextParser;

class Interact{
    private StyleHelper $styleHelper;
    private InputInterface $inputInterface;

    public function __construct(StyleHelper $styleHelper, InputInterface $inputInterface)
    {
        $this->styleHelper = $styleHelper;
        $this->inputInterface = $inputInterface;

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

    private function isValidInput() {

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