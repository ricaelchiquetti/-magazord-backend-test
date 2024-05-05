<?php

namespace App\Controllers;

use App\Models\Contato;
use App\Models\Services\ContatoModelService;

/**
 * Controller relacionado aos Contato das Pessoas
 * @package App\Controller
 * @author Ricael V. Chiquetti <ricaelchiquetti28@gmail.com>
 */
class ContatoController extends ControllerBase {

    /**
     * {@inheritDoc}
     * @return Contato
     */
    public function getModelService(): ContatoModelService {
        return $this->ModelService ??= new ContatoModelService(new Contato());
    }
}
