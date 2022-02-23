<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use PTK\DataFrame\DataFrame;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;

class BrecAntParser extends ParserAbstract {

    protected array $colSizes = [20, 2, 2, 13, 13, 4, 170, 1, 2, 3, 4, 4, 4];
    protected array $colNames = [
        'codigo_receita',
        'orgao',
        'uniorcam',
        'receita_orcada',
        'receita_realizada',
        'recurso_vinculado',
        'especificacao_receita',
        'tipo_nivel',
        'numero_nivel',
        'caracteristica_peculiar_receita',
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

        $dataFrame->applyOnCols('receita_orcada', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('receita_realizada', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });

        $dataFrame->applyOnCols('especificacao_receita', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });

        return $dataFrame;
    }

}
