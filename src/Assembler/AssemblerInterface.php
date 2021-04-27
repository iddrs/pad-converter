<?php
namespace IDDRS\SIAPC\PAD\Converter\Assembler;

use PTK\DataFrame\DataFrame;
/**
 *
 * @author Everton
 */
interface AssemblerInterface {
    public function assemble(): DataFrame;
}
