<?php

namespace IDDRS\SIAPC\PAD\Converter\Formatter;

/**
 * Description of FormatterAbstract
 *
 * @author Everton
 */
abstract class FormatterAbstract {
    abstract public static function format(string $data): string;
    
    protected static function applyMask(string $mask, string $data): string {
        $masked = '';
        $j = 0;
        for($i = 0; $i < strlen($mask); $i++){
            if($mask[$i] === '#'){
                $masked .= $data[$j];
                $j++;
            }else{
                $masked .= $mask[$i];
            }
        }
        
        return $masked;
    }
    
    protected static function trimLeftZeros(string $data): string {
        $trimed = '';
        $stop = false;
        for($i = 0; $i < strlen($data); $i++){
            if($data[$i] === '0' && $stop === false){
                continue;
            }
            $stop = true;
            $trimed .= $data[$i];
        }
        
        return str_pad($trimed, strlen($data), '0', STR_PAD_RIGHT);
    }
}
