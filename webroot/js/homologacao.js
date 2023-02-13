/* Busca os formulários para homologação */
$(function() {
    $("#buscar-homologacao").click(function() {
        $.ajax({
            url: "ajax/homologar/buscaforms.php",
            type: "POST",
            data: $("form[name=busca-forms]").serialize(),
            success: function(data) {
                $("#resultado-homologar").html(data);
                $(function() {
                    $("a.novahomolog").click(function() { // solicitação da nova homologação 
                        var id = $(this).children().attr("id"); // id
                        var url = $(this).attr("href");
                        $.ajax({
                            url: "ajax/homologar/solicitacao.php",
                            type: "POST",
                            data: url,
                            success: function(data) {
                                // se bloquear na requisição ajax
                                $("#" + id).attr("src", "webroot/img/lock.png");
                            }
                       });
                    });
                });
            }
        });
    });
});
/* Fim */

/* Cadastro da homologação de cada formulário */
$(function() {
    $("#form-homolog").click(function() {
        $.ajax({
            url: "ajax/homologar/homologacao.php",
            type: "POST",
            data: $("form[name=homologacao]").serialize(),
            success: function(data) {
                /* registra a homologacao do formulario */
                $("#resultado").html(data);
            }
        });
    });
});
/* Fim */

/* Reversão da homologação de cada formulário */
$(function(){
   $("a.reversao").click(function(){ 
      var data = $(this).attr("href");
       $.ajax({
          url:"ajax/homologar/reverter.php", // consulta
          type: "POST",
          data:data,
          success: function(data) {
             $("#result-reversao").html(data); 
          }
      });
   });
});

