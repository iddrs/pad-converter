<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use PTK\DataFrame\DataFrame;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;

class BrubAntParser extends ParserAbstract {

    protected array $colSizes = [2, 2, 2, 3, 4, 3, 5, 15, 4, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 11, 4, 4, 4];
    protected array $colNames = [
        'orgao',
        'uniorcam',
        'funcao',
        'subfuncao',
        'programa',
        'obsoleto1',
        'projativ',
        'rubrica',
        'recurso_vinculado',
        'empenhado_1bim',
        'empenhado_2bim',
        'empenhado_3bim',
        'empenhado_4bim',
        'empenhado_5bim',
        'empenhado_6bim',
        'liquidado_1bim',
        'liquidado_2bim',
        'liquidado_3bim',
        'liquidado_4bim',
        'liquidado_5bim',
        'liquidado_6bim',
        'pago_1bim',
        'pago_2bim',
        'pago_3bim',
        'pago_4bim',
        'pago_5bim',
        'pago_6bim',
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
        $dataFrame->applyOnLines(function ($line) {
            $return = CodigosFormatter::uniorcam($line);
            return $return;
        });

        $dataFrame->applyOnCols('rubrica', function ($cell): string {
            $return = CodigosFormatter::despesaDesdobramento($cell);
            return $return;
        });

        $dataFrame->applyOnCols('empenhado_1bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('empenhado_2bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('empenhado_3bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('empenhado_4bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('empenhado_5bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('empenhado_6bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('liquidado_1bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('liquidado_2bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('liquidado_3bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('liquidado_4bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('liquidado_5bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('liquidado_6bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('pago_1bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('pago_2bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('pago_3bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('pago_4bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('pago_5bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('pago_6bim', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        return $dataFrame;
    }

}
