<?php

namespace IDDRS\SIAPC\PAD\Converter\Writer;

use Exception;
use IDDRS\SIAPC\PAD\Converter\Data\Data;
use IDDRS\SIAPC\PAD\Converter\Exception\WarningException;
use IDDRS\SIAPC\PAD\Converter\Writer\WriterInterface;
use PDO;
use PDOStatement;
use PTK\DataFrame\DataFrame;
use PTK\DataFrame\Writer\PDOWriter;

class SQLiteWriter implements WriterInterface {

    protected PDO $dbh;

    public function __construct(string $filename) {
        try {
            $this->dbh = new PDO("sqlite:$filename");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function saveOutput(Data $data): void {
        $tableExists = $this->tableExists($data->fileId());
        if ($tableExists === false) {
            $this->createTable($data->dataFrame(), $this->dbh, $data->fileId());
        }
        $stmt = $this->prepareStatement($data->dataFrame()->getColNames(), $data->fileId());
        $writer = new PDOWriter($data->dataFrame(), $stmt);
        $writer->write();
    }

    protected function prepareStatement(array $colNames, string $tableName): PDOStatement {
        $cols = join(', ', $colNames);
        foreach ($colNames as $k => $v) {
            $colNames[$k] = ":$v";
        }
        $values = join(', ', $colNames);

        return $this->dbh->prepare("INSERT INTO $tableName ($cols) VALUES ($values);");
    }

    protected function createTable(DataFrame $df, PDO $dbh, string $tableName): void {
        try {
            $tableCreate = PDOWriter::createSQliteTable($df, $dbh, $tableName);
            if ($tableCreate === false) {
                throw new WarningException("Não foi possível criar a tabela {$data->fileId()}");
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    protected function tableExists(string $tableName): bool {
        $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='$tableName'";
        $exists = $this->dbh->query($sql);
        // estranhamente se usar rewCount() ou outra forma para identificar se tem ou não resultados, 
        // após a primeira tabela sempre dá true.
        // só com fetchAll() é que o resultado retorna adequadamente.
        if (sizeof($exists->fetchAll()) === 1) {
            return true;
        }

        return false;
    }

}
