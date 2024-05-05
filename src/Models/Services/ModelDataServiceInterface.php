<?php

namespace App\Models\Services;

use App\Models\ModelBase;

/**
 * Interface para o serviço de manipulação de dados relacionados ao modelo.
 * Define métodos para buscar, criar, atualizar e deletar registros do modelo.
 * @package App\Models\Services
 * @author Ricael V. Chiquetti <ricaelchiquetti28@gmail.com>
 */
interface ModelDataServiceInterface {

    /**
     * Retorna uma Instancia do Modelo
     * @return ModelBase
     */
    public function getModel(): ModelBase;

    /**
     * Busca registros do modelo com base em um filtro.
     * @return array
     */
    public function find(): array;

    /**
     * Cria um novo registro no modelo.
     * @return bool
     */
    public function create(): bool;

    /**
     * Atualiza um registro existente no modelo.
     * @return bool
     */
    public function update(): bool;

    /**
     * Deleta um registro do modelo com base no ID fornecido.
     * @return bool
     */
    public function delete(): bool;
}
