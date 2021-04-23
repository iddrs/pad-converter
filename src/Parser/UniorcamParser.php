<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class UniorcamParser extends ParserAbstract {

    protected array $colSizes = [4, 2, 2, 80, 2, 14];
    protected array $colNames = [
        'exercicio',
        'orgao',
        'uniorcam',
        'nome',
        'identificador',
        'cnpj'
    ];

    public function __construct() {
        if (sizeof($this->colNames) !== sizeof($this->colSizes)) {
            throw new WarningException("Número de colunas diferente do número de especificação para $fileId");
        }
    }

    protected function transform(DataFrame $dataFrame): DataFrame {
        $dataFrame->applyOnLines(function($line){
            $return = CodigosFormatter::uniorcam($line);
            return $return;
        });

        $dataFrame->applyOnCols('nome', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        return $dataFrame;
    }

}
