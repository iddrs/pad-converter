<?php

namespace IDDRS\SIAPC\PAD\Converter\Assembler;

use PTK\DataFrame\DataFrame;
use PTK\DataFrame\Reader\ArrayReader;

/**
 * Retira o 9 inicial do código das dedutoras da receita no bal_rec
 *
 * @author Everton
 */
class RecAntAltAssembler implements AssemblerInterface {

    protected DataFrame $balRec;

    public function __construct(DataFrame $balRec) {
        $this->balRec = $balRec;
    }

    public function assemble(): DataFrame {
        $data = $this->balRec->getAsArray();
        foreach($data as $index => $item){
            $data[$index]['codigo_receita'] = substr($item['codigo_receita'], 2);
        }
        return new DataFrame(new ArrayReader($data));
    }

}
