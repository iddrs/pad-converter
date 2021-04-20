<?php

namespace IDDRS\SIAPC\PAD\Converter\Processor;

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
            
            $this->writer->saveOutput($input);
        };
    }

}
