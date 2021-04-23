<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class DecretoParser extends ParserAbstract {

    protected array $colSizes = [20, 8, 20, 8, 13, 13, 1, 1, 1, 13, 8, 13];
    protected array $colNames = [
        'numero_lei',
        'data_lei',
        'numero_decreto',
        'data_decreto',
        'valor_credito_adicional',
        'valor_reducao_dotacoes',
        'tipo_credito_adicional',
        'origem_recurso',
        'alteracoes_orcamentarias',
        'valor_alteracoes',
        'data_reabertura_credito_adicional',
        'valor_saldo_reaberto'
    ];

    public function __construct() {
        if (sizeof($this->colNames) !== sizeof($this->colSizes)) {
            throw new WarningException("Número de colunas diferente do número de especificação para $fileId");
        }
    }

    protected function transform(DataFrame $dataFrame): DataFrame {

        $dataFrame->applyOnCols('numero_lei', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('data_lei', function ($cell): ?string {
            $return = ValoresFormatter::dataStrToStr($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('numero_decreto', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('data_decreto', function ($cell): ?string {
            $return = ValoresFormatter::dataStrToStr($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('valor_credito_adicional', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        $dataFrame->applyOnCols('valor_reducao_dotacoes', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('valor_alteracoes', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('data_reabertura_credito_adicional', function ($cell): ?string {
            $return = ValoresFormatter::dataStrToStr($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('valor_saldo_reaberto', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        return $dataFrame;
    }

}
