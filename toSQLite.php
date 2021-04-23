<?php

/**
 * Converte so txt para SQLite
 */
require_once 'vendor/autoload.php';
require 'config.php';

use IDDRS\SIAPC\PAD\Converter\Parser\ParserFactory;
use IDDRS\SIAPC\PAD\Converter\Processor\Processor;
use IDDRS\SIAPC\PAD\Converter\Reader\InputReader;
use IDDRS\SIAPC\PAD\Converter\Writer\SQLiteWriter;
use League\CLImate\CLImate;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PTK\FS\Directory;

try {
    $climate = new CLImate;
} catch (Exception $ex) {
    echo $ex->getTraceAsString();
}

try {
    if(file_exists($outputSQLite)){
        unlink($outputSQLite);
    }
} catch (Exception $ex) {
    $climate->backgroundRed()->white()->out($ex->getMessage());
}

try {

    $reader = new InputReader(...$inputDir);
    $writer = new SQLiteWriter($outputSQLite);
    $parserFactory = new ParserFactory();

    $logger = new Logger('pad-converter');
    $logger->pushHandler(new StreamHandler('php://stdout'));

    $processor = new Processor($reader, $writer, $parserFactory, $logger);
    $processor->convert();
} catch (Exception $ex) {
    $climate->backgroundRed()->white()->out($ex->getMessage());
}