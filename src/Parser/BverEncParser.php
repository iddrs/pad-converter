<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use PTK\DataFrame\DataFrame;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;

class BverEncParser extends ParserAbstract {

    protected array $colSizes = [20,2,2,13,13,13,13,13,13,148,1,2,1,1,1,1,4,4,4,4];
    protected array $colNames = [
        'conta_contabil',
        'orgao',
        'uniorcam',
        'saldo_anterior_debito',
        'saldo_anterior_credito',
        'movimentacao_debito',
        'movimentacao_credito',
        'saldo_atual_debito',
        'saldo_atual_credito',
        'especificacao',
        'tipo_nivel',
        'numero_nivel',
        'obsoleto1',
        'escrituracao',
        'natureza_informacao',
        'indicador_superavit_financeiro',
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
        $dataFrame->applyOnCols('conta_contabil', function ($cell) {
            $return = CodigosFormatter::contaContabil($cell);
            return $return;
        });
        
        $dataFrame->applyOnLines(function($line){
            $return = CodigosFormatter::uniorcam($line);
            return $return;
        });
        
        $dataFrame->applyOnCols('saldo_anterior_debito', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('saldo_anterior_credito', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('movimentacao_debito', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('movimentacao_credito', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('saldo_atual_debito', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('saldo_atual_credito', function ($cell) {
            $return = ValoresFormatter::valorSemSinal($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('especificacao', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        return $dataFrame;
    }

}
