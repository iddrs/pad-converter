<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use PTK\DataFrame\DataFrame;

class CredorParser extends ParserAbstract {

    protected array $colSizes = [10, 60, 14, 15, 15, 50, 30, 2, 8, 15, 15, 2, 2];
    protected array $colNames = [
        'credor',
        'nome',
        'cnpj_cpf',
        'inscricao_estadual',
        'inscricao_municipal',
        'endereco',
        'cidade',
        'uf',
        'cep',
        'fone',
        'fax',
        'tipo_credor',
        'tipo_pessoa'
    ];

    public function __construct() {
        if (sizeof($this->colNames) !== sizeof($this->colSizes)) {
            throw new WarningException("Número de colunas diferente do número de especificação para $fileId");
        }
    }

    protected function transform(DataFrame $dataFrame): DataFrame {
        $dataFrame->applyOnCols('nome', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });

        $dataFrame->applyOnCols('cidade', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('fone', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('fax', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });

        return $dataFrame;
    }

}
