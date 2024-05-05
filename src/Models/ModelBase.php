<?php

namespace App\Models;

use Exception;

/**
 * Modelo de Dados
 * @package App\Model
 * @author Ricael V. Chiquetti <ricaelchiquetti28@gmail.com>
 */
abstract class ModelBase implements ModelInterface {

    /**
     * Preenche os atributos do modelo com base nos dados fornecidos no array associativo.
     * @param array $data
     */
    public function fill(array|null $data = []): void {
        if ($data) {
            foreach ($data as $key => $value) {
                if ($value != "") {
                    $this->setProperty($key, $value);
                }
            }
        }
    }

    /**
     * Define a propriedade no modelo.
     * @param string $key
     * @param mixed $value
     */
    private function setProperty(string $key, $value): void {
        if (strpos($key, '.') !== false) {
            $this->setNestedProperty($key, $value);
        } else {
            $methodName = 'set' . ucfirst($key);
            if (method_exists($this, $methodName)) {
                $this->{$methodName}($value);
            } else {
                throw new Exception("Função $methodName Não existe no " . get_class($this));
            }
        }
    }

    /**
     * Define uma propriedade aninhada no modelo.
     * @param string $key
     * @param mixed $value
     */
    private function setNestedProperty(string $key, $value): void {
        $keys = explode('.', $key);
        $lastKey = array_pop($keys);
        $property = $this;

        foreach ($keys as $nestedKey) {
            $methodName = 'get' . ucfirst($nestedKey);
            if (method_exists($property, $methodName)) {
                $property = $property->{$methodName}();
            } else {
                return;
            }
        }

        $setMethodName = 'set' . ucfirst($lastKey);
        if (method_exists($property, $setMethodName)) {
            $property->{$setMethodName}($value);
        }
    }
}
