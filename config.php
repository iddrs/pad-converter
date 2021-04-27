<?php

use PTK\FS\Path;

/**
 * Arquivo de configuração coms os parâmetros de conversão
 * 
 */

$mes = 2;
$ano = 2021;


$mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
$inputDir = [
    (new Path('C:/Users/Everton/OneDrive/Prefeitura', $ano, 'PAD', "$ano-$mes", 'pm', "MES$mes"))->getPath(),
    (new Path('C:/Users/Everton/OneDrive/Prefeitura', $ano, 'PAD', "$ano-$mes", 'cm', "MES$mes"))->getPath(),
];

$outputSQLite = (new Path('C:/Users/Everton/OneDrive/Prefeitura/PAD', "$ano-$mes.sqlite"))->getPath();
$outputCSV = (new Path('C:/Users/Everton/OneDrive/Prefeitura/PAD', "$ano-$mes"))->getPath();