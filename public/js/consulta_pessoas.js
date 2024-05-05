$(document).ready(function () {

    // Função para exibir mensagem de alerta no canto da tela
    function mostrarAlerta(mensagem, tipo) {
        var alerta = $('<div class="alert alert-' + tipo + ' alert-dismissible fade show" role="alert">' +
            mensagem +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Fechar">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '</div>');
        $('#alertas').append(alerta);
        setTimeout(function () {
            alerta.alert('close');
        }, 5000); // Fecha o alerta após 5 segundos
    }

    // Função para validar CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, ''); // Remove caracteres não numéricos
        if (cpf === '') return false; // Verifica se o CPF está vazio
        // Verifica se todos os dígitos são iguais e se o CPF tem 11 dígitos
        if (
            cpf.length !== 11 ||
            cpf === '00000000000' ||
            cpf === '11111111111' ||
            cpf === '22222222222' ||
            cpf === '33333333333' ||
            cpf === '44444444444' ||
            cpf === '55555555555' ||
            cpf === '66666666666' ||
            cpf === '77777777777' ||
            cpf === '88888888888' ||
            cpf === '99999999999'
        ) {
            return false;
        }
        // Calcula o primeiro dígito verificador
        var soma = 0;
        for (var i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        var resto = 11 - (soma % 11);
        var digitoVerificador1 = resto === 10 || resto === 11 ? 0 : resto;
        // Calcula o segundo dígito verificador
        soma = 0;
        for (var j = 0; j < 10; j++) {
            soma += parseInt(cpf.charAt(j)) * (11 - j);
        }
        resto = 11 - (soma % 11);
        var digitoVerificador2 = resto === 10 || resto === 11 ? 0 : resto;
        // Verifica se os dígitos verificadores calculados correspondem aos dígitos verificadores fornecidos
        return cpf.charAt(9) == digitoVerificador1 && cpf.charAt(10) == digitoVerificador2;
    }

    // Evento de mudança no campo de CPF para validar o CPF
    $('#inputCPFModal').change(function () {
        var cpf = $(this).val();
        if (!validarCPF(cpf)) {
            mostrarAlerta('CPF inválido', 'warning');
            $(this).val(''); // Limpa o campo
        }
    });

    $('#selectTipoContato').change(function () {
        trataFormatoCampoContato();
    });

    // Evento de clique no botão Incluir
    $('#btnIncluir').click(function () {
        limparCamposModal(); // Limpa os campos do modal antes de exibir
        $('#modalCadastro').modal('show'); // Exibe o modal de cadastro
    });

    // Evento de clique no botão Alterar
    $('#btnAlterar').click(function () {
        limparCamposModal(); // Limpa os campos do modal antes de exibir
        // Verifica se uma linha da tabela está selecionada
        var linhaSelecionada = $('#tabelaPessoas').find('tr.selecionada');
        if (linhaSelecionada.length > 0) {

            var id = linhaSelecionada.find('td:nth-child(1)').text();
            var nome = linhaSelecionada.find('td:nth-child(2)').text();
            var cpf = linhaSelecionada.find('td:nth-child(3)').text();
            $('#inputIdModal').val(id);
            $('#inputNomeModal').val(nome);
            $('#inputCPFModal').val(cpf);

            criaContatosLista(id);

            $('#modalCadastro').modal('show'); // Exibe o modal de cadastro
        } else {
            mostrarAlerta('Por favor, selecione uma pessoa para alterar.', 'warning');
        }
    });

    function criaContatosLista(idPessoa) {
        $.ajax({
            url: '/src/Controllers/AjaxController.php/contato',
            type: 'GET',
            data: {
                filter: {
                    'Pessoa.id':idPessoa
                }
            },
            success: function (data) {
                if (data) {
                    criaTabelaContato(JSON.parse(data));
                }
            }
        });
    }

    function criaTabelaContato(contatos) {
        $('.input-group.mb-3').remove();

        primeiroContato = contatos.shift();
        $('#selectTipoContato').val(primeiroContato.tipo ?'email':'telefone');
        $('#inputDescricaoContato').val(primeiroContato.descricao);

        contatos.forEach(function (contato) {
            var selectTipoContato = $('#selectTipoContato').clone();
            var inputDescricaoContato = $('#inputDescricaoContato').clone();
            selectTipoContato.val(contato.tipo ?'email':'telefone');
            inputDescricaoContato.val(contato.descricao);
            
            selectTipoContato.change(function () {
                trataFormatoCampoContato();
            });
    
            var btnRemoverContato = $('<button class="btn btn-outline-danger btn-remover-contato" type="button">Remover</button>');
            var inputGroup = $('<div class="input-group mb-3"></div>');
        
            inputGroup.append(selectTipoContato).append(inputDescricaoContato).append(btnRemoverContato);
            $('#contatosContainer').append(inputGroup);
        
            btnRemoverContato.click(function () {
                inputGroup.remove(); 
            });
        });
    }

    $('#btnExcluir').click(function () {
        // Verifica se uma linha da tabela está selecionada
        var linhaSelecionada = $('#tabelaPessoas').find('tr.selecionada');
        if (linhaSelecionada.length > 0) {
            // Obtém o ID da pessoa da linha selecionada
            var id = linhaSelecionada.find('td:first-child').text();
            var confirmacao = confirm("Tem certeza que deseja excluir esta pessoa?");
            if (confirmacao) {
                excluirPessoa(id);
            }
        } else {
            mostrarAlerta('Por favor, selecione uma pessoa para excluir.', 'warning');
        }
    });
    
    function getContatosPessoa() {
        var contatos = [];
    
        $('.input-group').each(function() {
            var tipoContato = $(this).find('#selectTipoContato').val();
            var descricaoContato = $(this).find('#inputDescricaoContato').val();
    
            if (tipoContato && descricaoContato) {
                contatos.push({
                    tipo: tipoContato,
                    descricao: descricaoContato
                });
            }
        });
    
        return contatos;
    }

    $('#btnAdicionarContato').click(function () {
        // Clonar os elementos e remover os IDs para evitar duplicatas
        var selectTipoContato = $('#selectTipoContato').clone();
        var inputDescricaoContato = $('#inputDescricaoContato').clone();
        selectTipoContato.val('email');
        selectTipoContato.change(function () {
            trataFormatoCampoContato();
        });

        inputDescricaoContato.val('');
    
        var btnRemoverContato = $('<button class="btn btn-outline-danger btn-remover-contato" type="button">Remover</button>');
        var inputGroup = $('<div class="input-group mb-3"></div>');
    
        inputGroup.append(selectTipoContato).append(inputDescricaoContato).append(btnRemoverContato);
        $('#contatosContainer').append(inputGroup);
    
        btnRemoverContato.click(function () {
            inputGroup.remove(); // Remover o grupo de entrada específico ao clicar em Remover
        });
    });
    
    function excluirPessoa(idPessoa) {
        $.ajax({
            url: '/src/Controllers/AjaxController.php/pessoa', // Endpoint para excluir a pessoa
            method: 'DELETE',
            data: JSON.stringify({
                id: idPessoa
            }),
            success: function (response) {
                buscarPessoas($('#inputNome').val()); // Recarrega os dados da tabela
                mostrarAlerta('Pessoa excluída com sucesso!', 'success');
            },
            error: function (xhr, status, error) {
                var errorMessage = "Erro ao excluir a pessoa";
                if (xhr.responseText && JSON.parse(xhr.responseText).error) {
                    errorMessage = JSON.parse(xhr.responseText).error;
                }
                mostrarAlerta(errorMessage, 'danger');
            }
        });
    }
    
    function limparCamposModal() {
        $('#inputIdModal').val('');
        $('#inputNomeModal').val('');
        $('#inputCPFModal').val('');
        $('#inputDescricaoContato').val('');
        $('#selectTipoContato').val('email');
        $('.input-group.mb-3').remove();
        trataFormatoCampoContato();
    }

    $('#btnSalvar').click(function () {
        debugger;
        var id = $('#inputIdModal').val();
        var nome = $('#inputNomeModal').val();
        var cpf = $('#inputCPFModal').val();

        // Validar CPF
        if (!validarCPF(cpf)) {
            exibirAlerta('CPF inválido', 'danger');
            return;
        }

        var metodo = 'POST'; // Método padrão é POST
        var param = {
            data: {
                id: id,
                nome: nome,
                cpf: cpf,
                contato: getContatosPessoa()
            }
        }

        if (id) {
            metodo = 'PUT'; // Altera o método para PUT
            param = JSON.stringify({
                id: id,
                nome: nome,
                cpf: cpf,
                contato: getContatosPessoa()
            });
        }

        // Enviar os dados para o servidor via AJAX
        $.ajax({
            url: '/src/Controllers/AjaxController.php/pessoa',
            method: metodo,
            data: param,
            success: function (response) {
                limparCamposModal();
                mostrarAlerta('Dados salvos com sucesso!', 'success');
            },
            error: function (xhr, status, error) {
                var errorMessage = "Erro ao salvar os dados";
                if (xhr.responseText && JSON.parse(xhr.responseText).error) {
                    errorMessage = JSON.parse(xhr.responseText).error;
                }
                mostrarAlerta(errorMessage, 'danger');
            }
        });
    });
    
    function buscarPessoas(nome) {
        // Limpa a tabela de pessoas
        $('#tabelaPessoas').empty();

        // Faz uma requisição AJAX para buscar as pessoas com base no nome fornecido
        $.ajax({
            url: '/src/Controllers/AjaxController.php/pessoa',
            type: 'GET',
            data: {
                filter: {
                    'nome': nome
                }
            },
            success: function (data) {
                $('#tabelaPessoas').empty();
                if (data) {
                    criaTabelaPessoa(JSON.parse(data));
                }
            }
        });
    }

    function criaTabelaPessoa(data) {
        // Preenche a tabela com os dados retornados
        debugger;
        data.forEach(function (pessoa) {
            var linha = '<tr class="linha-pessoa">' + // Adiciona a classe "linha-pessoa" para cada linha
                '<td>' + (pessoa.id ? pessoa.id : '') + '</td>' +
                '<td>' + (pessoa.nome ? pessoa.nome : '') + '</td>' +
                '<td>' + (pessoa.cpf ? pessoa.cpf : '') + '</td>' +
                '</tr>';
            
            eventLinha = getEventLinha(linha, pessoa.id);
            $('#tabelaPessoas').append(eventLinha); // Adiciona a linha à tabela
        });
    }

    function getEventLinha(linha, id) {
        var elementLinha = $(linha);
        elementLinha.click(function() {
            $('.selecionada').removeClass('selecionada');
            elementLinha.addClass('selecionada');

            $('btnExcluir')
            console.log("Linha selecionada: ID=" + id);
        });

        return elementLinha;
    }

    function trataFormatoCampoContato() {
        $('.input-group').each(function() {
            var tipoContato = $(this).find('#selectTipoContato').val();
            var descricaoContato = $(this).find('#inputDescricaoContato');
    
            debugger;

            if (tipoContato == 'email') {
                descricaoContato.mask('A', {
                    translation: { 'A': { pattern: /[\w@\-.+]/, recursive: true } }
                });
            } else {
                descricaoContato.mask('(00) 0000-0000');
            }
        });
    }

    // Evento de clique no botão de consulta
    $('#btnConsultar').click(function () {
        var nome = $('#inputNome').val(); // Obtém o valor do campo de filtro de nome
        buscarPessoas(nome); // Chama a função para buscar pessoas com base no nome
    });

    // Evento de mudança no campo de filtro de nome
    $('#inputNome').change(function () {
        var nome = $(this).val(); // Obtém o valor do campo de filtro de nome
        buscarPessoas(nome); // Chama a função para buscar pessoas com base no nome
    });

    $('#inputCPFModal').mask('000.000.000-00', { reverse: true });
    $('#inputDescricaoContato').mask('A', {
        translation: { 'A': { pattern: /[\w@\-.+]/, recursive: true } }
    });

});
