<?php
if (!$aplicacoes[43]) {
    Error::addErro("Você não possui permissão para acessar a aplicação solicitada!");
    Utils::redirect();
}

// Captura o código da unidade
$codUnidade = $sessao->getCodUnidade();
$daoun = new UnidadeDAO();
$rows = $daoun->unidadeporcodigo($codUnidade);
foreach ($rows as $row) {
    $id_unidade = $row["id_unidade"]; // id da unidade responsável
}
$rows1 = $daoun->queryByUnidadeResponsavel($id_unidade);
?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Homologar</li>
		</ul>
	</div>
</head>

<div class="card card-info"> 
    <div class="card-header">
        <h3 class="card-title">Formulários para homologação</h3>
    </div>       
    <form class="form-horizontal" name="busca-forms" method="POST">
        <table class="card-body">
            <tr>
                <td class="coluna1" style="width: 15%;">Subunidade</td>
                <td class="coluna2">
                    <select class="custom-select" name="subunidades">
                        <option value="0">--selecione a subunidade--</option> 
                        <?php foreach ($rows1 as $row) { ?>
                            <option value="<?php echo $row["CodUnidade"]; ?>"><?php echo $row["NomeUnidade"]; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td>
                    <input type="button" value="Buscar" id="buscar-homologacao" class="btn btn-info" />
                </td>
            </tr>
        </table>

        <tabela>
            <div id="tabela">
                <div id="resultado-homologar"></div>
            </div>
        </tabela>
    </form>  
</div>