<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
disable_ob();
chdir(dirname(__DIR__));

header("Content-type: application/octet-stream");
// Need to output 2K text: http://stackoverflow.com/questions/7740646/jquery-ajax-read-the-stream-incrementally
echo str_repeat('°°filler°°', 205) . PHP_EOL;

print (date('H:i:s') . " :: <b>Deploying on prod:</b><br/> \n");

print "<br/>" . str_repeat('-', 114) . "<br/>";

$command = "php " . __DIR__ . "/commands/command1.php";
//$command = "php -r 'print_r(get_defined_constants());'";

$command .= ' 2>&1';

print "<br/>" . str_repeat('-', 114) . "<br/>";

passthru($command, $status);

if ($status !== 0) {
    print (
        date('H:i:s')
        . " :: <b>Error:</b> Command returned errorcode $status. Try to set higher logger level and debug the problem. \n"
    );
} else {
    print (date('H:i:s') . " :: <b>Success:</b> $command \n");
}

function disable_ob()
{
    // Turn off output buffering
    ini_set('output_buffering', 'off');
    // Turn off PHP output compression
    ini_set('zlib.output_compression', false);
    // Implicitly flush the buffer(s)
    ini_set('implicit_flush', true);
    ob_implicit_flush(true);
    // Turn off output buffering
    while (@ob_end_flush()) {
        ;
    }
    // Disable apache output buffering/compression
    if (function_exists('apache_setenv')) {
        apache_setenv('no-gzip', '1');
        apache_setenv('dont-vary', '1');
    }
}