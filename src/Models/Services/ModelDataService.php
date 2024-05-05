<?php

namespace App\Models\Services;

use App\Database;
use App\Models\ModelBase;
use PDO;

/**
 * Serviço para manipulação de dados relacionados ao modelo
 * @package App\Service
 * @author Ricael V. Chiquetti <ricaelchiquetti28@gmail.com>
 */
abstract class ModelDataService implements ModelDataServiceInterface {

    /** @var ModelBase */
    protected $model;

    /**
     * Contruict do Model
     * @param ModelBase $model
     */
    public function __construct(?ModelBase $model = null) {
        $this->model = $model;
    }

    /**
     * Retorna uma instancia do Model
     * @return ModelBase
     */
    public function getModel(): ModelBase {
        return $this->model;
    }

    /**
     * Define uma instancia do Model
     * @param ModelBase $model
     * @return ModelDataService
     */
    public function setModel(ModelBase $model): ModelDataService {
        $this->model = $model;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function find(): array {
        list($conditions, $params) = $this->getConditionSql();
        $sql = $this->getSelectQuery($conditions);
        return $this->executeQuery($sql, $params);
    }

    /**
     * Retorna a Query do Select
     * @param array $conditions
     * @return string
     */
    abstract protected function getSelectQuery(array $conditions = []): string;

    /**
     * Retorna as Condições e os parametros do SQL
     * @return array
     */
    abstract protected function getConditionSql(): array;

    /**
     * Executa um SQL de consulta no BD
     * @param string $sql
     * @param array $params
     * @return array|bool
     */
    protected function executeQuery(string $sql, array $params = []): array|bool|int {
        $conn = Database::getConnection();

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    if (is_bool($value)) {
                        $value = (int)$value;
                    }
                    $stmt->bindValue($key + 1, $value);
                }
            }

            $executed = $stmt->execute();

            if (stripos($sql, 'SELECT') === 0) {
                if ($executed) {
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $rows;
                } else {
                    return [];
                }
            } else {
                if (stripos($sql, 'INSERT') === 0) {
                    return $conn->lastInsertId();
                }
                return true;
            }
        }
        return false;
    }

    public function startTransaction(): void {
        Database::getConnection()->beginTransaction();
    }

    public function rollbackTransaction(): void {
        Database::getConnection()->rollBack();
    }

    public function commitTransaction(): void {
        Database::getConnection()->commit();
    }
}
