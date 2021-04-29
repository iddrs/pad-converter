<?php

/**
 * Converte so txt para SQLite
 */
require_once 'vendor/autoload.php';
require 'config.php';

use IDDRS\SIAPC\PAD\Converter\Assembler\RestosAPagarAssembler;
use PTK\DataFrame\DataFrame;
use PTK\DataFrame\Reader\PDOReader;
use PTK\DataFrame\Writer\PDOWriter;
use PTK\Log\Formatter\CLImateFormatter;
use PTK\Log\Logger\Logger;
use PTK\Log\Writer\CLImateWriter;

try{
    $defaultWriter = new CLImateWriter();
    $defaultFormatter = new CLImateFormatter();
    $logger = new Logger($defaultWriter, $defaultFormatter);
} catch (Exception $ex) {
    echo $ex->getMessage();
    exit($ex->getCode());
}

/*try {
    if(file_exists($outputSQLite)){
        $logger->info('Apagando conteúdo original...');
        unlink($outputSQLite);
    }
} catch (Exception $ex) {
    $logger->notice($ex->getMessage());
}

try {

    $reader = new InputReader(...$inputDir);
    $writer = new SQLiteWriter($outputSQLite);
    $parserFactory = new ParserFactory();

    $processor = new Processor($reader, $writer, $parserFactory, $logger);
    $processor->convert();
} catch (Exception $ex) {
    $logger->emergency($ex->getMessage());
    exit($ex->getCode());
}

try {
    $logger->info('Gerando arquivo LIQUIDACAO...');
    $pdo = new PDO("sqlite:$outputSQLite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmtEmpenho = $pdo->query("SELECT * FROM EMPENHO");
    $empenho = new DataFrame(new PDOReader($stmtEmpenho));
    
    $stmtLiquidac = $pdo->query("SELECT * FROM LIQUIDAC");
    $liquidac = new DataFrame(new PDOReader($stmtLiquidac));
        
    $liquidacaoAssembler = new LiquidacaoAssembler($liquidac, $empenho);
    $dfLiquidacao = $liquidacaoAssembler->assemble();
    
    PDOWriter::createSQliteTable($dfLiquidacao, $pdo, 'LIQUIDACAO');
    
    $stmtLiquidacao = $pdo->prepare("INSERT INTO LIQUIDACAO (numero_empenho,numero_liquidacao,data_liquidacao,valor_liquidacao,sinal_valor,obsoleto1,codigo_operacao,historico_liquidacao,existe_contrato,numero_contrato_tce,numero_contrato,ano_contrato,existe_nota_fiscal,numero_nota_fiscal,serie_nota_fiscal,tipo_contrato,ano_empenho,data_inicial,data_final,data_geracao,cnpj,entidade,arquivo,orgao,uniorcam,funcao,subfuncao,programa,projativ,rubrica,recurso_vinculado,contrapartida_recurso_vinculado,credor,caracteristica_peculiar_despesa,registro_precos,numero_licitacao,ano_licitacao,historico_empenho,forma_contratacao,base_legal,despesa_funcionario,licitacao_compartilhada,cnpj_gerenciador_licitacao_compartilhada,complemento_recurso_vinculado) VALUES(:numero_empenho,:numero_liquidacao,:data_liquidacao,:valor_liquidacao,:sinal_valor,:obsoleto1,:codigo_operacao,:historico_liquidacao,:existe_contrato,:numero_contrato_tce,:numero_contrato,:ano_contrato,:existe_nota_fiscal,:numero_nota_fiscal,:serie_nota_fiscal,:tipo_contrato,:ano_empenho,:data_inicial,:data_final,:data_geracao,:cnpj,:entidade,:arquivo,:orgao,:uniorcam,:funcao,:subfuncao,:programa,:projativ,:rubrica,:recurso_vinculado,:contrapartida_recurso_vinculado,:credor,:caracteristica_peculiar_despesa,:registro_precos,:numero_licitacao,:ano_licitacao,:historico_empenho,:forma_contratacao,:base_legal,:despesa_funcionario,:licitacao_compartilhada,:cnpj_gerenciador_licitacao_compartilhada,:complemento_recurso_vinculado);");
    $liquidacaoWriter = new PDOWriter($dfLiquidacao, $stmtLiquidacao);
    $liquidacaoWriter->write();

} catch (Exception $ex) {
    $logger->emergency($ex->getMessage());
    exit($ex->getCode());
}

try {
    $logger->info('Gerando arquivo PAGAMENTO...');
    $pdo = new PDO("sqlite:$outputSQLite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmtEmpenho = $pdo->query("SELECT * FROM EMPENHO");
    $empenho = new DataFrame(new PDOReader($stmtEmpenho));
    
    $stmtPagament = $pdo->query("SELECT * FROM PAGAMENT");
    $pagament = new DataFrame(new PDOReader($stmtPagament));
        
    $pagamentoAssembler = new PagamentoAssembler($pagament, $empenho);
    $dfPagamento = $pagamentoAssembler->assemble();
    
    PDOWriter::createSQliteTable($dfPagamento, $pdo, 'PAGAMENTO');
    
    $stmtPagamento = $pdo->prepare("INSERT INTO PAGAMENTO (numero_empenho,numero_pagamento,data_pagamento,valor_pagamento,sinal_valor,obsoleto1,codigo_operacao,conta_contabil_debito,uniorcam_debito,conta_contabil_credito,uniorcam_credito,historico_pagamento,numero_liquidacao,ano_empenho,data_inicial,data_final,data_geracao,cnpj,entidade,arquivo,orgao,uniorcam,funcao,subfuncao,programa,projativ,rubrica,recurso_vinculado,contrapartida_recurso_vinculado,credor,caracteristica_peculiar_despesa,registro_precos,numero_licitacao,ano_licitacao,historico_empenho,forma_contratacao,base_legal,despesa_funcionario,licitacao_compartilhada,cnpj_gerenciador_licitacao_compartilhada,complemento_recurso_vinculado) VALUES(:numero_empenho,:numero_pagamento,:data_pagamento,:valor_pagamento,:sinal_valor,:obsoleto1,:codigo_operacao,:conta_contabil_debito,:uniorcam_debito,:conta_contabil_credito,:uniorcam_credito,:historico_pagamento,:numero_liquidacao,:ano_empenho,:data_inicial,:data_final,:data_geracao,:cnpj,:entidade,:arquivo,:orgao,:uniorcam,:funcao,:subfuncao,:programa,:projativ,:rubrica,:recurso_vinculado,:contrapartida_recurso_vinculado,:credor,:caracteristica_peculiar_despesa,:registro_precos,:numero_licitacao,:ano_licitacao,:historico_empenho,:forma_contratacao,:base_legal,:despesa_funcionario,:licitacao_compartilhada,:cnpj_gerenciador_licitacao_compartilhada,:complemento_recurso_vinculado);");
    $pagamentoWriter = new PDOWriter($dfPagamento, $stmtPagamento);
    $pagamentoWriter->write();

} catch (Exception $ex) {
    $logger->emergency($ex->getMessage());
    exit($ex->getCode());
}

*/

