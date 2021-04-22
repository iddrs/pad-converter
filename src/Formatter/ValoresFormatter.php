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
        return round($valor / 100, 2);
    }

    public static function valorComSinal(string $campoValor, string $campoSinal, array $line): array {
        $valor = round($line[$campoValor] / 100, 2);
        
        switch ($line[$campoSinal]){
            case '+':
                $valor = $valor;
                break;
            case '-':
                $valor = $valor * -1;
                break;
            default :
                print_r($line);
                throw new ErrorException("Sinal inválido na linha de sinal do valor.");
        }
        
        $line[$campoValor] = $valor;
        return $line;
    }

    public static function trim(string $data): string {
        return trim($data);
    }

}
