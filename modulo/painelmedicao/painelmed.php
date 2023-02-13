<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

/* Sessão utilizada para esta aplicação é a PDI_PAINEL */
//session_start();
$sessao = $_SESSION['sessao'];

if (!isset($sessao)) {
	exit();
}

$unidade = new Unidade();
$unidade->setCodunidade($sessao->getCodUnidade());
$codunidade=$unidade->getCodunidade();
$anogestao=$sessao->getAnobase();

// var_dump($_POST);
?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Painel de medições</li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Painel de Medições</h3>
    </div>
    <form class="form-horizontal" name="pdi-painelres" id="pdi-painelres">
        <table class="card-body">
            <tr>
                <td class="coluna1">Documento</td>
            </tr>
            <tr>
                <td class="coluna2">
                    <select class="custom-select" name="coddocumento"  class="sel1 form-control">
                        <option value="0">Selecione o documento...</option>
                        <?php
                        $daodoc = new DocumentoDAO();
                                $docufpa = $daodoc->buscadocumentoPrazo($anogestao);
                        ?>
                        
                        <?php foreach ($docufpa as $row){ ?>
                                <?php $ano = $row['anoinicial']; ?>
                                <option value=<?php print $row["codigo"]; ?>><?php print $row['nome'] ?><?php print ' (' . $row['anoinicial'] . '-' . $row['anofinal'] . ')'; ?></option>
                        <?php }
                        
                        ?>
                    </select>
                </td>
                <td>
                    <input class="btn btn-info" type="submit"  value="Buscar" id="resposta-ajax-doc1" class="btn btn-info btn" /> 
                </td>
            </tr>  
        </table>
    
        <div id="mapa"></div>
    </form>
</div>



<script>
$('#resposta-ajax-doc1').click(function(event) {
    event.preventDefault();
    $("#mapa").html("");
    $("#mapa").addClass("ajax-loader");
    $.ajax({
        url: 'ajax/painelmedicao/buscapainelmed.php',
        type: "POST",
        data: $("form").serialize(),
        success: function(data) {
            $("#mapa").html(data);
            $("#mapa").removeClass("ajax-loader");
        }
    });
});

</script>
