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
    public static function contaContabil(string $data): string {
        $data = self::trimLeftZeros($data);
        $data = self::applyMask('#.#.#.#.#.##.##.##.##.##.##.###', $data);
        return $data;
    }

    public static function despesaDesdobramento(string $data): string {
        $data = self::trimLeftZeros($data);
        $data = self::applyMask('#.#.##.##.##.##.##.###', $data);
        return $data;
    }

    public static function despesaElemento(string $data): string {
        $data = self::trimLeftZeros($data);
        $data = self::applyMask('#.#.##.##', $data);
        return $data;
    }

    public static function uniorcam(array $line, string $campoOrgao = 'orgao', string $campoUniorcam = 'uniorcam'): array {
        $line[$campoUniorcam] = str_pad($line[$campoOrgao], 2, '0', STR_PAD_LEFT) . str_pad($line[$campoUniorcam], 2, '0', STR_PAD_LEFT);
        return $line;
    }

    public static function naturezaReceita(string $data): string {
        $data = self::trimLeftZeros($data);
        if ($data[0] !== '9') {
            $data = '4' . substr($data, 0, strlen($data) - 1);
        }

        $data = self::applyMask('#.#.#.#.#.##.#.#.##.##.##.##.###', $data);

        return $data;
    }
    
    public static function cpf(string $data): string {
        $data = self::applyMask('###.###.###-##', $data);
        return $data;
    }
    
    public static function cnpj(string $data): string {
        $data = self::applyMask('##.###.###/####-##', $data);
        return $data;
    }

}
