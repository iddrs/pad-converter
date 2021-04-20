<?php
namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Data\Data;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserInterface;
use PTK\DataFrame\DataFrame;
use PTK\DataFrame\Reader\FixedWidthFieldReader;

abstract class ParserAbstract implements ParserInterface
{

    public function parse(Data $data): DataFrame {
        ini_set('memory_limit', 4 * 1024 * 1024 * 1024); //seta o limite de memória para o php porque esse arquivo é grande
        rewind($data->fileHandle());
        $reader = new FixedWidthFieldReader($data->fileHandle(), false, 1, ...$this->colSizes);
        $dataFrame = new DataFrame($reader);
        $dataFrame = $this->removeFinalizador($dataFrame);
        $dataFrame = $this->setColNames($dataFrame, $this->colNames);
        $dataFrame = $this->transform($dataFrame);
        return $dataFrame;
    }
    
    abstract protected function transform(DataFrame $dataFrame): DataFrame;
    
    protected function setColNames(DataFrame $dataFrame, array $colNames): DataFrame {
        return $dataFrame->setColNames(...$colNames);
    }
    
    protected function removeFinalizador(DataFrame $dataFrame): DataFrame
    {
        $lines = $dataFrame->seek(function(array $line): bool {
            return str_starts_with(strtolower($line[array_key_first($line)]), 'f');
        });
        return $dataFrame->removeLines(...$lines);
    }
}