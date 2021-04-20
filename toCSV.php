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
use League\CLImate\CLImate;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

try{
    $climate = new CLImate;
}catch(Exception $ex){
    echo $ex->getTraceAsString();
}

try{

    $reader = new InputReader(...$inputDir);
    $writer = new CSVWriter($outputCSV);
    $parserFactory = new ParserFactory();

    $logger = new Logger('pad-converter');
    $logger->pushHandler(new StreamHandler('php://stdout'));
    
    $processor = new Processor($reader, $writer, $parserFactory, $logger);
    $processor->convert();
} catch (Exception $ex) {
    $climate->backgroundRed()->white()->out($ex->getTraceAsString());
}