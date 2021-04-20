<?php
namespace IDDRS\SIAPC\PAD\Converter\Writer;

use IDDRS\SIAPC\PAD\Converter\Data\Data;
use IDDRS\SIAPC\PAD\Converter\Exception\ErrorException;
use IDDRS\SIAPC\PAD\Converter\Writer\WriterInterface;
use PTK\DataFrame\Writer\CSVWriter as DataFrameWriter;
use PTK\FS\Directory;
use PTK\FS\Path;


class CSVWriter implements WriterInterface
{
    protected Directory $directory;
    
    public function __construct(string $directory)
    {
        $this->directory = Directory::create($directory);
    }

    public function saveOutput(Data $data): void
    {
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
//        print_r($data->dataFrame());exit();
        $writer = new DataFrameWriter($data->dataFrame(), $handle, ';', $hasHeader);
        $writer->write();
        fclose($handle);
    }
    
    
}