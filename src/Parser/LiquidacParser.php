<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class LiquidacParser extends ParserAbstract {

    protected array $colSizes = [13,20,8,13,1,165,30,400,1,20,20,4,1,9,3,1];
    protected array $colNames = [
        'numero_empenho',
        'numero_liquidacao',
        'data_liquidacao',
        'valor_liquidacao',
        'sinal_valor',
        'obsoleto1',
        'codigo_operacao',
        'historico_liquidacao',
        'existe_contrato',
        'numero_contrato_tce',
        'numero_contrato',
        'ano_contrato',
        'existe_nota_fiscal',
        'numero_nota_fiscal',
        'serie_nota_fiscal',
        'tipo_contrato'
    ];

    public function __construct() {
        if (sizeof($this->colNames) !== sizeof($this->colSizes)) {
            throw new WarningException("Número de colunas diferente do número de especificação para $fileId");
        }
    }

    protected function transform(DataFrame $dataFrame): DataFrame {
        
        $dataFrame->applyOnCols('data_liquidacao', function ($cell): string {
            $return = ValoresFormatter::dataStrToStr($cell);
            return $return;
        });

        $dataFrame->applyOnLines(function ($line) {
            $return = ValoresFormatter::valorComSinal('valor_liquidacao', 'sinal_valor', $line);
            return $return;
        });

        $dataFrame->applyOnCols('historico_liquidacao', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        $anoEmpenho = [];
        $data = $dataFrame->getAsArray();
        foreach ($data as $index => $line){
            $anoEmpenho[$index] = (int) substr($line['numero_empenho'], 0, 5);
        }
        $dataFrame->appendCol('ano_empenho', $anoEmpenho);
        
        return $dataFrame;
    }

}
