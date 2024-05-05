<?php

namespace App\Models;

/**
 * Modelo de dados de Pessoa.
 * @package App\Model
 * @author Ricael V. Chiquetti <ricaelchiquetti28@gmail.com>
 */
class Pessoa extends ModelBase {

    private ?int $id;
    private string $nome;
    private string $cpf;

    private array $contato = [];

    /**
     * Construtor da classe Pessoa.
     * @param string $nome O nome da pessoa.
     * @param string $cpf O CPF da pessoa.
     */
    public function __construct(string $nome = '', string $cpf = '') {
        $this->nome = $nome;
        $this->cpf = $cpf;
    }

    /**
     * Obtém o ID da pessoa.
     *
     * @return int|null O ID da pessoa.
     */
    public function getId(): ?int {
        if (isset($this->id)) {
            return $this->id;
        }
        return null;
    }

    /**
     * Obtém o nome da pessoa.
     *
     * @return string O nome da pessoa.
     */
    public function getNome(): string {
        return $this->nome;
    }

    /**
     * Obtém o CPF da pessoa.
     *
     * @return string O CPF da pessoa.
     */
    public function getCpf(): string {
        return $this->cpf;
    }

    /**
     * Retorna o Id da Pessoa.
     *
     * @param integer $id
     * @return self
     */
    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    /**
     * Define o nome da pessoa.
     *
     * @param string $nome O nome da pessoa.
     * @return Pessoa A instância atual da classe Pessoa.
     */
    public function setNome(string $nome): self {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Define o CPF da pessoa.
     *
     * @param string $cpf O CPF da pessoa.
     * @return Pessoa A instância atual da classe Pessoa.
     */
    public function setCpf(string $cpf): self {
        $this->cpf = $cpf;
        return $this;
    }

    /**
     * Retorna todos os Contatos
     * @return Contato
     */
    public function getContato() {
        return $this->contato;
    }

    /**
     * Adiciona um novo Contato
     *
     * @param array [tipo, descricao]
     * @return self
     */
    public function setContato(array $arrayContato) {
        foreach ($arrayContato as $infoContato) {
            $contato = new Contato();
            $contato->setTipo($infoContato['tipo'] == 'email');
            $contato->setDescricao($infoContato['descricao']);
            $contato->setPessoa($this);
            $this->contato[] = $contato;
        }

        return $this;
    }
}