<?php

namespace IDDRS\SIAPC\PAD\Converter\Assembler;

use DateTime;
use PTK\DataFrame\DataFrame;
use PTK\DataFrame\Reader\ArrayReader;

/**
 * Monta um data frame com os empenhos de restos a pagar
 *
 * @author Everton
 */
class RestosAPagarAssembler implements AssemblerInterface {
    
    protected array $empenho;
    protected array $liquidac;
    protected array $pagament;
    
    protected array $data = [];
    protected int $anoAtual = 0;


    public function __construct(DataFrame $empenho, DataFrame $liquidac, DataFrame $pagament) {
        $this->empenho = $empenho->getAsArray();
        $this->liquidac = $liquidac->getAsArray();
        $this->pagament = $pagament->getAsArray();
        
        $this->prepara();
    }
    
    protected function prepara(): void {
        $this->empenho = $this->preparaValor($this->empenho, 'valor_empenho');
        $this->empenho = $this->preparaData($this->empenho, 'data_empenho', 'ano_movimento');
        
        $this->liquidac = $this->preparaValor($this->liquidac, 'valor_liquidacao');
        $this->liquidac = $this->preparaData($this->liquidac, 'data_liquidacao', 'ano_movimento');
        
        $this->pagament = $this->preparaValor($this->pagament, 'valor_pagamento');
        $this->pagament = $this->preparaData($this->pagament, 'data_pagamento', 'ano_movimento');
        
        $this->detectaAnoAtual();
        
        $this->selecionaEmpenhosUnicos();
        
    }
    
    protected function detectaAnoAtual(): void {
        $dataFinal = $this->empenho[0]['data_final'];
        $date = new DateTime();
        $date->createFromFormat('Y-m-d', $dataFinal);
        $this->anoAtual = (int) $date->format('Y');
    }
    
    protected function preparaData(array $data, string $field, string $newField): array {
        foreach ($data as $index => $line){
            $date = date_create_from_format('Y-m-d', $line[$field]);
            $ano = $date->format('Y');
            $data[$index][$newField] = $ano;
        }
        return $data;
    }
    
    protected function preparaValor(array $data, string $field): array {
        foreach ($data as $index => $line){
            $valor = $line[$field];
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
            
            $data[$index][$field] = (float) round($valor, 2);
        };
        return $data;
    }
    
    protected function selecionaEmpenhosUnicos(): void {
        $unicos = [];
        foreach ($this->empenho as $line){
            if($line['ano_empenho'] == $this->anoAtual){
                continue;
            }
            /////////////
//            if($line['numero_empenho'] !== '0201900004451'){//não processado
//            if($line['numero_empenho'] !== '0201900001416'){//processado
//            if($line['numero_empenho'] !== '0202000000068'){
//                continue;
//            }
            /////////////
            $id = $line['numero_empenho'];
            if(!key_exists($id, $unicos)){
                $unicos[$id]['orgao'] = $line['orgao'];
                $unicos[$id]['uniorcam'] = $line['uniorcam'];
                $unicos[$id]['funcao'] = $line['funcao'];
                $unicos[$id]['subfuncao'] = $line['subfuncao'];
                $unicos[$id]['programa'] = $line['programa'];
                $unicos[$id]['projativ'] = $line['projativ'];
                $unicos[$id]['rubrica'] = $line['rubrica'];
                $unicos[$id]['recurso_vinculado'] = $line['recurso_vinculado'];
                $unicos[$id]['contrapartida_recurso_vinculado'] = $line['contrapartida_recurso_vinculado'];
                $unicos[$id]['numero_empenho'] = $line['numero_empenho'];
                $unicos[$id]['data_empenho'] = $line['data_empenho'];
                $unicos[$id]['valor_empenho'] = $line['valor_empenho'];
                $unicos[$id]['credor'] = $line['credor'];
                $unicos[$id]['caracteristica_peculiar_despesa'] = $line['caracteristica_peculiar_despesa'];
                $unicos[$id]['registro_precos'] = $line['registro_precos'];
                $unicos[$id]['numero_licitacao'] = $line['numero_licitacao'];
                $unicos[$id]['ano_licitacao'] = $line['ano_licitacao'];
                $unicos[$id]['historico_empenho'] = $line['historico_empenho'];
                $unicos[$id]['forma_contratacao'] = $line['forma_contratacao'];
                $unicos[$id]['base_legal'] = $line['base_legal'];
                $unicos[$id]['despesa_funcionario'] = $line['despesa_funcionario'];
                $unicos[$id]['licitacao_compartilhada'] = $line['licitacao_compartilhada'];
                $unicos[$id]['cnpj_gerenciador_licitacao_compartilhada'] = $line['cnpj_gerenciador_licitacao_compartilhada'];
                $unicos[$id]['complemento_recurso_vinculado'] = $line['complemento_recurso_vinculado'];
                $unicos[$id]['ano_empenho'] = $line['ano_empenho'];
                $unicos[$id]['data_inicial'] = $line['data_inicial'];
                $unicos[$id]['data_final'] = $line['data_final'];
                $unicos[$id]['data_geracao'] = $line['data_geracao'];
                $unicos[$id]['cnpj'] = $line['cnpj'];
                $unicos[$id]['entidade'] = $line['entidade'];
                $unicos[$id]['arquivo'] = $line['arquivo'];
            }
        }
        $this->data = $unicos;
    }
    
