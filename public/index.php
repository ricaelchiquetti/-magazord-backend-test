<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Pessoas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/consulta_pessoa.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Consulta de Pessoas</h1>
        <!-- Alerts -->
        <div id="alertas" class="alertas"></div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="inputNome" placeholder="Nome">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="btnConsultar">Consultar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-success" id="btnIncluir">Incluir</button>
                <button type="button" class="btn btn-danger" id="btnExcluir">Excluir</button>
                <button type="button" class="btn btn-warning" id="btnAlterar">Alterar</button>
            </div>
        </div>

        <!-- Tabela de Pessoas -->
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                </tr>
            </thead>
            <tbody id="tabelaPessoas">
            </tbody>
        </table>
    </div>

    <!-- Modal de Cadastro -->
    <div class="modal fade" id="modalCadastro" tabindex="-1" role="dialog" aria-labelledby="modalCadastroLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCadastroLabel">Cadastro de Pessoa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCadastro">
                        <input type="hidden" class="form-control" id="inputIdModal" placeholder="Id">
                        <div class="form-group">
                            <label for="inputNome">Nome</label>
                            <input type="text" class="form-control" id="inputNomeModal" placeholder="Nome">
                        </div>
                        <div class="form-group">
                            <label for="inputCPF">CPF</label>
                            <input type="text" class="form-control" id="inputCPFModal" placeholder="CPF">
                        </div>

                        <div class="form-group" id="contatosContainer">
                            <label for="contatos">Contatos</label>
                            <div class="input-group group_grid">
                                <select class="form-control" id="selectTipoContato">
                                    <option value="email">E-mail</option>
                                    <option value="telefone">Telefone</option>
                                </select>
                                <input type="text" class="form-control" id="inputDescricaoContato"
                                    placeholder="Descrição">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="btnAdicionarContato">Adicionar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="btnSalvar">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js">
    </script>
    <script src="js/consulta_pessoas.js"></script>
</body>

</html>