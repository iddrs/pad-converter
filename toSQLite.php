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
use PTK\Log\Formatter\CLImateFormatter;
use PTK\Log\Logger\Logger;
use PTK\Log\Writer\CLImateWriter;

try{
    $defaultWriter = new CLImateWriter();
    $defaultFormatter = new CLImateFormatter();
    $logger = new Logger($defaultWriter, $defaultFormatter);
} catch (Exception $ex) {
    echo $ex->getMessage();
    exit($ex->getCode());
}

try {
    if(file_exists($outputSQLite)){
        unlink($outputSQLite);
    }
} catch (Exception $ex) {
    $logger->emergency($ex->getMessage());
    exit($ex->getCode());
}

try {

    $reader = new InputReader(...$inputDir);
    $writer = new SQLiteWriter($outputSQLite);
    $parserFactory = new ParserFactory();

    $processor = new Processor($reader, $writer, $parserFactory, $logger);
    $processor->convert();
} catch (Exception $ex) {
    $climate->backgroundRed()->white()->out($ex->getMessage());
}