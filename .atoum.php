<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$runner->addExtension(new \mageekguy\atoum\xml\extension($script));
$runner->disableCodeCoverage();
$runner->setBootstrapFile(__DIR__ . '/tests/bootstrap.php');
$runner->addTestsFromDirectory(__DIR__ . '/tests/units');


## Notifier (growlnotify)
$images = __DIR__ . '/vendor/atoum/atoum/resources/images/logo';

$report = $script->AddDefaultReport();

if(syslibExist('growlnotify') )
{
    $notifier = new \mageekguy\atoum\report\fields\runner\result\notifier\image\growl();
    $notifier
        ->setSuccessImage($images . DIRECTORY_SEPARATOR . 'success.png')
        ->setFailureImage($images . DIRECTORY_SEPARATOR . 'failure.png')
    ; 
    $report->addField($notifier, array(atoum\runner::runStop));
}

/**
 * Return true if library is available on system
 * 
 * @param  string $libName
 * @return boolean
 */
function syslibExist($libName)
{
    return !is_null(shell_exec(sprintf('command -v %s 2>/dev/null', $libName)));
}
