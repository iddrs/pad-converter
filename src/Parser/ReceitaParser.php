<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use PTK\DataFrame\DataFrame;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;

class ReceitaParser extends ParserAbstract {

    protected array $colSizes = [20, 2, 2, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 12, 12, 12, 12, 12, 12, 3, 4, 4, 4, 4];
    protected array $colNames = [
        'codigo_receita',
        'orgao',
        'uniorcam',
        'receita_realizada_jan',
        'receita_realizada_fev',
        'receita_realizada_mar',
        'receita_realizada_abr',
        'receita_realizada_mai',
        'receita_realizada_jun',
        'receita_realizada_jul',
        'receita_realizada_ago',
        'receita_realizada_set',
        'receita_realizada_out',
        'receita_realizada_nov',
        'receita_realizada_dez',
        'meta_1bim',
        'meta_2bim',
        'meta_3bim',
        'meta_4bim',
        'meta_5bim',
        'meta_6bim',
        'caracteristica_peculiar_receita',
        'recurso_vinculado',
        'complemento_recurso_vinculado',
        'fonte_recurso_stn',
        'acompanhamento_execucao_orcamentaria'
    ];

    public function __construct() {
        if (sizeof($this->colNames) !== sizeof($this->colSizes)) {
            throw new WarningException("Número de colunas diferente do número de especificação para $fileId");
        }
    }

    protected function transform(DataFrame $dataFrame): DataFrame {
        $dataFrame->applyOnCols('codigo_receita', function ($cell): string {
            $return = CodigosFormatter::naturezaReceita($cell);
            return $return;
        });
        
        $dataFrame->applyOnLines(function($line){
            $return = CodigosFormatter::uniorcam($line);
            return $return;
        });

        $dataFrame->applyOnCols('receita_realizada_jan', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('receita_realizada_fev', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('receita_realizada_mar', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('receita_realizada_abr', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('receita_realizada_mai', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('receita_realizada_jun', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('receita_realizada_jul', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('receita_realizada_ago', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('receita_realizada_set', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('receita_realizada_out', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('receita_realizada_nov', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('receita_realizada_dez', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('meta_1bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('meta_2bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('meta_3bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('meta_4bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('meta_5bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('meta_6bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        return $dataFrame;
    }

}
