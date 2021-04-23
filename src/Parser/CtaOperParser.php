<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class CtaOperParser extends ParserAbstract {

    protected array $colSizes = [30, 8, 13, 1, 2, 2, 20, 2, 2, 20, 4, 4];
    protected array $colNames = [
        'codigo_operacao',
        'data_operacao',
        'valor_operacao',
        'sinal_valor',
        'recurso_vinculado',
        'codigo_receita',
        'orgao_receita',
        'uniorcam_receita',
        'conta_contabil',
        'orgao_contabil',
        'uniorcam_contabil',
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

        $dataFrame->applyOnLines(function ($line) {
            $return = CodigosFormatter::uniorcam($line, 'orgao_receita', 'uniorcam_receita');
            return $return;
        });

        $dataFrame->applyOnLines(function ($line) {
            $return = CodigosFormatter::uniorcam($line, 'orgao_contabil', 'uniorcam_contabil');
            return $return;
        });

        $dataFrame->applyOnCols('data_operacao', function ($cell): string {
            $return = ValoresFormatter::dataStrToStr($cell);
            return $return;
        });

        $dataFrame->applyOnLines(function ($line) {
            $return = ValoresFormatter::valorComSinal('valor_operacao', 'sinal_valor', $line);
            return $return;
        });
        return $dataFrame;
    }

}
