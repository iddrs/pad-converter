<?php
namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Data\Data;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

/**
 * Usado para quando não houver um parser.
 */
class NullParser extends ParserAbstract
{
    public function parse(Data $data): DataFrame
    {

    }
    
    protected function transform(DataFrame $dataFrame): DataFrame {
        return $dataFrame;
    }

}