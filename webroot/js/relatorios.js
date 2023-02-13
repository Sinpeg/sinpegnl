$(document).ready(function() {
    $('.help').popover({
        trigger: "hover",
        placement: "top"
    });
});
$(function() {
    $("#txtUnidade").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "ajax/labor/buscaunidade.php",
                dataType: "json",
                type: 'GET',
                data: {
                    featureClass: "P",
                    style: "full",
                    maxRows: 12,
                    name_startsWith: request.term
                },
                success: function(data) {
                    response($.map(data, function(item) {
                        return {
                            label: item.nome,
                            value: item.nome,
                        }
                    }));
                }
            });
        },
        minLength: 3
    });
});
$.ui.autocomplete.prototype._renderItem = function(ul, item) {
    item.label = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(this.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
    return $("<li></li>")
            .data("item.autocomplete", item)
            .append("<a>" + item.label + "</a>")
            .appendTo(ul);
};
/***
 * @returns
 * Função para renderizar os gráficos com highcharts
 * Última modificação: 16/05/2014 10:42:00
 */
$(function() {
    $("#gerarGrafico").click(function() {
        $("#chart").html("");
        var modulo = $('input[name=modulo]').val(); // módulo 
        url = "ajax/" + modulo + "/relatorioTab.php?" + $('form').serialize();
        $("#chart-erro").html(""); // apaga os erros
        // trecho de configuração do highcharts
        var options = {
            chart: {
                renderTo: 'chart',
                type: 'column',
            },
            title: {
                useHTML: true,
                text: null,
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: [],
            },
            yAxis: {
                title: {
                    text: '',
                    enabled: false
                },
                labels:
                        {
                            formatter: function()
                            {
                                return this.value;
                            }
                        },
                plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }]
            },
            tooltip: {
                formatter: function() {
                    return this.x + ':' + this.y;
                },
                enabled: true,
                animation:true
            },
            formatter: function() {
                return this.value;
            },
            legend: {
                borderWidth: 0
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    showInLegend: true,
                    dataLabels: {
                        enabled:true,
                        distance: -24,
                        color: 'white',
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function() {
                            return Highcharts.numberFormat(this.percentage) + '%';
                        },
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: []
        };
        // Captura os valores do gráfico
        $.getJSON(url, function(json) {
            $("#resultado").html("");
            // gera informações de erro
            if (json["dados"][0]["erro"] !== undefined) {
                $("#chart-erro").html("<div class='alert alert-danger'>" + json["dados"][0]["erro"] + "</div>");
            } else {
                if (json["dados"].length == 2 && $("select[name=tipografico]").val() != "pie") {
                    options.legend.enabled = false;
                }
                else {
                    options.legend.enabled = true;
                }
                // Gráfico de pizza
                if ($("select[name=tipografico]").val() == "pie" && json["dados"].length == 1) {
                    options.series = json["dados"];
                    options.legend.enabled = true;
                    options.tooltip.formatter = function() {
                        return Highcharts.numberFormat(this.percentage) + '%' + ' / '+this.y+'';
                    };
                }
                else {
                    options.xAxis.categories = json["dados"][0]['data'];
                    for (i = 0; i < json["dados"].length - 1; i++) {
                        options.series[i] = json["dados"][i + 1];
                    }
                    options.tooltip.formatter = function() {
                        return this.x + ': ' + this.y;
                    }
                }

            }
            // Configura os demais atributos do gráfico, caso não haja erro
            if (json["dados"][0]["erro"] == undefined) {
                if (json["dados"].length == 2) {
                    if (json["dados"][1]["name"] == "Simple") {
                        options.legend = false;
                    }
                }
                options.title.text = json["configuracoes"].title; // título
                options.subtitle.text = json["configuracoes"].subtitle; // subtítulo
                options.yAxis.allowDecimals = json["configuracoes"].float; // float ou inteiro
                // Exibe o gráfico
                chart = new Highcharts.Chart(options);
            }
        });
    });
});

// busca os tipos associados à categoria do laboratório
$(function() {
    $("select[name=categoria]").change(function() {
        $("#tipo-ajax").html("");
        $("#tipo-ajax").addClass("ajax-loader");
        $.ajax({
            url: "ajax/labor/buscatplab.php",
            type: "POST",
            data: "categoria=" + $("select[name=categoria]").val(),
            success: function(data) {
                $("#tipo-ajax").html(data);
                $("#tipo-ajax").removeClass("ajax-loader");
            }
        });
    });
});
// fim

//Aplicações por grupo
$(function() {
    $("select[name=cad-consulta]").change(function() {
         primeiraf();
    });
    
});

function primeiraf(){
    $("#aplicgrupo").html("");
    $("#aplicgrupo").addClass("ajax-loader");
    $.ajax({
        url: "ajax/usuario/aplicacaogrupo.php",
        type: "POST",
        data: "cad-consulta=" + $("select[name=cad-consulta]").val(),
        success: function(data) {
            $("#aplicgrupo").html(data);
            $("#aplicgrupo").removeClass("ajax-loader");           
            segundaf();
        }
    });
}


//unidades selecionadas
function terceiraf(){
	   $("#select2").html("");
       $("#select2").addClass("ajax-loader");
	$.ajax({
        url: "ajax/usuario/unidgrupo.php",
        type: "POST",
        data: "cad-consulta=" + $("select[name=cad-consulta]").val(),
        success: function(data) {
            $("#select2").html(data);
            $("#select2").removeClass("ajax-loader");
        }
    });
}

//unidades selecionadas
function segundaf(){
	   $("#selectu").html("");//selectu
       $("#selectu").addClass("ajax-loader");
	$.ajax({
        url: "ajax/usuario/subunidades.php",
        type: "POST",
        data: "cad-consulta=" + $("select[name=cad-consulta]").val(),
        success: function(data) {
            $("#selectu").html(data);
            $("#selectu").removeClass("ajax-loader");
            terceiraf();
        }
    });
}





/*
$(document).ready(function(){
    $("#cad-
    consulta").change(function(){
        $("#select2").load("ajax/usuario/unidgrupo.php?grupo="+$("#cad-consulta").val());

    });
    
});
*/
$(document).ready(function(){
	$( "#cad-consulta" )
	.change(function() {
	  $( "select option:selected" ).each(function() {
	       // $("#select2").load("ajax/usuario/unidgrupo.php?grupo="+$("#cad-consulta").val());

	  });
	})
	.trigger( "change" );
	}) 

/*$(document).ready(function(){
	   $("#cad-consulta").change(function(){
	      $.ajax({
	         type: "POST",
	         url: "ajax/usuario/unidgrupo.php",
	         data: {cad-consulta: $("#cad-consulta").val()},
	         dataType: "json",
	         success: function(json){
	            var options = "";
	            $.each(json, function(key, value){
	               options += '<option value="' + key + '">' + value + '</option>';
	            });
	            $("#select2").html(options);
	         }
	      });
	   });
	});*/






// fim

// complementa o formulário
$(function() {
    $("select[name=agrupamento]").change(function() {
        var modulo = $("input[name=modulo]").val(); // módulo 
        url = "ajax/" + modulo + "/completaform.php";
        data = $('form').serialize();
        $("#completaform").html("");
        $("#completaform").addClass("ajax-loader");
        // requisição ajax
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function(data) {
                $("#completaform").html(data);
                $("#completaform").removeClass("ajax-loader");
            }
        });
    });
});
/* 
 * Código para gerar consulta em tela ou excel */
/* Adicionado no dia: 20/05/2014 
 * Desenvolvedor: Diego do Couto
 * */
$(function() {
    $("#gerarTabela").click(function() {
        $("#chart").empty();
        $("#chart-erro").html(""); // apaga os erros
        var url = "ajax/" + $("input[name=modulo]").val() + "/" + $("input[name=acao]").val() + ".php";
        $.ajax({
            type: "GET",
            data: $("#us").serialize(),
            url: url,
            success: function(data) {
//                var txt = data.substring(0,6);
//                alert(txt);
//                if (txt == "erro:") {
//                    $("#resultado").empty();
//                    $("#chart-erro").html("<div class='alert alert-danger'>" + data.substring(7) + "</div>");
//                }
//                else {
                    $("#resultado").html(data);
                    configuretablesorter();
                    $("#reportchart").click(function() {
                        var urlexport = "relatorio/" + $("input[name=modulo]").val() + "/" + "relatorioXLS.php";
                        $("#us").attr("action", urlexport);
                        $("#us").submit();
                    });
                }
//            }
        });
    });
});

