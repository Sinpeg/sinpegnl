/**
 * Script com as funcionalidades para o módulo do PDI
 * Universidade Federal do Pará
 * Pró-Reitoria de Planejamento Institucional (PROPLAN)
 * Autor: Diego da Costa
 * e-mail: diego.couto at itec.ufpa.br
 * https://sisraa.ufpa.br
 * 
 */
$(function() {
    $("#pdi-result-bind").change(function() {
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
    });
});

$(function() {
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
});

// painel de medições
$(function() {
    $("#documento-painel").change(function() {
        $.ajax({
            url: "ajax/painelmedicao/buscapainelmed.php",
            type: "POST",
            data: $("form").serialize(),
            success: function(data) {
                $("#mapa").html(data);
            },
        });
    });
});

$(function() {
    $('input[name=salvar]').click(function() {
        $('div#resultado').empty();
        $.ajax({url: $('form').attr('action'), type: 'POST', data: $('form[name=adicionar]').serialize(), success: function(data) {
                $('div#resultado').html(data);
            }});
    });
});

$(function() {
    $('#documento').change(function() {
        $('div.indicador').html('<label>Indicador:</label>' + '<select name="indicador" class="sel1">' + '<option value="0">Selecione um indicador...</option>');
        $('div#resultado').empty();
        $.ajax({url: 'ajax/objetivopdi/buscaobjetivo.php', type: 'POST', data: $('form').serialize(), success: function(data) {
                $('div.objetivo').html(data);
            }});
    });
});

$(function() {
    $('#doc-indicador').change(function() {
        $('div.unidade').empty();
        $.ajax({url: 'ajax/unidade/buscaunidade.php', type: 'POST', data: $('form').serialize(), success: function(data) {
                $('div.unidade').html($(data).filter("#unidades").html());
                $('div.objetivo').html($(data).filter("#objetivo").html());
            }});
    });
});

$(function() {
    $('div.objetivo').change(function() {
        $('div#resultado').empty();
        $.ajax({url: 'ajax/indicadorpdi/buscaindic.php', type: 'POST', data: $('form').serialize(), success: function(data) {
                $('div.indicador').html(data);
            }});
    });
});