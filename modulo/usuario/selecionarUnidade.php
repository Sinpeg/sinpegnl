<?php

$sessao = $_SESSION["sessao"];

if ($sessao->getCategoria() !=0 ) { 
    echo "Formulário indisponível para o seu perfil!";
    die;
}
?>

<div class="bs-example">
    <ul class="breadcrumb">
        <li class="active">Selecionar unidade</li>
    </ul>
</div>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Selecionar Unidade</h3><br/> 
    </div>
    <form class="form-horizontal" id="formMudarUnidade" action="#" method="post">
        <table class="card-body">
            <tr>
                <td class="coluna1">
                    Informe a Unidade
                </td>
            </tr>
            <tr>
                <td class="coluna2">
                    <input class="form-control" type="text" id="cxunidade" name="cxunidade" placeholder="Unidade" 
                    autocomplete="off"/>                     
                    <div id="cxsugestao"></div> 
                </td>
                <td>
                    <button id="selecionar" class="btn btn-info">Selecionar</button>
                </td>                                       
            </tr>
        </table>
        <input class="form-control"type="hidden" name="userID" id="userID" value="<?php echo $sessao->getCodusuario();?>">
        <input class="form-control"type="hidden" name="mudarUnidade" id="mudarUnidade" value="1"></td>
    </form>       
</div>

<script>
    $("#cxunidade").keyup(function(){	
        $.ajax({
        type: "POST",
        url: "ajax/resultadopdi/lerUnidade.php",
        data:'keyword='+$(this).val(),
    /*  beforeSend: function(){
            $("#autocomp").css("background","#FFF url(img/LoaderIcon.gif) no-repeat 165px");
        },*/
        success: function(data){
            $("#cxsugestao").show();
            $("#cxsugestao").html(data);
            $("#cxunidade").css("background","#FFF");
        }
        }) ;

        $("#cxunidade").click(function(){
            $(this).val("");
        });
    });

    $("#selecionar").click(function(){
        var unidade = $('#cxunidade').val();
        var user = $('#userID').val();
        //alert(unidade);

        $.ajax({
            type: "POST",
            url: "ajax/usuario/defUnidade.php",
            data:{nomeUnidade: unidade,userID:user},
            success: function(data){
            alert(data);           
                $( "#formMudarUnidade" ).submit();
                //history.go(0);
            }
        })
    });
</script>

