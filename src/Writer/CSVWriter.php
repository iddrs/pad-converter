<?php
namespace IDDRS\SIAPC\PAD\Converter\Writer;

use IDDRS\SIAPC\PAD\Converter\Data\Data;
use IDDRS\SIAPC\PAD\Converter\Exception\ErrorException;
use IDDRS\SIAPC\PAD\Converter\Writer\WriterInterface;
use PTK\DataFrame\DataFrame;
use PTK\DataFrame\Writer\CSVWriter as DataFrameWriter;
use PTK\FS\Directory;
use PTK\FS\Path;

/**
 * Escreve os dados para CSV
 */
class CSVWriter implements WriterInterface
{
    protected Directory $directory;
    
    public function __construct(string $directory)
    {
        $this->directory = Directory::create($directory);
    }
    
    public function saveOutput(Data $data): void
    {
        $data->setDataFrame($this->decimalSeparatorChoice($data->dataFrame()));
        
        $path = new Path($this->directory->getDirPath(), "{$data->fileId()}.csv");
        $filename = $path->getPath();
        $hasHeader = true;
        if(file_exists($filename)){
            $hasHeader = false;
        }
        
        $handle = fopen($filename, 'a');
        
        if($handle === false){
            throw new ErrorException("Não foi possível abrir $filename");
        }
        $writer = new DataFrameWriter($data->dataFrame(), $handle, ';', $hasHeader);
        $writer->write();
        fclose($handle);
    }
    
    /**
     * Formata colunas que tem como tipo predominante o float|double para o formato pt_BR
     * 
     * @param DataFrame $dataFrame
     * @return DataFrame
     */
    protected function decimalSeparatorChoice(DataFrame $dataFrame): DataFrame {
        $colTypes = $dataFrame->getColTypes();
        
        foreach($colTypes as $colName => $type){
            switch($type){
                case 'double':
                case 'float':
                $dataFrame->applyOnCols($colName, function($cell){
                    return number_format($cell, 2, ',', '.');
                });
                break;
            }
        }
        
        return $dataFrame;
    }
}