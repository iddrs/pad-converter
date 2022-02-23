<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use PTK\DataFrame\DataFrame;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;

class BalDespParser extends ParserAbstract {

    protected array $colSizes = [2, 2, 2, 3, 4, 3, 5, 6, 4, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 13, 4, 13, 13, 13, 4, 4];
    protected array $colNames = [
        'orgao',
        'uniorcam',
        'funcao',
        'subfuncao',
        'programa',
        'obsoleto1',
        'projativ',
        'elemento',
        'recurso_vinculado',
        'dotacao_inicial',
        'atualizacao_monetaria',
        'creditos_suplementares',
        'creditos_especiais',
        'creditos_extraordinarios',
        'reducao_dotacao',
        'suplementacao_recurso_vinculado',
        'reducao_recurso_vinculado',
        'valor_empenhado',
        'valor_liquidado',
        'valor_pago',
        'valor_limitado_lrf',
        'valor_recomposto_lrf',
        'previsao_realizacao_lrf',
        'complemento_recurso_vinculado',
        'transferencia',
        'transposicao',
        'remanejamento',
        'fonte_recurso_stn',
        'acompanhamento_execucao_orcamentaria'
    ];

    public function __construct() {
        if (sizeof($this->colNames) !== sizeof($this->colSizes)) {
            throw new WarningException("Número de colunas diferente do número de especificação para $fileId");
        }
    }

    protected function transform(DataFrame $dataFrame): DataFrame {
        $dataFrame->applyOnLines(function ($line) {
            $return = CodigosFormatter::uniorcam($line);
            return $return;
        });

        $dataFrame->applyOnCols('elemento', function ($cell): string {
            $return = CodigosFormatter::despesaElemento($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('dotacao_inicial', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('atualizacao_monetaria', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('creditos_suplementares', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('creditos_especiais', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('creditos_extraordinarios', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('reducao_dotacao', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('suplementacao_recurso_vinculado', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('reducao_recurso_vinculado', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('valor_empenhado', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('valor_liquidado', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('valor_pago', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('valor_limitado_lrf', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('valor_recomposto_lrf', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('previsao_realizacao_lrf', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('transferencia', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('transposicao', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('remanejamento', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        return $dataFrame;
    }

}
