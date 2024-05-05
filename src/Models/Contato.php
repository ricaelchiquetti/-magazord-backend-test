<?php

namespace App\Models;

/**
 * Modelo de dados de Contato.
 * @package App\Model
 * @author Ricael V. Chiquetti <ricaelchiquetti28@gmail.com>
 */
class Contato extends ModelBase {

    private ?int $id;
    private bool $tipo;
    private string $descricao;
    private Pessoa $Pessoa;

    /**
     * Obtém o ID do contato.
     *
     * @return int|null O ID do contato.
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Obtém o tipo de contato.
     *
     * @return bool O tipo de contato (TRUE para email, FALSE para telefone).
     */
    public function getTipo(): bool {
        return $this->tipo;
    }

    /**
     * Obtém a descrição do contato.
     *
     * @return string A descrição do contato (email ou número de telefone).
     */
    public function getDescricao(): string {
        return $this->descricao;
    }

    /**
     * Obtém a pessoa associada ao contato.
     * @return Pessoa
     */
    public function getPessoa(): Pessoa {
        return $this->Pessoa ??= new Pessoa();
    }

    /**
     * Define o tipo de contato.
     *
     * @param bool $tipo O tipo de contato (TRUE para email, FALSE para telefone).
     * @return Contato A instância atual da classe Contato.
     */
    public function setTipo(bool $tipo): self {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * Define a descrição do contato.
     *
     * @param string $descricao A descrição do contato (email ou número de telefone).
     * @return Contato A instância atual da classe Contato.
     */
    public function setDescricao(string $descricao): self {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * Define a pessoa associada ao contato.
     *
     * @param Pessoa $idPessoa O ID da pessoa associada ao contato.
     * @return Contato A instância atual da classe Contato.
     */
    public function setPessoa(Pessoa $Pessoa): self {
        $this->Pessoa = $Pessoa;
        return $this;
    }
}
