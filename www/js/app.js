$(document).ready(function () {
    $('.custom-alert').each(function () {
        var timeout = $(this).data('timeout') * 1000;
        if (timeout > 0) {
            $(this).delay(timeout).fadeOut();
        }
    });
});

$('#file-button').on('click', function () {
    $('#file-input').click();
});

$('#file-input').on('change', function () {
    var fileName = $(this).val().split('\\').pop();
    $('#file-button').html(fileName ? '<i class="bi bi-check-circle"></i> Ok!' : 'Nada foi enviado');
});


$('button[name="btn_apagar"]').on('click', function (event) {
    const confirmacao = confirm("Tem certeza que deseja apagar esse registro?");
    if (!confirmacao) {
        event.preventDefault();
    }
});

$('button[name="btn_salvar"]').on('click', function (event) {
    const confirmacao = confirm("Tem certeza que deseja inserir esse registro?");
    if (!confirmacao) {
        event.preventDefault();
    }
});

$('button[name="btn_login"]').on('click', function (event) {
    const confirmacao = confirm("Seu cadastro será enviada para ativação.");
    if (!confirmacao) {
        event.preventDefault();
    }
});


$('button[name="btn_atualizar"]').on('click', function (event) {
    const confirmacao = confirm("Tem certeza que deseja atualizar esse registro?");
    if (!confirmacao) {
        event.preventDefault();
    }
});

$('button[name="btn_apagar_arquivo"]').on('click', function (event) {
    const confirmacao = confirm("Tem certeza que deseja apagar esse arquivo?");
    if (!confirmacao) {
        event.preventDefault();
    }
});

$('button[name="btn_upload"]').on('click', function (event) {
    const confirmacao = confirm("Tem certeza que deseja salvar esse arquivo?");
    if (!confirmacao) {
        event.preventDefault();
    }
});

$('#btn-sair').on('click', function(event) {
    event.preventDefault(); // Impede a navegação imediata

    // Exibe a mensagem de confirmação
    if (confirm('Tem certeza que deseja sair?')) {
        window.location.href = $(this).attr('href'); // Redireciona se confirmado
    }
});
