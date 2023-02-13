//Busca as produções da área da saúde
$(function() {
    $("#buscaprodsaude").click(function() {
        var acao;
        var dados;
        acao = $("#fpsaude4").attr("action");
        dados = $("#fpsaude4").serialize();
        $.ajax({
            type: 'POST',
            data: dados,
            url: acao,
            success: function(html) {
                $("#resultado").html(html);
                configuretablesorter();
                $("#exportar_pdf").click(function() {
                    $("#pdf").submit();
                });
            }
        });

    });
});



//Busca os serviços p/ a referida subunidade selecionada
$(function() {
    $("#subunidade_busca").change(function() {
        var subunidade;
        subunidade = $('select[name=subunidade]').val(); //Obtem o cod da subunidade selecionada        
        $.ajax({
            type: 'POST',
            url: "ajax/prodsaude4/busca.php",
            data: 'subunidade=' + subunidade,
            success: function(html) {
            	//alert(html);
                $('#servico').html(html);
            }
        });
    });
});


//Busca os procedimentos p/ o referido serviço selecionado
$(function() {
    $("#servico").change(function() {
        var subunidade;
        var servico;
        subunidade = $('select[name=subunidade]').val();
        servico = $('select[name=servico]').val();
        $.ajax({
            type: 'POST',
            url: "ajax/prodsaude4/buscap.php",
            data: 'subunidade=' + subunidade + "&servico=" + servico,
            success: function(html) {
                $('#txtHint2').html(html);
            }
        });
    });
});
