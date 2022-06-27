<?php

use PTK\FS\Path;

/**
 * Arquivo de configuração coms os parâmetros de conversão
 * 
 */

$mes = 12;
$ano = 2018;


$mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
$inputDir = [
//    (new Path('C:/Users/Everton/OneDrive/Prefeitura', $ano, 'PAD', "$ano-$mes", 'pm', "MES$mes"))->getPath(),
//    (new Path('C:/Users/Everton/OneDrive/Prefeitura', $ano, 'PAD', "$ano-$mes", 'cm', "MES$mes"))->getPath(),
	(new Path('Z:/Abase/ARQUIVOSPAD', $ano, "MES$mes"))->getPath(),
	(new Path('Z:/Abase/ARQUIVOSPAD', $ano, 'CAMARA', "MES$mes"))->getPath(),
];

//$outputSQLite = (new Path('C:/Users/Everton/OneDrive/Prefeitura/PAD', "$ano-$mes.sqlite"))->getPath();
$outputCSV = (new Path('C:/Users/Everton/OneDrive/Prefeitura/PAD', "$ano-$mes"))->getPath();
