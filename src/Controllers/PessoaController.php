<?php

namespace App\Controllers;

use App\Models\Pessoa;
use App\Models\Services\PessoaModelService;

/**
 * Controller relacionado aos Check-in
 * @package App\Controller
 * @author Ricael V. Chiquetti <ricaelchiquetti28@gmail.com>
 */
class PessoaController extends ControllerBase {

    /**
     * {@inheritDoc}
     * @return Pessoa
     */
    public function getModelService(): PessoaModelService {
        return $this->ModelService ??= new PessoaModelService(new Pessoa());
    }
}
