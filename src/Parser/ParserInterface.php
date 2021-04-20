<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Data\Data;
use PTK\DataFrame\DataFrame;

interface ParserInterface
{
    public function parse(Data $data): DataFrame;
    
}