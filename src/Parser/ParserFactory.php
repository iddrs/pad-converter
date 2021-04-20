<?php

namespace IDDRS\SIAPC\PAD\Converter\Parser;

use IDDRS\SIAPC\PAD\Converter\Parser\ParserInterface;

class ParserFactory {

    public function getFactory(string $fileId): ParserInterface {
        $parserClassName = $this->detectParserClassName($fileId);

        if ($this->parserExists($parserClassName)) {
            $parser = new $parserClassName();
            return $parser;
        }
        return new NullParser();
    }

    /**
     * Necessário para ver se tem parser porque todos os outros métodos para
     *  detectar se existe classe ou carregar ela dinamicamente falharam.
     * 
     * @param string $parserClassName
     * @return bool
     */
    protected function parserExists(string $parserClassName): bool {
        $boom = explode('\\', $parserClassName);
        $fileClass = $boom[array_key_last($boom)] . ".php";
        $pathFileClass = "src/Parser/$fileClass";
        return file_exists($pathFileClass);
    }

    protected function detectParserClassName(string $fileId): string {
        $pieces = explode('_', $fileId);
        $pieces = array_map('strtolower', $pieces);
        $pieces = array_map('ucfirst', $pieces);
        $parserClassName = join('', $pieces);
        $qualifiedParserClassName = "IDDRS\\SIAPC\\PAD\\Converter\\Parser\\{$parserClassName}Parser";
        return $qualifiedParserClassName;
    }

}
