<?php

/**
 * Converte so txt para SQLite
 */
require_once 'vendor/autoload.php';
require 'config.php';

use IDDRS\SIAPC\PAD\Converter\Assembler\LiquidacaoAssembler;
use IDDRS\SIAPC\PAD\Converter\Assembler\PagamentoAssembler;
use IDDRS\SIAPC\PAD\Converter\Exception\ErrorException;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserFactory;
use IDDRS\SIAPC\PAD\Converter\Processor\Processor;
use IDDRS\SIAPC\PAD\Converter\Reader\InputReader;
use IDDRS\SIAPC\PAD\Converter\Writer\CSVWriter;
use PTK\DataFrame\DataFrame;
use PTK\DataFrame\Reader\CSVReader;
use PTK\DataFrame\Writer\CSVWriter as CSVWriter2;
use PTK\FS\Directory;
use PTK\FS\Path;
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
    $logger->notice($ex->getMessage());
}

try {

    $reader = new InputReader(...$inputDir);
    $writer = new CSVWriter($outputCSV);
    $parserFactory = new ParserFactory();

    $processor = new Processor($reader, $writer, $parserFactory, $logger);
    $processor->convert();
} catch (Exception $ex) {
    $logger->emergency($ex->getMessage());
    exit($ex->getCode());
}

try {
    $logger->info('Gerando arquivo LIQUIDACAO...');
    
    $empenhoPath = new Path($outputCSV, 'EMPENHO.csv');
    $empenhoHandle = fopen($empenhoPath->getRealPath(), 'r');
    if($empenhoHandle === false){
        throw new ErrorException("Falha ao abrir {$empenhoPath->getPath()}");
    }
    $empenho = new DataFrame(new CSVReader($empenhoHandle, ';', true));
    
    $liquidacPath = new Path($outputCSV, 'LIQUIDAC.csv');
    $liquidacHandle = fopen($liquidacPath->getRealPath(), 'r');
    if($liquidacHandle === false){
        throw new ErrorException("Falha ao abrir {$liquidacPath->getPath()}");
    }
    $liquidac = new DataFrame(new CSVReader($liquidacHandle, ';', true));
    
    $liquidacaoAssembler = new LiquidacaoAssembler($liquidac, $empenho);
    $dfLiquidacao = $liquidacaoAssembler->assemble();
    
    $liquidacaoOutput = new Path($outputCSV, 'LIQUIDACAO.csv');
    $liquidacaoHandle = fopen($liquidacaoOutput->getPath(), 'w');
    if($liquidacaoHandle === false){
        throw new ErrorException("Falha ao abrir {$liquidacaoOutput->getPath()}");
    }
    $liquidacaoWriter = new CSVWriter2($dfLiquidacao, $liquidacaoHandle, ';', true);
    $liquidacaoWriter->write();

} catch (Exception $ex) {
    $logger->emergency($ex->getMessage());
    exit($ex->getCode());
}

try {
    $logger->info('Gerando arquivo PAGAMENTO...');
    
    $empenhoPath = new Path($outputCSV, 'EMPENHO.csv');
    $empenhoHandle = fopen($empenhoPath->getRealPath(), 'r');
    if($empenhoHandle === false){
        throw new ErrorException("Falha ao abrir {$empenhoPath->getPath()}");
    }
    $empenho = new DataFrame(new CSVReader($empenhoHandle, ';', true));
    
    $pagamentPath = new Path($outputCSV, 'PAGAMENT.csv');
    $pagamentHandle = fopen($pagamentPath->getRealPath(), 'r');
    if($pagamentHandle === false){
        throw new ErrorException("Falha ao abrir {$pagamentPath->getPath()}");
    }
    $pagament = new DataFrame(new CSVReader($pagamentHandle, ';', true));
    
    $pagamentoAssembler = new PagamentoAssembler($pagament, $empenho);
    $dfPagamento = $pagamentoAssembler->assemble();
    
    $pagamentoOutput = new Path($outputCSV, 'PAGAMENTO.csv');
    $pagamentoHandle = fopen($pagamentoOutput->getPath(), 'w');
    if($pagamentoHandle === false){
        throw new ErrorException("Falha ao abrir {$pagamentoOutput->getPath()}");
    }
    $pagamentoWriter = new CSVWriter2($dfPagamento, $pagamentoHandle, ';', true);
    $pagamentoWriter->write();

} catch (Exception $ex) {
    $logger->emergency($ex->getMessage());
    exit($ex->getCode());
}

try {
    $logger->info('Apagando conteúdo de latest...');
    $latestPath = new Path(dirname($outputCSV), 'latest');
    $latestDir = new Directory($latestPath->getPath());
    $latestDir->delete();
    
    $logger->info('Copiando conteúdo para latest...');
    $output = new Directory($outputCSV);
    $output->copy($latestPath->getPath());
} catch (Exception $ex) {
    $logger->error($ex->getMessage());
    exit($ex->getCode());
}
