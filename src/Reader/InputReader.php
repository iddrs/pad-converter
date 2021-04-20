<?php
namespace IDDRS\SIAPC\PAD\Converter\Reader;

use DateTime;
use IDDRS\SIAPC\PAD\Converter\Data\Data;
use IDDRS\SIAPC\PDA\Converter\Exception\ErrorException;
use IDDRS\SIAPC\PDA\Converter\Exception\WarningException;
use Iterator;

class InputReader implements Iterator
{
    protected array $files = [];

    public function __construct(string ...$inputDir)
    {
        foreach ($inputDir as $dir) {
            if (!is_dir($dir)) {
                throw new ErrorException("$dir não existe ou não é diretório.");
            }

            $this->scanFiles($dir);
        }
    }

    protected function scanFiles(string $inputDir): void
    {
        $iterator = new \DirectoryIterator($inputDir);
        

        while ($iterator->valid()) {
            if ($iterator->isFile()) {
                $this->files[] = $iterator->getPathname();
            }
            $iterator->next();
        }
    }

    protected function readFile(string $fileName): Data
    {
        $extension = '.' . pathinfo($fileName, PATHINFO_EXTENSION);
        $fileId = basename($fileName, $extension);

        $handle = fopen($fileName, 'r');
        if ($handle === false) {
            throw new WarningException("Não foi possível ler de $fileName");
        }

        $headerData = $this->parseFileHeader($handle);
        $initialDate = $headerData['initialDate'];
        $finalDate = $headerData['finalDate'];
        $generationDate = $headerData['generationDate'];
        $cnpj = $headerData['cnpj'];
        $entityName = $headerData['entityName'];


        $numRecords = $this->parseEOF($handle);


        $data = new Data($fileName, $handle, $initialDate, $finalDate, $generationDate, $cnpj, $entityName, $numRecords, $fileId);

        return $data;
    }

    protected function parseFileHeader($handle): array
    {
        $data = fgets($handle);
        $result['cnpj'] = substr($data, 0, 14);
        $result['initialDate'] = DateTime::createFromFormat('dmY', substr($data, 14, 8));
        $result['finalDate'] = DateTime::createFromFormat('dmY', substr($data, 22, 8));
        $result['generationDate'] = DateTime::createFromFormat('dmY', substr($data, 30, 8));
        $result['entityName'] = trim(substr($data, 38, 80));

        return $result;
    }

    protected function parseEOF($handle): int
    {
        do {
            $buffer = fgets($handle);
            if (str_starts_with(strtolower($buffer), 'finalizador')) {
                return (int) substr($buffer, 11);
            }
        }while ($buffer !== false);

        throw new WarningException("Finalizador não encontrado.");
    }

    public function getInput(): bool|Data
    {
        $data = $this->current();
        $this->next();
        return $data;
    }

    public function current()
    {
        $current = current($this->files);
        if ($current === false) {
            return false;
        }
        return $this->readFile($current);
    }

    public function key()
    {
        return key($this->files);
    }

    public function next(): void
    {
        next($this->files);
    }

    public function rewind(): void
    {
        rewind($this->files);
    }

    public function valid(): bool
    {
        if ($this->key() === null) {
            return false;
        }

        return true;
    }
}
