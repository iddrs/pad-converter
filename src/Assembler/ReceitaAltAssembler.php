<?php

namespace IDDRS\SIAPC\PAD\Converter\Assembler;

use PTK\DataFrame\DataFrame;
use PTK\DataFrame\Reader\ArrayReader;

/**
 * Retira o 9 inicial do código das dedutoras da receita no bal_rec
 *
 * @author Everton
 */
class ReceitaAltAssembler implements AssemblerInterface {

    protected DataFrame $receita;

    public function __construct(DataFrame $receita) {
        $this->receita = $receita;
    }

    public function assemble(): DataFrame {
        $data = $this->receita->getAsArray();
        foreach($data as $index => $item){
            $data[$index]['codigo_receita'] = substr($item['codigo_receita'], 2);
        }
        return new DataFrame(new ArrayReader($data));
    }

}
