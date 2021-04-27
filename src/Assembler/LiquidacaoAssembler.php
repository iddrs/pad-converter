<?php

namespace IDDRS\SIAPC\PAD\Converter\Assembler;

use PTK\DataFrame\DataFrame;
use PTK\DataFrame\Reader\ArrayReader;

/**
 * Inclui os dados de empenho em cada entrada de liquidac.txt
 *
 * @author Everton
 */
class LiquidacaoAssembler implements AssemblerInterface {

    protected DataFrame $liquidac;
    protected DataFrame $empenho;

    public function __construct(DataFrame $liquidac, DataFrame $empenho) {
        $this->liquidac = $liquidac;
        $this->empenho = $empenho;
    }

    public function assemble(): DataFrame {
        $data = [];
        foreach ($this->liquidac as $indexLiquidac => $dataLiquidac) {

            $dataLiquidac['orgao'] = null;
            $dataLiquidac['uniorcam'] = null;
            $dataLiquidac['funcao'] = null;
            $dataLiquidac['subfuncao'] = null;
            $dataLiquidac['programa'] = null;
            $dataLiquidac['projativ'] = null;
            $dataLiquidac['rubrica'] = null;
            $dataLiquidac['recurso_vinculado'] = null;
            $dataLiquidac['contrapartida_recurso_vinculado'] = null;
            $dataLiquidac['credor'] = null;
            $dataLiquidac['caracteristica_peculiar_despesa'] = null;
            $dataLiquidac['registro_precos'] = null;
            $dataLiquidac['numero_licitacao'] = null;
            $dataLiquidac['ano_licitacao'] = null;
            $dataLiquidac['historico_empenho'] = null;
            $dataLiquidac['forma_contratacao'] = null;
            $dataLiquidac['base_legal'] = null;
            $dataLiquidac['despesa_funcionario'] = null;
            $dataLiquidac['licitacao_compartilhada'] = null;
            $dataLiquidac['cnpj_gerenciador_licitacao_compartilhada'] = null;
            $dataLiquidac['complemento_recurso_vinculado'] = null;

            foreach ($this->empenho as $indexEmpenho => $dataEmpenho) {

                if ($dataEmpenho['numero_empenho'] === $dataLiquidac['numero_empenho']) {

                    $dataLiquidac['orgao'] = $dataEmpenho['orgao'];
                    $dataLiquidac['uniorcam'] = $dataEmpenho['uniorcam'];
                    $dataLiquidac['funcao'] = $dataEmpenho['funcao'];
                    $dataLiquidac['subfuncao'] = $dataEmpenho['subfuncao'];
                    $dataLiquidac['programa'] = $dataEmpenho['programa'];
                    $dataLiquidac['projativ'] = $dataEmpenho['projativ'];
                    $dataLiquidac['rubrica'] = $dataEmpenho['rubrica'];
                    $dataLiquidac['recurso_vinculado'] = $dataEmpenho['recurso_vinculado'];
                    $dataLiquidac['contrapartida_recurso_vinculado'] = $dataEmpenho['contrapartida_recurso_vinculado'];
                    $dataLiquidac['credor'] = $dataEmpenho['credor'];
                    $dataLiquidac['caracteristica_peculiar_despesa'] = $dataEmpenho['caracteristica_peculiar_despesa'];
                    $dataLiquidac['registro_precos'] = $dataEmpenho['registro_precos'];
                    $dataLiquidac['numero_licitacao'] = $dataEmpenho['numero_licitacao'];
                    $dataLiquidac['ano_licitacao'] = $dataEmpenho['ano_licitacao'];
                    $dataLiquidac['historico_empenho'] = $dataEmpenho['historico_empenho'];
                    $dataLiquidac['forma_contratacao'] = $dataEmpenho['forma_contratacao'];
                    $dataLiquidac['base_legal'] = $dataEmpenho['base_legal'];
                    $dataLiquidac['despesa_funcionario'] = $dataEmpenho['despesa_funcionario'];
                    $dataLiquidac['licitacao_compartilhada'] = $dataEmpenho['licitacao_compartilhada'];
                    $dataLiquidac['cnpj_gerenciador_licitacao_compartilhada'] = $dataEmpenho['cnpj_gerenciador_licitacao_compartilhada'];
                    $dataLiquidac['complemento_recurso_vinculado'] = $dataEmpenho['complemento_recurso_vinculado'];
                    break;
                }
            }
            $data[] = $dataLiquidac;
        }
//        print_r($data);
        return new DataFrame(new ArrayReader($data));
    }

}
