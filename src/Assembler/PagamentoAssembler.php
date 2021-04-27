<?php

namespace IDDRS\SIAPC\PAD\Converter\Assembler;

use PTK\DataFrame\DataFrame;
use PTK\DataFrame\Reader\ArrayReader;

/**
 * Inclui os dados de empenho em cada entrada de pagament.txt
 *
 * @author Everton
 */
class PagamentoAssembler implements AssemblerInterface {

    protected DataFrame $pagament;
    protected DataFrame $empenho;

    public function __construct(DataFrame $pagament, DataFrame $empenho) {
        $this->pagament = $pagament;
        $this->empenho = $empenho;
    }

    public function assemble(): DataFrame {
        $data = [];
        foreach ($this->pagament as $indexPagament => $dataPagament) {

            $dataPagament['orgao'] = null;
            $dataPagament['uniorcam'] = null;
            $dataPagament['funcao'] = null;
            $dataPagament['subfuncao'] = null;
            $dataPagament['programa'] = null;
            $dataPagament['projativ'] = null;
            $dataPagament['rubrica'] = null;
            $dataPagament['recurso_vinculado'] = null;
            $dataPagament['contrapartida_recurso_vinculado'] = null;
            $dataPagament['credor'] = null;
            $dataPagament['caracteristica_peculiar_despesa'] = null;
            $dataPagament['registro_precos'] = null;
            $dataPagament['numero_licitacao'] = null;
            $dataPagament['ano_licitacao'] = null;
            $dataPagament['historico_empenho'] = null;
            $dataPagament['forma_contratacao'] = null;
            $dataPagament['base_legal'] = null;
            $dataPagament['despesa_funcionario'] = null;
            $dataPagament['licitacao_compartilhada'] = null;
            $dataPagament['cnpj_gerenciador_licitacao_compartilhada'] = null;
            $dataPagament['complemento_recurso_vinculado'] = null;

            foreach ($this->empenho as $indexEmpenho => $dataEmpenho) {

                if ($dataEmpenho['numero_empenho'] === $dataPagament['numero_empenho']) {

                    $dataPagament['orgao'] = $dataEmpenho['orgao'];
                    $dataPagament['uniorcam'] = $dataEmpenho['uniorcam'];
                    $dataPagament['funcao'] = $dataEmpenho['funcao'];
                    $dataPagament['subfuncao'] = $dataEmpenho['subfuncao'];
                    $dataPagament['programa'] = $dataEmpenho['programa'];
                    $dataPagament['projativ'] = $dataEmpenho['projativ'];
                    $dataPagament['rubrica'] = $dataEmpenho['rubrica'];
                    $dataPagament['recurso_vinculado'] = $dataEmpenho['recurso_vinculado'];
                    $dataPagament['contrapartida_recurso_vinculado'] = $dataEmpenho['contrapartida_recurso_vinculado'];
                    $dataPagament['credor'] = $dataEmpenho['credor'];
                    $dataPagament['caracteristica_peculiar_despesa'] = $dataEmpenho['caracteristica_peculiar_despesa'];
                    $dataPagament['registro_precos'] = $dataEmpenho['registro_precos'];
                    $dataPagament['numero_licitacao'] = $dataEmpenho['numero_licitacao'];
                    $dataPagament['ano_licitacao'] = $dataEmpenho['ano_licitacao'];
                    $dataPagament['historico_empenho'] = $dataEmpenho['historico_empenho'];
                    $dataPagament['forma_contratacao'] = $dataEmpenho['forma_contratacao'];
                    $dataPagament['base_legal'] = $dataEmpenho['base_legal'];
                    $dataPagament['despesa_funcionario'] = $dataEmpenho['despesa_funcionario'];
                    $dataPagament['licitacao_compartilhada'] = $dataEmpenho['licitacao_compartilhada'];
                    $dataPagament['cnpj_gerenciador_licitacao_compartilhada'] = $dataEmpenho['cnpj_gerenciador_licitacao_compartilhada'];
                    $dataPagament['complemento_recurso_vinculado'] = $dataEmpenho['complemento_recurso_vinculado'];
                    break;
                }
            }
            $data[] = $dataPagament;
        }
//        print_r($data);
        return new DataFrame(new ArrayReader($data));
    }

}
