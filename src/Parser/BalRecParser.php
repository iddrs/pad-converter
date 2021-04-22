<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class BalRecParser extends ParserAbstract {

    protected array $colSizes = [20, 4, 13, 13, 4, 170, 1, 2, 3, 13, 4];
    protected array $colNames = [
        'codigo_receita',
        'uniorcam',
        'receita_orcada',
        'receita_realizada',
        'recurso_vinculado',
        'especificacao_receita',
        'tipo_nivel',
        'numero_nivel',
        'caracteristica_peculiar_receita',
        'previsao_atualizada',
        'complemento_recurso_vinculado'
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

        $dataFrame->applyOnCols('receita_orcada', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('receita_realizada', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('previsao_atualizada', function ($cell) {
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
