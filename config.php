<?php

require_once 'vendor/autoload.php';

use PTK\FS\Path;
use PTK\Console\Form\Field\NumberField;

/**
 * Arquivo de configuração coms os parâmetros de conversão
 * 
 */

//$mes = 6;
//$ano = 2022;

$getMes = new NumberField('mes', 'Mês:');
$getMes->setMin(1);
$getMes->setMax(12);
//$getMes->onlyInt(true);
$getMes->required(true);
$getMes->showDefaultInLabel(false);
$getMes->showFormatInLabel(false);
$getMes->showMinMaxInLabel(false);

$getAno = new NumberField('ano', 'Ano:');
//$getAno->onlyInt(true);
$getAno->required(true);
$getAno->showDefaultInLabel(false);
$getAno->showFormatInLabel(false);
$getAno->showMinMaxInLabel(false);

$getMes->ask();
$getAno->ask();

$mes = str_pad($getMes->answer(), 2, '0', STR_PAD_LEFT);
$ano = $getAno->answer();

$inputDir = [
    (new Path('C:/Users/Everton/OneDrive/Prefeitura', $ano, 'PAD', "$ano-$mes", 'pm', "MES$mes"))->getPath(),
    (new Path('C:/Users/Everton/OneDrive/Prefeitura', $ano, 'PAD', "$ano-$mes", 'cm', "MES$mes"))->getPath(),
//	(new Path('Z:/Abase/ARQUIVOSPAD', $ano, "MES$mes"))->getPath(),
//	(new Path('Z:/Abase/ARQUIVOSPAD', $ano, 'CAMARA', "MES$mes"))->getPath(),
];

//$outputSQLite = (new Path('C:/Users/Everton/OneDrive/Prefeitura/PAD', "$ano-$mes.sqlite"))->getPath();
$outputCSV = (new Path('C:/Users/Everton/OneDrive/Prefeitura/PAD', "$ano-$mes"))->getPath();
