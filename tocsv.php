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
 * Converte so txt para CSV
 */
$outputFormat = 'csv';

require_once 'vendor/autoload.php';
require 'config.php';

//prepara o climate
try{
    $climate = new League\CLImate\CLImate();
} catch (Exception $ex) {
    $ex->getTraceAsString();
}

//copia o conteúdo de latest para oldest
try {
    // deletando oldest
    $climate->green()->out("Excluindo conteúdo antigo de...");
    $climate->blue()->bold()->tab()->out($oldestDir);
    
    //movendo latest->oldest
    $climate->green()->out("Movendo conteúdo de...");
    $climate->blue()->bold()->tab()->out($latestDir);
    $climate->tab()->green()->out("...para...");
    $climate->blue()->bold()->tab()->out($oldestDir);
} catch (Exception $ex) {
    $ex->getTraceAsString();
} catch (Exception $ex) {
    $ex->getTraceAsString();
}
