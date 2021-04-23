<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class CtaDispParser extends ParserAbstract {

    protected array $colSizes = [20,2,2,4,5,5,20,1,1,4];
    protected array $colNames = [
        'conta_contabil',
        'orgao',
        'uniorcam',
        'recurso_vinculado',
        'banco',
        'agencia',
        'conta_corrente',
        'tipo_conta_corrente',
        'classificacao_disponivel',
        'complemento_recurso_vinculado'
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
        
        $dataFrame->applyOnCols('banco', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('agencia', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('conta_corrente', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        return $dataFrame;
    }

}
