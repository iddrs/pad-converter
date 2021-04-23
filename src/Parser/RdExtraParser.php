<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class RdExtraParser extends ParserAbstract {

    protected array $colSizes = [20,2,2,13,1,2,4];
    protected array $colNames = [
        'conta_contabil',
        'orgao',
        'uniorcam',
        'valor_movimentacao',
        'ingresso_dispendio',
        'classificacao',
        'recurso_vinculado'
    ];

    public function __construct() {
        if (sizeof($this->colNames) !== sizeof($this->colSizes)) {
            throw new WarningException("Número de colunas diferente do número de especificação para $fileId");
        }
    }

    protected function transform(DataFrame $dataFrame): DataFrame {
        $dataFrame->applyOnCols('conta_contabil', function ($cell) {
            $return = CodigosFormatter::contaContabil($cell);
            return $return;
        });
        
        $dataFrame->applyOnLines(function($line){
            $return = CodigosFormatter::uniorcam($line);
            return $return;
        });
        
        $dataFrame->applyOnCols('valor_movimentacao', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        return $dataFrame;
    }

}
