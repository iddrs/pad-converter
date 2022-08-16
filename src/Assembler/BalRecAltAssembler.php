<?php

namespace IDDRS\SIAPC\PAD\Converter\Assembler;

use PTK\DataFrame\DataFrame;
use PTK\DataFrame\Reader\ArrayReader;

/**
 * Retira o 9 inicial do código das dedutoras da receita no bal_rec
 *
 * @author Everton
 */
class BalRecAltAssembler implements AssemblerInterface {

    protected DataFrame $balRec;

    public function __construct(DataFrame $balRec) {
        $this->balRec = $balRec;
    }

    public function assemble(): DataFrame {
        $data = $this->balRec->getAsArray();
        foreach($data as $index => $item){
            if($data[$index]['codigo_receita'][0] == 4){
                switch($data[$index]['codigo_receita'][2]){
                    case 1:
                    case 2:
                        $data[$index]['tipo_receita'] = 'normal';
                        $data[$index]['base_receita'] = substr($item['codigo_receita'], 2);
                        break;
                    case 7:
                        $data[$index]['tipo_receita'] = 'intra';
                        $data[$index]['base_receita'] = "1".substr($item['codigo_receita'], 3);
                        break;
                    case 8:
                        $data[$index]['tipo_receita'] = 'intra';
                        $data[$index]['base_receita'] = "2".substr($item['codigo_receita'], 3);
                        break;
                }
            }else{
                $data[$index]['tipo_receita'] = 'dedutora';
                $data[$index]['base_receita'] = substr($item['codigo_receita'], 2);
            }
            $data[$index]['codigo_receita'] = substr($item['codigo_receita'], 2);
        }
        return new DataFrame(new ArrayReader($data));
    }

}
