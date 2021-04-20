<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace IDDRS\SIAPC\PAD\Converter\Formatter;

/**
 * Description of ContaContabil
 *
 * @author Everton
 */
class ContaContabilFormatter extends FormatterAbstract {
    public static function format(string $data): string
    {
        $data = self::trimLeftZeros($data);
        $data = self::applyMask('#.#.#.#.#.##.##.##.##.##.##.###', $data);
        return $data;
    }
}
