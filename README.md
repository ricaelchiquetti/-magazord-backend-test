# Sistema de Contatos

Este é um simples sistema de contatos desenvolvido como parte do desafio para a posição de Desenvolvedor Back-end no Magazord.com.br. O sistema é construído utilizando PHP, JS, HTML, CSS e o banco de dados SQL, seguindo o padrão MVC e utilizando a ORM Doctrine para manipulação de dados.

## Requisitos Funcionais

- **RF01:** Tela de consulta para pessoas.
- **RF02:** Campo de pesquisa por nome de pessoa.
- **RF03:** Tela de consulta para contatos.
- **RF04:** CRUD (Cadastrar, Visualizar, Alterar, Excluir) para pessoas.
- **RF05:** CRUD (Cadastrar, Visualizar, Alterar, Excluir) para contatos.

## Requisitos Não Funcionais

- **RNF01:** Linguagem PHP para o Back-end.
- **RNF02:** ORM Doctrine para o Back-end.
- **RNF03:** JS, HTML, CSS.
- **RNF04:** Organização pelo padrão MVC.
- **RNF05:** Utilização do Composer para gerenciamento de dependências.
- **RNF06:** Banco de dados SQL (postgres ou mysql).
- **RNF07:** Controle de versão no Github.

## Regras de Negócio

- **RN01:** Dados de pessoas: Nome e CPF.
- **RN02:** Dados de contato: Tipo (Telefone ou Email), Descrição.
- **RN03:** Uma pessoa pode ter vários contatos.

## Estrutura do Projeto

- **database:** Diretório contendo o script SQL para criação das tabelas do banco de dados.
- **public:** Diretório público contendo arquivos de estilo CSS e scripts JS, além do arquivo `index.php`.
- **src:** Diretório contendo o código-fonte do projeto.
  - **controllers:** Controladores PHP seguindo o padrão MVC.
  - **models:** Modelos de dados e serviços relacionados.
- **Database.php:** Configuração de conexão com o banco de dados.
- **composer.json:** Arquivo de configuração do Composer.
- **config.php:** Arquivo de configuração geral do projeto.
- **docker-compose.yml:** Arquivo de configuração do Docker Compose.
- **dockerfile:** Arquivo de configuração do Docker.
- **README.md:** Este arquivo, contendo instruções sobre o projeto.

## Instalação e Execução

1. Clone este repositório:

   ```bash
   git clone https://github.com/ricaelchiquetti/controle_contatos.git
   ```

2. Instale as dependências do Composer:

   ```bash
   composer install
   ```

3. Configure o ambiente de acordo com as instruções contidas nos arquivos de configuração.

4. Execute o sistema utilizando Docker:

   ```bash
   docker-compose up -d
   ```

5. Acesse o sistema em [http://localhost:8080](http://localhost:8080).

## Contribuição

Caso deseje contribuir com este projeto, siga as instruções contidas no arquivo CONTRIBUTING.md.

## Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE).
