<?php
namespace IDDRS\SIAPC\PAD\Converter\Writer;

use IDDRS\SIAPC\PAD\Converter\Data\Data;

interface WriterInterface
{
    public function saveOutput(Data $data): void;
}