<?php

/**
 * Converte so txt para SQLite
 */
require_once 'vendor/autoload.php';
require 'config.php';

use IDDRS\SIAPC\PAD\Converter\Parser\ParserFactory;
use IDDRS\SIAPC\PAD\Converter\Processor\Processor;
use IDDRS\SIAPC\PAD\Converter\Reader\InputReader;
use IDDRS\SIAPC\PAD\Converter\Writer\CSVWriter;
use PTK\FS\Directory;
use PTK\Log\Formatter\CLImateFormatter;
use PTK\Log\Logger\Logger;
use PTK\Log\Writer\CLImateWriter;

try {
    $defaultWriter = new CLImateWriter();
    $defaultFormatter = new CLImateFormatter();
    $logger = new Logger($defaultWriter, $defaultFormatter);
} catch (Exception $ex) {
    echo $ex->getMessage();
    exit($ex->getCode());
}

try {
    $logger->info('Apagando conteúdo original...');
    $oCSV = new Directory($outputCSV);
    $oCSV->recursive()->delete();
} catch (Exception $ex) {
    $logger->emergency($ex->getMessage());
    exit($ex->getCode());
}

try {

    $reader = new InputReader(...$inputDir);
    $writer = new CSVWriter($outputCSV);
    $parserFactory = new ParserFactory();

    $processor = new Processor($reader, $writer, $parserFactory, $logger);
    $processor->convert();
} catch (Exception $ex) {
    $logger->emergency($ex->getMessage());
}

try {
    $logger->info('Apagando conteúdo de latest...');
    $latestPath = new PTK\FS\Path(dirname($outputCSV), 'latest');
    $latestDir = new Directory($latestPath->getPath());
    $latestDir->delete();
    
    $logger->info('Copiando conteúdo para latest...');
    $output = new Directory($outputCSV);
    $output->copy($latestPath->getPath());
} catch (Exception $ex) {
    $logger->error($ex->getMessage());
    exit($ex->getCode());
}
