<?php
namespace IDDRS\SIAPC\PAD\Converter\Data;

use DateTime;
use PTK\DataFrame\DataFrame;

class Data
{
    protected string $filePath;
    protected $fileHandle;
    protected DateTime $initialDate;
    protected DateTime $finalDate;
    protected DateTime $generationDate;
    protected string $cnpj;
    protected string $entityName;
    protected int $numRecords;
    protected string $fileId;
    protected DataFrame $data;

    public function __construct(string $filePath, $fileHandle, DateTime $initialDate, DateTime $finalDate, DateTime $generationDate, string $cnpj, string $entityName, int $numRecords, string $fileId)
    {
        $this->filePath = $filePath;
        $this->fileHandle = $fileHandle;
        $this->initialDate = $initialDate;
        $this->finalDate = $finalDate;
        $this->generationDate = $generationDate;
        $this->cnpj = $cnpj;
        $this->entityName = $entityName;
        $this->numRecords = $numRecords;
        $this->fileId = $fileId;
    }

    public function filePath(): string
    {
        return $this->filePath;
    }

    public function fileHandle()
    {
        return $this->fileHandle;
    }

    public function initialDate(): DateTime
    {
        return $this->initialDate;
    }

    public function finalDate(): DateTime
    {
        return $this->finalDate;
    }

    public function generationDate(): DateTime
    {
        return $this->generationDate;
    }

    public function cnpj(): string
    {
        return $this->cnpj;
    }

    public function entityName(): string
    {
        return $this->entityName;
    }

    public function numRecords(): int
    {
        return $this->numRecords;
    }

    public function fileId(): string
    {
        return $this->fileId;
    }

    public function setDataFrame(DataFrame $dataFrame): void
    {
        $this->data = $dataFrame;
    }

    public function dataFrame(): DataFrame
    {
        return $this->data;
    }
}