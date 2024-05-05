<?php

namespace App\Controllers;

use App\Models\Services\ModelDataService;
use Exception;

/**
 * Controller Base
 * @package App\Controller
 * @author Ricael V. Chiquetti <ricaelchiquetti28@gmail.com>
 */
abstract class ControllerBase {

    /** @var ModelDataService */
    protected $ModelService;

    /**
     * Construtor da classe ControllerBase.
     * @param ModelDataService $ModelService
     */
    public function __construct(ModelDataService $ModelService = null) {
        $this->ModelService = $ModelService;
    }

    /**
     * Manipula a requisição recebida e executa a função necessaria.
     */
    public function handleRequest() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            switch ($method) {
                case 'GET':
                    $this->handleGet();
                    break;
                case 'POST':
                    $this->handleCreate();
                    break;
                case 'PUT':
                    $this->handleUpdate();
                    break;
                case 'DELETE':
                    $this->handleDelete();
                    break;
                default:
                    header("HTTP/1.1 405 Method Not Allowed");
                    header("Allow: GET, POST, PUT, DELETE");
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => $e->getMessage()]);
        }
    }

    /**
     * Manipula a requisição GET.
     * @throws Exception
     */
    protected function handleGet() {
        if (!empty($_GET['filter'])) {
            $filter = $_GET['filter'];
            $this->getModelService()->getModel()->fill($filter);
            $return = $this->getModelService()->find($filter);
            echo json_encode($return);
        } else {
            $return = $this->getModelService()->find();
            echo json_encode($return);
        }
    }

    /**
     * Manipula a requisição POST.
     * @throws Exception
     */
    protected function handleCreate() {
        $data = $this->getRequestData();
        if ($data) {
            $this->getModelService()->getModel()->fill($data);
            $this->getModelService()->create();
        } else {
            throw new Exception('Parâmetros não especificados');
        }
    }

    /**
     * Manipula a requisição PUT.
     * @throws Exception
     */
    protected function handleUpdate() {
        $data = $this->getRequestData();
        if ($data) {
            $this->getModelService()->getModel()->fill($data);
            $this->getModelService()->update();
        } else {
            throw new Exception('Parâmetros não especificados');
        }
    }

    /**
     * Manipula a requisição DELETE.
     * @throws Exception
     */
    protected function handleDelete() {
        $data = $this->getRequestData();
        if ($data) {
            $this->getModelService()->getModel()->fill($data);
            $this->getModelService()->delete();
        } else {
            throw new Exception('Parâmetros não especificados');
        }
    }

    /**
     *Retorna os dados da requisição com base no método (POST ou GET).
     * @return array|null 
     */
    private function getRequestData(): ?array {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $requestData = !empty($_POST['data']) ? $_POST['data'] : null;
                break;
            case 'GET':
                $requestData = !empty($_GET['data']) ? json_decode($_GET['data'], true) : null;
                break;
            case 'PUT':
            case 'DELETE':
                $data = file_get_contents('php://input');
                $requestData = !empty($data) ? json_decode($data, true) : null;
                break;
            default:
                $requestData = null;
                break;
        }
        return $requestData;
    }

    /**
     * Retorna o serviço de dados do modelo
     * @return ModelDataService
     */
    public function getModelService(): ModelDataService {
        return $this->ModelService;
    }
}
