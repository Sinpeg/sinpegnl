//Função para realizar a requisição via ajax para configuração do 
//sistema, abertura e fechamento.
$(function() {
    $("#salvar").click(function(){
        var dados;
        dados = $("#configuracao").serialize();
        // alert(dados);
        $.ajax({
            type:'POST',
            url:$("#configuracao").attr("action"),
            data: dados,
            success: function(response) {
                var token;
                var target;
                var resp;
                // parser
                token = response.charAt(0); // analise do token na resposta
                target = (token=="%")?("#error"):("#resultado_config"); // o alvo para a resposta
                resp = (target=="#error")?(response.substr(1,(response.length-1))):(response);			
                $(target).html(resp);
            // fim do parser
            }
        });
    });
});



$(function() {
    $("#search_unidade").click(function() {
        var unidade;
        var caminho;
        //var oculto;
       
        unidade = $('input:text[name=unidade]').val();        
        caminho = 'ajax/unidade/unidadereport.php';
       	acao = $("#us").attr("action");
        dados = $("#us").serialize();
        $("#resultado").html("");
        $("#modalLoading").css("display","block");
        $.ajax({
               type: 'POST', 
               url: acao,
               data: dados,
               success: function(data) {                       
                       $("#modalLoading").css("display","none");
                       $("#resultado").html(data); // apresenta o resultado na div resultado
                       // disponibiliza para download a planilha
         		       configuretablesorter();
                       $("#relatorioXLS").click(function() {
//                             alert("");
                               $("#xls").submit();
                       });
							
                	    // disponibiliza para download a planilha em versão do anuário
                        $("#anuarioXLS").click(function() {
                              $("#xls_anuario").submit();
                        });
                } // Fim da função do segundo sucess
         });               
       
    });
});



