<?php
namespace IDDRS\SIAPC\PAD\Converter\Writer;

use IDDRS\SIAPC\PAD\Converter\Data\Data;
use IDDRS\SIAPC\PAD\Converter\Writer\WriterInterface;


class SQLiteWriter implements WriterInterface
{
    public function __construct(string $dsn)
    {

    }

    public function saveOutput(Data $data): void
    {

    }
}