<?php

namespace App\Models\Services;

use App\Models\Contato;
use App\Models\Pessoa;

/**
 * Serviço de manipulação de dados relacionados aos Pessoa.
 * @package App\Models\Services
 * @author Ricael V. Chiquetti <ricaelchiquetti28@gmail.com>
 * 
 * @method Pessoa getModel()
 */
class PessoaModelService extends ModelDataService {

    /**
     * {@inheritDoc}
     */
    public function create(): bool {
        $nome = $this->getModel()->getNome();
        $cpf = $this->getModel()->getCpf();

        $sql = "INSERT INTO pessoa (nome, cpf) VALUES (?, ?)";
        $params = [$nome, $cpf];

        $idPessoa = $this->executeQuery($sql, $params);
        $this->getModel()->setId($idPessoa);

        $modelContato = new ContatoModelService();
        foreach ($this->getModel()->getContato() as $contato) {
            $modelContato->setModel($contato);
            if (!$modelContato->create()) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function update(): bool {
        $id = $this->getModel()->getId();
        $nome = $this->getModel()->getNome();
        $cpf = $this->getModel()->getCpf();

        $sql = "UPDATE pessoa 
                   SET nome = ?, cpf = ?
                 WHERE id = ?";
        $params = [$nome, $cpf, $id];

        $modelContato = new ContatoModelService(new Contato());
        $modelContato->getModel()->setPessoa($this->getModel());
        $modelContato->delete();

        foreach ($this->getModel()->getContato() as $contato) {
            $modelContato->setModel($contato);
            if (!$modelContato->create()) {
                return false;
            }
        }

        return $this->executeQuery($sql, $params);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(): bool {
        $id = $this->getModel()->getId();

        $modelContato = new ContatoModelService(new Contato());
        $modelContato->getModel()->setPessoa($this->getModel());
        $modelContato->delete();

        $sql = "DELETE FROM pessoa WHERE id = ?";
        $params = [$id];

        return $this->executeQuery($sql, $params);
    }

    /**
     * {@inheritDoc}
     */
    protected function getSelectQuery(array $conditions = []): string {
        $sql = "SELECT * FROM pessoa";

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

        $id = $this->getModel()->getId();
        if ($id) {
            $conditions[] = "id = ?";
            $params[] = $id;
        }

        $nome = $this->getModel()->getNome();
        if ($nome) {
            $conditions[] = "nome ILIKE ?";
            $params[] = "%$nome%";
        }

        $cpf = $this->getModel()->getCpf();
        if ($cpf) {
            $conditions[] = "cpf = ?";
            $params[] = $cpf;
        }

        return [$conditions, $params];
    }
}