try {
    $logger->info('Gerando arquivo RESTOS_PAGAR...');
    $pdo = new PDO("sqlite:$outputSQLite");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmtEmpenho = $pdo->query("SELECT * FROM EMPENHO");
    $empenho = new DataFrame(new PDOReader($stmtEmpenho));
    
    $stmtLiquidac = $pdo->query("SELECT * FROM LIQUIDAC");
    $liquidac = new DataFrame(new PDOReader($stmtLiquidac));
    
    $stmtPagament = $pdo->query("SELECT * FROM PAGAMENT");
    $pagament = new DataFrame(new PDOReader($stmtPagament));
        
    $rpAssembler = new RestosAPagarAssembler($empenho, $liquidac, $pagament);
    $dfRP = $rpAssembler->assemble();
    print_r($dfRP->getAsArray());exit();
    PDOWriter::createSQliteTable($dfRP, $pdo, 'RESTOS_PAGAR');
    
    $stmtRP = $pdo->prepare("INSERT INTO RESTOS_PAGAR (orgao,uniorcam,funcao,subfuncao,programa,projativ,rubrica,recurso_vinculado,contrapartida_recurso_vinculado,numero_empenho,data_empenho,valor_empenho,credor,caracteristica_peculiar_despesa,registro_precos,numero_licitacao,ano_licitacao,historico_empenho,forma_contratacao,base_legal,despesa_funcionario,licitacao_compartilhada,cnpj_gerenciador_licitacao_compartilhada,complemento_recurso_vinculado,ano_empenho,data_inicial,data_final,data_geracao,cnpj,entidade,arquivo,saldo_inicial_nao_processados,saldo_inicial_processados,nao_processados_liquidados,nao_processados_pagos,processados_pagos,nao_processados_cancelados,processados_cancelados,saldo_final_nao_processados,saldo_final_processados) VALUES(:orgao,:uniorcam,:funcao,:subfuncao,:programa,:projativ,:rubrica,:recurso_vinculado,:contrapartida_recurso_vinculado,:numero_empenho,:data_empenho,:valor_empenho,:credor,:caracteristica_peculiar_despesa,:registro_precos,:numero_licitacao,:ano_licitacao,:historico_empenho,:forma_contratacao,:base_legal,:despesa_funcionario,:licitacao_compartilhada,:cnpj_gerenciador_licitacao_compartilhada,:complemento_recurso_vinculado,:ano_empenho,:data_inicial,:data_final,:data_geracao,:cnpj,:entidade,:arquivo,:saldo_inicial_nao_processados,:saldo_inicial_processados,:nao_processados_liquidados,:nao_processados_pagos,:processados_pagos,:nao_processados_cancelados,:processados_cancelados,:saldo_final_nao_processados,:saldo_final_processados);");
    $rpWriter = new PDOWriter($dfRP, $stmtRP);
    $rpWriter->write();

} catch (Exception $ex) {
    $logger->emergency($ex->getMessage());
    exit($ex->getCode());
}

/*try {
    $output = new File($outputSQLite);
    $latestFile = new Path($output->getFileDir(), 'latest.sqlite');
    $output->copy($latestFile->getPath());
} catch (Exception $ex) {
    $logger->error($ex->getMessage());
    exit($ex->getCode());
}*/