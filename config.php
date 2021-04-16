<?php

/* 
 * The MIT License
 *
 * Copyright 2021 Everton.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Arquivo de configuração coms os parãmetros de conversão
 * 
 */

$mes = 3;
$ano = 2021;

$mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
$sourceDir = [
//    "C:\\Users\\Everton\\OneDrive\\Prefeitura\\$ano\\PAD\\$ano-$mes\\pm\\MES$mes",
//    "C:\\Users\\Everton\\OneDrive\\Prefeitura\\$ano\\PAD\\$ano-$mes\\cm\\MES$mes"
    \ptk\fs\slashes(
        \ptk\fs\join_path(
            "C:\\Users\\Everton\\OneDrive\\Prefeitura",
            $ano,
            "PAD",
            $ano,
            "-",
            $mes,
            "pm\\MES",
            $mes
        )
    ),
    ptk\fs\slashes(
        \ptk\fs\join_path(
            "C:\\Users\\Everton\\OneDrive\\Prefeitura",
            $ano,
            "PAD",
            $ano,
            "-",
            $mes,
            "cm\\MES",
            $mes
        )
    ),
];

//$destinyRootPath = "C:\\Users\\Everton\\OneDrive\\Prefeitura\\padData\\$outputFormat";
$destinyRootPath = ptk\fs\slashes(
    ptk\fs\join_path(
        "C:\\Users\\Everton\\OneDrive\\Prefeitura\\padData",
        $outputFormat
    )
);
//$latestDir = "$destinyRootPath\\latest";
//$oldestDir = "$destinyRootPath\\oldest";
$latestDir = ptk\fs\slashes(
    ptk\fs\join_path($destinyRootPath, "latest")
);
$oldestDir = ptk\fs\slashes(
    ptk\fs\join_path($destinyRootPath, "oldest")
);