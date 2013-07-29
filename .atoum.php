<?php

$runner->setBootstrapFile( __DIR__ . '/tests/units/bootstrap.php' );

$script->addTestAllDirectory('tests/units');

$runner->disableCodeCoverage();

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
