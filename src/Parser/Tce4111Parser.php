<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class Tce4111Parser extends ParserAbstract {

    protected array $colSizes = [20, 2, 2, 4, 12, 12, 13, 8, 17, 1, 12, 150, 1, 1, 1, 4, 4];
    protected array $colNames = [
        'conta_contabil',
        'orgao',
        'uniorcam',
        'reservado1',
        'numero_lancamento',
        'numero_lote',
        'numero_documento',
        'data_lancamento',
        'valor',
        'tipo_lancamento',
        'numero_arquivamento',
        'historico',
        'tipo_documento',
        'natureza_informacao',
        'indicador_superavit_financeiro',
        'recurso_vinculado',
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
        
        $dataFrame->applyOnCols('data_lancamento', function ($cell): string {
            $return = ValoresFormatter::dataStrToStr($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('valor', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('historico', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        return $dataFrame;
    }

}