    protected function calculaLiquidacaoRPNP(): void {
        foreach ($this->data as $index => $empenho){
            if($empenho['saldo_inicial_nao_processados'] == 0){
                $this->data[$index]['nao_processados_liquidados'] = 0.0;
                continue;
            }
            
            $liquidacaoRPNP = 0;
            foreach($this->liquidac as $line){
                if(
                        $line['numero_empenho'] === $empenho['numero_empenho']
                        && $line['ano_movimento'] == $this->anoAtual
                ){
                    $liquidacaoRPNP += $line['valor_liquidacao'];
                }
            };
            
            $this->data[$index]['nao_processados_liquidados'] = (float) $liquidacaoRPNP;
        }
    }
    
    protected function calculaSaldoInicialRP(): void {
        foreach ($this->data as $index => $empenho){
            $saldoInicialRPNP = 0;
            $saldoInicialRPP = 0;
            
            $valorEmpenhado = 0;
            foreach($this->empenho as $line){
                if(
                        $line['numero_empenho'] === $empenho['numero_empenho']
                        && $line['ano_empenho'] < $this->anoAtual
                        && $line['ano_movimento'] < $this->anoAtual
                ){
                    $valorEmpenhado += $line['valor_empenho'];
                }
            };
            
            $valorLiquidado = 0;
            foreach($this->liquidac as $line){
                if(
                        $line['numero_empenho'] === $empenho['numero_empenho']
                        && $line['ano_movimento'] < $this->anoAtual
                ){
                    $valorLiquidado += $line['valor_liquidacao'];
                }
            };
            
            $valorPagamento = 0;
            foreach($this->pagament as $line){
                if(
                        $line['numero_empenho'] === $empenho['numero_empenho']
                        && $line['ano_movimento'] < $this->anoAtual
                ){
                    $valorPagamento += $line['valor_pagamento'];
                }
            };

            $saldoInicialRPNP = round($valorEmpenhado, 2) - round($valorLiquidado, 2);
            $saldoInicialRPP = round($valorLiquidado, 2) - round($valorPagamento, 2);
            
            $this->data[$index]['saldo_inicial_nao_processados'] = (float) $saldoInicialRPNP;
            $this->data[$index]['saldo_inicial_processados'] = (float) $saldoInicialRPP;
//            echo $index, ' -> ', $valorEmpenhado, ' -> ', $valorLiquidado, ' -> ', $valorPagamento, ' -> ', $saldoInicialRPNP, ' -> ', $saldoInicialRPP, PHP_EOL;
        }
    }
    
    protected function calculaPagamentoRPNP(): void {
        foreach ($this->data as $index => $empenho){
            if($empenho['saldo_inicial_nao_processados'] == 0){
                $this->data[$index]['nao_processados_pagos'] = 0.0;
                continue;
            }
            
            $pagamentoRPNP = 0;
            foreach($this->pagament as $line){
                if(
                        $line['numero_empenho'] === $empenho['numero_empenho']
                        && $line['ano_movimento'] == $this->anoAtual
                ){
                    $pagamentoRPNP += $line['valor_pagamento'];
                }
            };
            
            $this->data[$index]['nao_processados_pagos'] = (float) $pagamentoRPNP;
        }
    }
    
