<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class RubricaParser extends ParserAbstract {

    protected array $colSizes = [4, 15, 110, 1, 2];
    protected array $colNames = [
        'exercicio',
        'rubrica',
        'especificacao',
        'tipo_nivel',
        'numero_nivel'
    ];

    public function __construct() {
        if (sizeof($this->colNames) !== sizeof($this->colSizes)) {
            throw new WarningException("Número de colunas diferente do número de especificação para $fileId");
        }
    }

    protected function transform(DataFrame $dataFrame): DataFrame {
        $dataFrame->applyOnCols('rubrica', function ($cell): string {
            $return = CodigosFormatter::despesaDesdobramento($cell);
            return $return;
        });

        $dataFrame->applyOnCols('especificacao', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });

        return $dataFrame;
    }

}
