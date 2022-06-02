<?php
declare(strict_types = 1);
namespace MailRoutine\App\Service;

class DateFiltering{
    public function __invoke(array $lines) {
        Sanitizer::$sh->text("teste");
    }
}
?>