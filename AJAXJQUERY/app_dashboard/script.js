$(document).ready(() => {
    $('#documentacao').click(()=>{
        // $('#pagina').load('documentacao.html')
        $.get('documentacao.html', data =>{
            $('#pagina').html(data)
        })
    })
    $('#suporte').click(()=>{
        $.post('suporte.html', data =>{
            $('#pagina').html(data)
        })
        // $('#pagina').load('suporte.html')
    })

    $('#competencia').on('change', e =>{
        let valor = $(e.target).val();
        $.ajax({
            url: 'app.php',
            type: 'GET',
            data: `competencia=${valor}`,
            dataType: 'json',
            success: dados =>{
                $('#numeroVendas').html(dados.numero_vendas)
                $('#totalVendas').html(dados.totalVendas)
            },
            error: erro =>{
                console.log('deu erro'+erro)
            }
        })
    })
})