    protected function calculaPagamentoRPP(): void {
        foreach ($this->data as $index => $empenho){
            if($empenho['saldo_inicial_processados'] == 0){
                $this->data[$index]['processados_pagos'] = 0.0;
                continue;
            }
            
            $pagamentoRPP = 0;
            foreach($this->pagament as $line){
                if(
                        $line['numero_empenho'] === $empenho['numero_empenho']
                        && $line['ano_movimento'] == $this->anoAtual
                ){
                    $pagamentoRPP += $line['valor_pagamento'];
                }
            };
            
            $this->data[$index]['processados_pagos'] = (float) $pagamentoRPP;
        }
    }
    
    protected function calculaCancelamentoRPNP(): void {
        foreach ($this->data as $index => $empenho){
            if($empenho['saldo_inicial_nao_processados'] == 0){
                $this->data[$index]['nao_processados_cancelados'] = 0.0;
                continue;
            }
            
            $cancelamentoRPNP = 0;
            foreach($this->empenho as $line){
                if(
                        $line['numero_empenho'] === $empenho['numero_empenho']
                        && $line['ano_movimento'] == $this->anoAtual
                        && $line['sinal_valor'] === '-'
                ){
                    $cancelamentoRPNP += $line['valor_empenho'];
                }
            };
            
            $this->data[$index]['nao_processados_cancelados'] = (float) $cancelamentoRPNP * -1;
        }
    }
    
    protected function calculaCancelamentoRPP(): void {
        foreach ($this->data as $index => $empenho){
            if($empenho['saldo_inicial_processados'] == 0){
                $this->data[$index]['processados_cancelados'] = 0.0;
                continue;
            }
            
            $cancelamentoRPP = 0;
            foreach($this->empenho as $line){
                if(
                        $line['numero_empenho'] === $empenho['numero_empenho']
                        && $line['ano_movimento'] == $this->anoAtual
                        && $line['sinal_valor'] === '-'
                ){
                    $cancelamentoRPP += $line['valor_empenho'];
                }
            };
            
            $this->data[$index]['processados_cancelados'] = (float) $cancelamentoRPP * -1;
        }
    }
    
    protected function calculaSaldoFinalRP(): void {
        foreach ($this->data as $index => $empenho){
            $saldoRPNP = round($empenho['saldo_inicial_nao_processados'], 2) - round($empenho['nao_processados_pagos'], 2) - round($empenho['nao_processados_cancelados'], 2);
            $saldoRPP = round($empenho['saldo_inicial_processados'], 2) - round($empenho['processados_pagos'], 2) - round($empenho['processados_cancelados'], 2);
            
            $this->data[$index]['saldo_final_nao_processados'] = (float) $saldoRPNP;
            $this->data[$index]['saldo_final_processados'] = (float) $saldoRPP;
        }
        
    }
    
    public function assemble(): DataFrame {
        $this->calculaSaldoInicialRP();
        $this->calculaLiquidacaoRPNP();
        $this->calculaPagamentoRPNP();
        $this->calculaPagamentoRPP();
        $this->calculaCancelamentoRPNP();
        $this->calculaCancelamentoRPP();
        $this->calculaSaldoFinalRP();
        
        
        $reader = new ArrayReader($this->data);
        $rpDF = new DataFrame($reader);
//        $rpDF = $this->decimalSeparatorChoice($rpDF);
//        print_r($rpDF->getColTypes());
        return $rpDF;
    }
    
//    protected function decimalSeparatorChoice(DataFrame $dataFrame): DataFrame {
//        $colTypes = $dataFrame->getColTypes();
//        
//        foreach($colTypes as $colName => $type){
//            switch($type){
//                case 'double':
//                case 'float':
//                $dataFrame->applyOnCols($colName, function($cell){
//                    return number_format($cell, 2, ',', '.');
//                });
//                break;
//            }
//        }
//        
//        return $dataFrame;
//    }

}
