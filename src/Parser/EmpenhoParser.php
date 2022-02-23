<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use PTK\DataFrame\DataFrame;
use IDDRS\SIAPC\PAD\Converter\Parser\ParserAbstract;
use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Formatter\CodigosFormatter;
use IDDRS\SIAPC\PAD\Converter\Formatter\ValoresFormatter;

class EmpenhoParser extends ParserAbstract {

    protected array $colSizes = [2, 2, 2, 3, 4, 3, 5, 15, 4, 4, 13, 8, 13, 1, 10, 165, 3, 2, 1, 20, 20, 4, 400, 3, 2, 1, 1, 14, 4, 4, 4];
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
        'contrapartida_recurso_vinculado',
        'numero_empenho',
        'data_empenho',
        'valor_empenho',
        'sinal_valor',
        'credor',
        'obsoleto2',
        'caracteristica_peculiar_despesa',
        'obsoleto3',
        'registro_precos',
        'obsoleto4',
        'numero_licitacao',
        'ano_licitacao',
        'historico_empenho',
        'forma_contratacao',
        'base_legal',
        'despesa_funcionario',
        'licitacao_compartilhada',
        'cnpj_gerenciador_licitacao_compartilhada',
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
        
        $dataFrame->applyOnCols('data_empenho', function ($cell): string {
            $return = ValoresFormatter::dataStrToStr($cell);
            return $return;
        });

        $dataFrame->applyOnLines(function ($line) {
            $return = ValoresFormatter::valorComSinal('valor_empenho', 'sinal_valor', $line);
            return $return;
        });

        $dataFrame->applyOnCols('historico_empenho', function ($cell) {
            $return = ValoresFormatter::trim($cell);
            return $return;
        });
        
        $dataFrame->applyOnCols('cnpj_gerenciador_licitacao_compartilhada', function ($cell) {
            $return = CodigosFormatter::cnpj($cell);
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
