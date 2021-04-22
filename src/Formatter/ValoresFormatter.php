<?php

namespace IDDRS\SIAPC\PAD\Converter\Formatter;

/**
 * Formata valores
 *
 * @author Everton
 */
class ValoresFormatter extends FormatterBase {
    
    public static function dataStrToStr(string $date): string {
        $dateObj = date_create_from_format('dmY', $date);
        return $dateObj->format('Y-m-d');
    }
    
    public static function valorSemSinal(string $valor): float {
        return round($valor /100, 2);
    }
    
    public static function trim(string $data): string {
        return trim($data);
    }
}
