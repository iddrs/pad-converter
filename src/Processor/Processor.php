<?php

namespace IDDRS\SIAPC\PAD\Converter\Processor;

use IDDRS\SIAPC\PAD\Converter\Data\Data;
use IDDRS\SIAPC\PAD\Converter\Parser\NullParser;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserFactory;
use IDDRS\SIAPC\PAD\Converter\Reader\InputReader;
use IDDRS\SIAPC\PAD\Converter\Writer\WriterInterface;
use Psr\Log\LoggerInterface;

/**
 * Comanda toda a operação.
 * 
 * @author Everton
 *
 */
class Processor {

    protected InputReader $reader;
    protected WriterInterface $writer;
    protected ParserFactory $parserFactory;
    protected LoggerInterface $logger;

    public function __construct(InputReader $reader, WriterInterface $writer, ParserFactory $parserFactory, LoggerInterface $logger) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->parserFactory = $parserFactory;
        $this->logger = $logger;
    }

    /**
     * Executa a conversão
     */
    public function convert(): void {
        while ($this->reader->valid()) {
            $input = $this->reader->getInput();
            $this->logger->info("Processando {$input->fileId()} ..." . PHP_EOL);

            $parser = $this->parserFactory->getFactory($input->fileId());
            
            if($parser instanceof NullParser){
                $this->logger->notice("Parser não localizado para {$input->fileId()}");
                continue;
            }
            
            $input->setDataFrame($parser->parse($input));
            
            $input = $this->appendHeaderData($input);
            
            $this->writer->saveOutput($input);
        };
    }
    
    protected function appendHeaderData(Data $data): Data {
        $dataFrame = $data->dataFrame();
        $numLines = $dataFrame->countLines();
        $initialDate = array_fill(0, $numLines, $data->initialDate()->format('Y-m-d'));
        $finalDate = array_fill(0, $numLines, $data->finalDate()->format('Y-m-d'));
        $generationDate = array_fill(0, $numLines, $data->generationDate()->format('Y-m-d'));
        $cnpj = array_fill(0, $numLines, $data->cnpj());
        $entityName = array_fill(0, $numLines, $data->entityName());
        $file = array_fill(0, $numLines, $data->filePath());
        
        $dataFrame->appendCol('data_inicial', $initialDate);
        $dataFrame->appendCol('data_final', $finalDate);
        $dataFrame->appendCol('data_geracao', $generationDate);
        $dataFrame->appendCol('cnpj', $cnpj);
        $dataFrame->appendCol('entidade', $entityName);
        $dataFrame->appendCol('arquivo', $file);
        
        $data->setDataFrame($dataFrame);
        
        return $data;
    }
}
