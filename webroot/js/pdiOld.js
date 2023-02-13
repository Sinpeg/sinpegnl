/**
 * Script com as funcionalidades para o módulo do PDI
 * Universidade Federal do Pará
 * Pró-Reitoria de Planejamento Institucional (PROPLAN)
 * Autor: Diego da Costa
 * e-mail: diego.couto at itec.ufpa.br
 * https://sisraa.ufpa.br
 * 
 */

$("#infSemaforo").blur(function(){
    $("#resposta-semaforo").empty();
    $.ajax({
		type: "POST",
		url: "ajax/resultadopdi/defSemaforo.php",
		data: $("form").serialize(),
		success: function(data){
			$("#resposta-semaforo").html(data);
		}    });

    });



 $("#cxunidade").keyup(function(){
		$.ajax({
		type: "POST",
		url: "ajax/resultadopdi/lerUnidade.php",
		data:'keyword='+$(this).val(),
      /*  beforeSend: function(){
			$("#autocomp").css("background","#FFF url(img/LoaderIcon.gif) no-repeat 165px");
		},*/
		success: function(data){
			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#cxunidade").css("background","#FFF");
		}
		}) ;
    

    $("#cxunidade").click(function(){
        $(this).val("");
    });
 });
 /*  $("#pdi-result-bind").change(function() {
        $("#resposta-ajax").html("");
        $("#resposta-ajax-result").html("");
        $("#resposta-ajax").addClass("ajax-loader");
        $.ajax({
            type: "POST",
            data: $("form").serialize(),
            url: "ajax/resultadopdi/listaindicadores.php",
            success: function(data) {
                $("#resposta-ajax").html(data);
                $("#resposta-ajax").removeClass("ajax-loader");
                $(function() {
                    $("#pdi-result-br").click(function(event) {
                        event.preventDefault();
                        $("#resposta-ajax-result").html("");
                        $("#resposta-ajax-result").addClass("ajax-loader");
                        $.ajax({
                            url: $("form").attr("action"),
                            type: "POST",
                            data: $("form").serialize(),
                            success: function(data) {
                                $("#resposta-ajax-result").html(data);
                                $("#resposta-ajax-result").removeClass("ajax-loader");
                            }
                        });
                    });
                });
            },
            error: function() {
                $("#resposta-ajax").removeClass("ajax-loader");
            }

        });
    });*/


    $("#pdi-result-bind-1").change(function() {
        $("#resposta-ajax").html("");
        $("#resposta-ajax-result").html("");
        $("#resposta-ajax").addClass("ajax-loader");
        $.ajax({
            type: "POST",
            data: $("form").serialize(),
            url: "ajax/resultadopdi/listaindicadoresobj.php",
            success: function(data) {
                $("#resposta-ajax").html(data);
                $("#resposta-ajax").removeClass("ajax-loader");
                $(function() {
                    $("#pdi-result-br").click(function(event) {
                        event.preventDefault();
                        $("#resposta-ajax-result").html("");
                        $("#resposta-ajax-result").addClass("ajax-loader");
                        $.ajax({
                            url: $("form").attr("action"),
                            type: "POST",
                            data: $("form").serialize(),
                            success: function(data) {
                                $("#resposta-ajax-result").html(data);
                                $("#resposta-ajax-result").removeClass("ajax-loader");
                            }
                        });
                    });
                });
            },
            error: function() {
                $("#resposta-ajax").removeClass("ajax-loader");
            }

        });
    });



    $("#pdi-salvar").click(function() {
        $.ajax({
            url: $("form").attr("action"),
            type: $("form").attr("method"),
            data: $("form").serialize(),
            success: function(data) {
                $("#resposta-ajax").html(data);
            },
            error: function(data) {
                $("#resposta-ajax").html(data);
            }
        });
    });

// painel de medições
    $("#documento-painel").change(function() {
    	$("#mapa").empty();
        $.ajax({
            url: "ajax/painelmedicao/buscapainelmed.php",
            type: "POST",
            data: $("form").serialize(),
            success: function(data) {
                $("#mapa").html(data);
            },
        });
    });

    $('input[name=salvar]').click(function() {
        $('div#resultado').empty();
        $.ajax({url: $('form').attr('action'), type: 'POST', data: $('form[name=adicionar]').serialize(), success: function(data) {
                $('div#resultado').html(data);
            }});
    });

    $('#documento').change(function() {
        $('div.indicador').html('<label>Indicador:</label>' + '<select name="indicador" class="sel1">' + '<option value="0">Selecione um indicador...</option>');
        $('div#resultado').empty();
        $.ajax({url: 'ajax/objetivopdi/buscaobjetivo.php', type: 'POST', data: $('form').serialize(), success: function(data) {
                $('div.objetivo').html(data);
            }});
    });

    $('#doc-indicador').change(function() {
        $('div.unidade').empty();
        $.ajax({url: 'ajax/unidade/buscaunidade.php', type: 'POST', data: $('form').serialize(), success: function(data) {
                $('div.unidade').html($(data).filter("#unidades").html());
                $('div.objetivo').html($(data).filter("#objetivo").html());
            }});
    });

    $('div.objetivo').change(function() {
        $('div#resultado').empty();
        $.ajax({url: 'ajax/indicadorpdi/buscaindic.php', type: 'POST', data: $('form').serialize(), success: function(data) {
                $('div.indicador').html(data);
            }});
    });


//funções referentes ao mapaestratégico.


    $('#selectDocument').change(function() {
        $('div#resultado').empty();
        $.ajax({url: "ajax/mapa/geratabelamapa.php", type: 'POST', data:$('form[name=chamaTabela]').serialize(), success: function(result){
            $("div#resultado").html(result);
        }});
    });
 $('#resultado').change(function() {
        $('div#resultado').empty();
        $.ajax({url: "ajax/mapa/geratabelamapa.php", type: 'POST', data:$('form[name=chamaTabela]').serialize(), success: function(result){
            $("div#resultado").html(result);
        }});
    });


    



    $('#selectCalendario').change(function() {
        $('div#resultado').empty();
        $.ajax({url: "ajax/mapa/geratabelamapa.php", type: 'POST', data:$('form[name=chamaTabela]').serialize(), success: function(result){
            $("div#resultado").html(result);
        }});
    });


$('#mostraTelaCadastro').click(function() {
		$('div#resultado').empty();
		$.ajax({url: $('form').attr('action'), type: 'POST', data: $('form[name=adicionar]').serialize(), success: function(data) {
			$('div#resultado').html(data);
		}});
	});


//funções referentes a indicador.
$(function() {
    $('input[name=salvarindicador]').click(function() {
            
    	
		$('div#resultado').empty();
        $.ajax({url: "ajax/indicadorpdi/registraindicador.php", type: 'POST', data:$('form[name=cad-indicador]').serialize(), success: function(data) {
                $('div#resultado').html(data);
            }});
    	
    	});   
});

$(function() {
    $('input[name=adicionar-meta]').click(function() {
            
    	$('div#resultadoexibemeta').empty();
		$('div#resultado').empty();
        $.ajax({url: "ajax/metapdi/registrameta.php", type: 'POST', data:$('form[name=cadastra-meta]').serialize(), success: function(data) {
                $('div#resultado').html(data);
            }});
    	
    	});   
});

$(function() {
    $('input[name=exibir-meta]').click(function() {
            
    	
		$('div#resultadoexibemeta').empty();
        $.ajax({url: "ajax/metapdi/geratabelameta.php", type: 'POST', data:$('form[name=cadastra-meta]').serialize(), success: function(data) {
                $('div#resultadoexibemeta').html(data);
            }});
    	
    	});   
});





