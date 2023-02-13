/* inclui a estrutura dos polos */
$(function() {
    $("#gravar-epolo").click(function() {
        $.ajax({
            url: "ajax/polo/poloajax.php",
            type: "POST",
            data: $("form[name=form-incluipolo]").serialize(),
            success: function(data) {
                /* registra a estrutura do polo */
            	// $("#resultado").html(data);
            	//alert(data);
            	window.location.href = "?modulo=epolo&acao=consultapolo"
            }
        });
    });
});
/*Fim*/

/* direciona para a página de edição dos dados */
$(function() {
    $("#alterard-epolo").click(function() {        
            	window.location.href = "?modulo=epolo&acao=altpolo"         
    });
});
/*Fim*/ 

/* inclui a estrutura dos polos */
$(function() {
    $("#alterar-epolo").click(function() {
        $.ajax({
            url: "ajax/polo/poloajax.php",
            type: "POST",
            data: $("form[name=form-alterarpolo]").serialize(),
            success: function(data) {
                /* registra a estrutura do polo */
            	// $("#resultado").html(data);
            	//alert(data);
            	window.location.href = "?modulo=epolo&acao=consultapolo"
            }
        });
    });
});
/*Fim*/