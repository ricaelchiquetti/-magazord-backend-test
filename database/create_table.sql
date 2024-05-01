-- Arquivo: create_tables.sql

-- Função para validar CPF no DB
CREATE OR REPLACE FUNCTION is_valid_cpf(text) RETURNS BOOLEAN AS $$
DECLARE
    cpf ALIAS FOR $1;
    v1 INT;
    v2 INT;
BEGIN
    cpf := regexp_replace(cpf, '[^0-9]', '', 'g'); -- Remove todos os caracteres não numéricos do CPF

    IF length(cpf) <> 11 THEN
        RETURN FALSE; -- CPF deve ter 11 dígitos
    END IF;

    -- Calcula o primeiro dígito verificador
    v1 := (10 * substring(cpf, 1, 1)::INT + 9 * substring(cpf, 2, 1)::INT + 8 * substring(cpf, 3, 1)::INT +
           7 * substring(cpf, 4, 1)::INT + 6 * substring(cpf, 5, 1)::INT + 5 * substring(cpf, 6, 1)::INT +
           4 * substring(cpf, 7, 1)::INT + 3 * substring(cpf, 8, 1)::INT + 2 * substring(cpf, 9, 1)::INT) % 11;

    IF v1 >= 10 THEN
        v1 := 0;
    END IF;

    -- Calcula o segundo dígito verificador
    v2 := (11 * substring(cpf, 1, 1)::INT + 10 * substring(cpf, 2, 1)::INT + 9 * substring(cpf, 3, 1)::INT +
           8 * substring(cpf, 4, 1)::INT + 7 * substring(cpf, 5, 1)::INT + 6 * substring(cpf, 6, 1)::INT +
           5 * substring(cpf, 7, 1)::INT + 4 * substring(cpf, 8, 1)::INT + 3 * substring(cpf, 9, 1)::INT +
           2 * v1) % 11;

    IF v2 >= 10 THEN
        v2 := 0;
    END IF;

    -- Verifica se os dígitos verificadores calculados correspondem aos dígitos verificadores fornecidos no CPF
    IF v1 = substring(cpf, 10, 1)::INT AND v2 = substring(cpf, 11, 1)::INT THEN
        RETURN TRUE; -- CPF válido
    ELSE
        RETURN FALSE; -- CPF inválido
    END IF;
END;
$$ LANGUAGE plpgsql IMMUTABLE;


-- Criação da tabela pessoa
CREATE TABLE IF NOT EXISTS pessoa (
    id SERIAL PRIMARY KEY, -- Identificador único da pessoa
    nome VARCHAR(255) NOT NULL COMMENT 'Nome da pessoa', -- Nome da pessoa
    cpf VARCHAR(14) UNIQUE COMMENT 'CPF da pessoa' -- CPF da pessoa (único)
    
    CONSTRAINT chk_cpf_valido CHECK (is_valid_cpf(cpf))
);

-- Criação da tabela contato
CREATE TABLE IF NOT EXISTS contato (
    id SERIAL PRIMARY KEY, -- Identificador único do contato
    tipo BOOLEAN NOT NULL COMMENT 'Tipo de contato: TRUE para email, FALSE para telefone', -- Tipo de contato: TRUE para email, FALSE para telefone
    descricao VARCHAR(255) NOT NULL COMMENT 'Descrição do contato (email ou número de telefone)', -- Descrição do contato (email ou número de telefone)
    idPessoa INT COMMENT 'Chave estrangeira referenciando a pessoa associada', -- Chave estrangeira referenciando a pessoa associada
    FOREIGN KEY (idPessoa) REFERENCES pessoa(id),
    
    -- Check para garantir que o formato do email seja válido ou que o número de telefone contenha apenas dígitos
    CONSTRAINT chk_contato_formato CHECK (
        (tipo = true AND descricao ~* '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$') OR
        (tipo = false AND descricao ~ '^[0-9]+$')
    )
);
