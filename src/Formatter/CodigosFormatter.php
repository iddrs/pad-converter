<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace IDDRS\SIAPC\PAD\Converter\Formatter;

/**
 * Formata códigos
 *
 * @author Everton
 */
class CodigosFormatter extends FormatterBase {
    
    /**
     * Formata o código contábil
     * 
     * @param string $data
     * @return string
     */
    public static function contaContabil(string $data): string
    {
        $data = self::trimLeftZeros($data);
        $data = self::applyMask('#.#.#.#.#.##.##.##.##.##.##.###', $data);
        return $data;
    }
    
    public static function despesaDesdobramento(string $data): string
    {
        $data = self::trimLeftZeros($data);
        $data = self::applyMask('#.#.##.##.##.##.##.###', $data);
        return $data;
    }
    
    public static function uniorcam(array $line): array {
        $line['uniorcam'] = str_pad($line['orgao'], 2, '0', STR_PAD_LEFT).str_pad($line['uniorcam'], 2, '0', STR_PAD_LEFT);
        return $line;
    }
}
