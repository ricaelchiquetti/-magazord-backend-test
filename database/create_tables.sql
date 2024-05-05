-- Arquivo: create_tables.sql
-- Criação da tabela pessoa
CREATE TABLE IF NOT EXISTS pessoa (
    id SERIAL PRIMARY KEY,
    -- Identificador único da pessoa
    nome VARCHAR(255) NOT NULL,
    -- Nome da pessoa
    cpf VARCHAR(14) UNIQUE -- CPF da pessoa (único)
);
-- Criação da tabela contato
CREATE TABLE IF NOT EXISTS contato (
    id SERIAL PRIMARY KEY,
    -- Identificador único do contato
    tipo BOOLEAN NOT NULL,
    -- Tipo de contato: TRUE para email, FALSE para telefone
    descricao VARCHAR(255) NOT NULL,
    -- Descrição do contato (email ou número de telefone)
    idPessoa INT,
    -- Chave estrangeira referenciando a pessoa associada
    FOREIGN KEY (idPessoa) REFERENCES pessoa(id),
    -- Check para garantir que o formato do email seja válido ou que o número de telefone contenha apenas dígitos
    CONSTRAINT chk_contato_formato CHECK (
        (
            tipo = true
            AND descricao ~* '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$'
        )
        OR (
            tipo = false
            AND descricao ~ '^\(\d{2}\) \d{4}-\d{4}$'
        )
    )
);