<?php

use PTK\DataFrame\DataFrame;

namespace IDDRS\SIAPC\PAD\Converter\Assembler;

/**
 *
 * @author Everton
 */
interface AssemblerInterface {
    public function assemble(): DataFrame;
}
