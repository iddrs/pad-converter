<?php

/**
 * Converte so txt para SQLite
 */
require_once 'vendor/autoload.php';
require 'config.php';

use PTK\FS\Path;
use PTK\FS\Directory;
use PTK\Log\Logger\Logger;
use PTK\DataFrame\DataFrame;
use PTK\Log\Writer\CLImateWriter;
use PTK\DataFrame\Reader\CSVReader;
use PTK\Log\Formatter\CLImateFormatter;
use IDDRS\SIAPC\PAD\Converter\Writer\CSVWriter;
use IDDRS\SIAPC\PAD\Converter\Reader\InputReader;
use PTK\DataFrame\Writer\CSVWriter as CSVWriter2;
use IDDRS\SIAPC\PAD\Converter\Processor\Processor;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserFactory;
use IDDRS\SIAPC\PAD\Converter\Exception\ErrorException;
use IDDRS\SIAPC\PAD\Converter\Assembler\BalRecAltAssembler;
use IDDRS\SIAPC\PAD\Converter\Assembler\PagamentoAssembler;
use IDDRS\SIAPC\PAD\Converter\Assembler\BrecAntAltAssembler;
use IDDRS\SIAPC\PAD\Converter\Assembler\LiquidacaoAssembler;
use IDDRS\SIAPC\PAD\Converter\Assembler\RestosAPagarAssembler;

try {
    $defaultWriter = new CLImateWriter();
    $defaultFormatter = new CLImateFormatter();
    $logger = new Logger($defaultWriter, $defaultFormatter);
} catch (Exception $ex) {
    echo $ex->getMessage();
    exit($ex->getCode());
}

try {
    if (file_exists($outputCSV)) {
        $logger->info('Apagando conteúdo original...');
        $oCSV = new Directory($outputCSV);
        $oCSV->recursive()->delete();
    }
} catch (Exception $ex) {
    $logger->notice($ex->getTraceAsString());
}

try {

    $reader = new InputReader(...$inputDir);
    $writer = new CSVWriter($outputCSV);
    $parserFactory = new ParserFactory();

    $processor = new Processor($reader, $writer, $parserFactory, $logger);
    $processor->convert();
} catch (Exception $ex) {
    $logger->emergency($ex->getTraceAsString());
    exit($ex->getCode());
}

try {
    $logger->info('Gerando arquivo LIQUIDACAO...');

    $empenhoPath = new Path($outputCSV, 'EMPENHO.csv');
    $empenhoHandle = fopen($empenhoPath->getRealPath(), 'r');
    if ($empenhoHandle === false) {
        throw new ErrorException("Falha ao abrir {$empenhoPath->getPath()}");
    }
    $empenho = new DataFrame(new CSVReader($empenhoHandle, ';', true));

    $liquidacPath = new Path($outputCSV, 'LIQUIDAC.csv');
    $liquidacHandle = fopen($liquidacPath->getRealPath(), 'r');
    if ($liquidacHandle === false) {
        throw new ErrorException("Falha ao abrir {$liquidacPath->getPath()}");
    }
    $liquidac = new DataFrame(new CSVReader($liquidacHandle, ';', true));

    $liquidacaoAssembler = new LiquidacaoAssembler($liquidac, $empenho);
    $dfLiquidacao = $liquidacaoAssembler->assemble();

    $liquidacaoOutput = new Path($outputCSV, 'LIQUIDACAO.csv');
    $liquidacaoHandle = fopen($liquidacaoOutput->getPath(), 'w');
    if ($liquidacaoHandle === false) {
        throw new ErrorException("Falha ao abrir {$liquidacaoOutput->getPath()}");
    }
    $liquidacaoWriter = new CSVWriter2($dfLiquidacao, $liquidacaoHandle, ';', true);
    $liquidacaoWriter->write();
} catch (Exception $ex) {
    $logger->emergency($ex->getTraceAsString());
    exit($ex->getCode());
}

try {
    $logger->info('Gerando arquivo PAGAMENTO...');

    $empenhoPath = new Path($outputCSV, 'EMPENHO.csv');
    $empenhoHandle = fopen($empenhoPath->getRealPath(), 'r');
    if ($empenhoHandle === false) {
        throw new ErrorException("Falha ao abrir {$empenhoPath->getPath()}");
    }
    $empenho = new DataFrame(new CSVReader($empenhoHandle, ';', true));

    $pagamentPath = new Path($outputCSV, 'PAGAMENT.csv');
    $pagamentHandle = fopen($pagamentPath->getRealPath(), 'r');
    if ($pagamentHandle === false) {
        throw new ErrorException("Falha ao abrir {$pagamentPath->getPath()}");
    }
    $pagament = new DataFrame(new CSVReader($pagamentHandle, ';', true));

    $pagamentoAssembler = new PagamentoAssembler($pagament, $empenho);
    $dfPagamento = $pagamentoAssembler->assemble();

    $pagamentoOutput = new Path($outputCSV, 'PAGAMENTO.csv');
    $pagamentoHandle = fopen($pagamentoOutput->getPath(), 'w');
    if ($pagamentoHandle === false) {
        throw new ErrorException("Falha ao abrir {$pagamentoOutput->getPath()}");
    }
    $pagamentoWriter = new CSVWriter2($dfPagamento, $pagamentoHandle, ';', true);
    $pagamentoWriter->write();
} catch (Exception $ex) {
    $logger->emergency($ex->getTraceAsString());
    exit($ex->getCode());
}

