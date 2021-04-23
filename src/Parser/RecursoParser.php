<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class RecursoParser extends ParserAbstract {

    protected array $colSizes = [4, 80, 160];
    protected array $colNames = [
        'recurso_vinculado',
        'nome',
        'finalidade'
    ];

    public function __construct() {
        if (sizeof($this->colNames) !== sizeof($this->colSizes)) {
            throw new WarningException("Número de colunas diferente do número de especificação para $fileId");
        }
    }

    protected function transform(DataFrame $dataFrame): DataFrame {
        $dataFrame->applyOnCols('nome', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('finalidade', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });

        return $dataFrame;
    }

}
