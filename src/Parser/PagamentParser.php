<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class PagamentParser extends ParserAbstract {

    protected array $colSizes = [13,20,8,13,1,120,30,20,4,20,4,400,20];
    protected array $colNames = [
        'numero_empenho',
        'numero_pagamento',
        'data_pagamento',
        'valor_pagamento',
        'sinal_valor',
        'obsoleto1',
        'codigo_operacao',
        'conta_contabil_debito',
        'uniorcam_debito',
        'conta_contabil_credito',
        'uniorcam_credito',
        'historico_pagamento',
        'numero_liquidacao'
    ];

    public function __construct() {
        if (sizeof($this->colNames) !== sizeof($this->colSizes)) {
            throw new WarningException("Número de colunas diferente do número de especificação para $fileId");
        }
    }

    protected function transform(DataFrame $dataFrame): DataFrame {
        
        $dataFrame->applyOnCols('data_pagamento', function ($cell): string {
            $return = ValoresFormatter::dataStrToStr($cell);
            return $return;
        });

        $dataFrame->applyOnLines(function ($line) {
            $return = ValoresFormatter::valorComSinal('valor_pagamento', 'sinal_valor', $line);
            return $return;
        });

        $dataFrame->applyOnCols('historico_pagamento', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('conta_contabil_debito', function ($cell) {
            $return = CodigosFormatter::contaContabil($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('conta_contabil_credito', function ($cell) {
            $return = CodigosFormatter::contaContabil($cell);
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
