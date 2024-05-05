<?php

namespace App\Models\Services;

use App\Models\Contato;

/**
 * Serviço de manipulação de dados relacionados aos Contato da Pessoa.
 * @package App\Models\Services
 * @author Ricael V. Chiquetti <ricaelchiquetti28@gmail.com>
 * 
 * @method Contato getModel()
 */
class ContatoModelService extends ModelDataService {

    /**
     * {@inheritDoc}
     */
    public function create(): bool {
        $sql = "INSERT INTO contato (tipo, descricao, idPessoa) VALUES (?, ?, ?);";
        $params = [
            $this->getModel()->getTipo(),
            $this->getModel()->getDescricao(),
            $this->getModel()->getPessoa()->getId()
        ];

        return $this->executeQuery($sql, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function update(): bool {
        $sql = "UPDATE contato 
                   SET tipo  = ?, descricao  = ?
                 WHERE id = ?";

        $params = [
            $this->getModel()->getTipo(),
            $this->getModel()->getDescricao(),
            $this->getModel()->getId()
        ];

        return $this->executeQuery($sql, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(): bool {
        $id = $this->getModel()->getPessoa()->getId();

        $sql = "DELETE FROM contato WHERE idPessoa = ?";
        $params = [$id];

        return $this->executeQuery($sql, $params);
    }

    /**
     * {@inheritDoc}
     */
    protected function getSelectQuery(array $conditions = []): string {
        $sql = "SELECT * FROM contato";

        if (!empty($conditions)) {
            $condition = implode(" AND ", $conditions);
            $sql .= " WHERE $condition ";
        }

        return $sql;
    }

    /**
     * {@inheritDoc}
     */
    protected function getConditionSql(): array {
        $conditions = [];
        $params = [];

        $idPessoa = $this->getModel()->getPessoa()->getId();
        if ($idPessoa) {
            $conditions[] = "idPessoa = ?";
            $params[] = $idPessoa;
        }

        return [$conditions, $params];
    }
}