try {
    $logger->info('Gerando arquivo RESTOS_PAGAR...');

    $empenhoPath = new Path($outputCSV, 'EMPENHO.csv');
    $empenhoHandle = fopen($empenhoPath->getRealPath(), 'r');
    if ($empenhoHandle === false) {
        throw new ErrorException("Falha ao abrir {$empenhoPath->getPath()}");
    }
    $empenho = new DataFrame(new CSVReader($empenhoHandle, ';', true));
    
    $liquidacPath = new Path($outputCSV, 'LIQUIDAC.csv');
    $liquidacHandle = fopen($liquidacPath->getRealPath(), 'r');
    if ($liquidacHandle === false) {
        throw new ErrorException("Falha ao abrir {$liquidacPath->getPath()}");
    }
    $liquidac = new DataFrame(new CSVReader($liquidacHandle, ';', true));

    $pagamentPath = new Path($outputCSV, 'PAGAMENT.csv');
    $pagamentHandle = fopen($pagamentPath->getRealPath(), 'r');
    if ($pagamentHandle === false) {
        throw new ErrorException("Falha ao abrir {$pagamentPath->getPath()}");
    }
    $pagament = new DataFrame(new CSVReader($pagamentHandle, ';', true));

    $rpAssembler = new RestosAPagarAssembler($empenho, $liquidac, $pagament);
    $dfRP = $rpAssembler->assemble();
    
    $colTypes = $dfRP->getColTypes();
        
    foreach($colTypes as $colName => $type){
        switch($type){
            case 'double':
            case 'float':
            $dfRP->applyOnCols($colName, function($cell){
                return number_format($cell, 2, ',', '.');
            });
            break;
        }
    }

    $rpOutput = new Path($outputCSV, 'RESTOS_PAGAR.csv');
    $rpHandle = fopen($rpOutput->getPath(), 'w');
    if ($rpHandle === false) {
        throw new ErrorException("Falha ao abrir {$rpOutput->getPath()}");
    }
    $rpWriter = new CSVWriter2($dfRP, $rpHandle, ';', true);
    $rpWriter->write();
} catch (Exception $ex) {
    $logger->emergency($ex->getTraceAsString());
    exit($ex->getCode());
}

try {
    $logger->info('Gerando arquivo BAL_REC_ALT...');

    $balRecPath = new Path($outputCSV, 'BAL_REC.csv');
    $balRecHandle = fopen($balRecPath->getRealPath(), 'r');
    if ($balRecHandle === false) {
        throw new ErrorException("Falha ao abrir {$balRecPath->getPath()}");
    }
    $balRec = new DataFrame(new CSVReader($balRecHandle, ';', true));
    
    $balRecAltAssembler = new BalRecAltAssembler($balRec);
    $dfBalRecAlt = $balRecAltAssembler->assemble();

    $balRecAltOutput = new Path($outputCSV, 'BAL_REC_ALT.csv');
    $balRecAltHandle = fopen($balRecAltOutput->getPath(), 'w');
    if ($balRecAltHandle === false) {
        throw new ErrorException("Falha ao abrir {$balRecAltOutput->getPath()}");
    }
    $balRecAltWriter = new CSVWriter2($dfBalRecAlt, $balRecAltHandle, ';', true);
    $balRecAltWriter->write();
} catch (Exception $ex) {
    $logger->emergency($ex->getTraceAsString());
    exit($ex->getCode());
}

try {
    $logger->info('Gerando arquivo BREC_ANT_ALT...');

    $brecAntPath = new Path($outputCSV, 'BREC_ANT.csv');
    $brecAntHandle = fopen($brecAntPath->getRealPath(), 'r');
    if ($brecAntHandle === false) {
        throw new ErrorException("Falha ao abrir {$brecAntPath->getPath()}");
    }
    $brecAnt = new DataFrame(new CSVReader($brecAntHandle, ';', true));
    
    $brecAntAltAssembler = new BrecAntAltAssembler($brecAnt);
    $dfBrecAntAlt = $brecAntAltAssembler->assemble();
    
    $brecAntAltOutput = new Path($outputCSV, 'BREC_ANT_ALT.csv');
    $brecAntAltHandle = fopen($brecAntAltOutput->getPath(), 'w');
    if ($brecAntAltHandle === false) {
        throw new ErrorException("Falha ao abrir {$brecAntAltOutput->getPath()}");
    }
    $brecAntAltWriter = new CSVWriter2($dfBrecAntAlt, $brecAntAltHandle, ';', true);
    $brecAntAltWriter->write();
} catch (Exception $ex) {
    $logger->emergency($ex->getTraceAsString());
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
    $logger->error($ex->getTraceAsString());
    exit($ex->getCode());
}