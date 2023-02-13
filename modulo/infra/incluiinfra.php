<?php
if (!isset($_SESSION["sessao"])) {
    header("location:index.php");
}
$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();
require_once('dao/infraDAO.php');
require_once('classes/infraestrutura.php');
require_once('dao/tipoinfraDAO.php');
require_once('classes/tipoinfraestrutura.php');
$tiposti = array();
$cont = 1;
$daotipoinfra = new TipoinfraDAO();
$daoin = new InfraDAO();
$rows_tin = $daotipoinfra->ListaIncluir();
foreach ($rows_tin as $row) {
    $tiposti[$cont] = new Tipoinfraestrutura();
    $tiposti[$cont]->setCodigo($row['Codigo']);
    $tiposti[$cont]->setNome($row['Nome']);
    $cont++;
}
$conttin = 0;
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$conti = 0;
$rows_ti = $daoin->buscainfraunidade($codunidade);
foreach ($rows_ti as $row1) {
    $tipo = $row1['Tipo'];
    foreach ($tiposti as $tipoti) {
        if ($tipoti->getCodigo() == $tipo) {
            $conti++;
            $tiposi[$conti] = $tipoti;
            $tiposi[$conti]->adicionaItemInfraestrutura($row1["CodInfraestrutura"], $unidade, $row1['AnoAtivacao'], $row1['Nome'], $row1['Sigla'], $row1['HoraInicio'], $row1['HoraFim'], $row1['Adistancia'], $row1['PCD'], $row1['Area'], $row1['Capacidade'], $row1['AnoDesativacao'], $row1['Situacao']);
        }
    }
}
$daoin->fechar();
?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li class="active"><a href="<?php echo Utils::createLink("infra", "consultainfra"); ?>">Infraestrutura</a>
                <i class="fas fa-long-arrow-alt-right"></i>
			    Incluir
            </li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Infraestrutura </h3>
    </div>
    <form class="form-horizontal" name="finfra" method="post">
        <input class="form-control"name="operacao" type="hidden" value="I" />
        <div class="msg" id="msg"></div>
        <table class="card-body">
            <tr>
                <td class="coluna1">Tipos de Infraestrutura</td>
            </tr>
            <tr>
                <td class="coluna2"><select class="custom-select" name="codtinfra">
                        <option value="0">Selecione um tipo...</option>
                        <?php
                        foreach ($tiposti as $tti) {
                            ?>
                            <option value="<?php print $tti->getCodigo(); ?>">
                                <?php print $tti->getNome(); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="coluna1">Nome</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" name="npn" maxlength="100" size=80 /></td>
            </tr>
            <tr>
                <td class="coluna1">Sigla</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" style="width:200px;" name="npa" maxlength="20" size=30 /></td>
            </tr>
            <tr>
                <td class="coluna1">Capacidade/Quantidade</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" name="npc" maxlength="4"
                        onkeypress='return SomenteNumero(event);' size="4" /></td>
            </tr>
            <tr>
                <td class="coluna2">
                    &Aacute;rea
                    <input class="form-control"type="text" style="width:70px" name="npr" size="4" maxlength="7" value=""  /> m<sup>2</sup>
                </td>
            </tr>
            <tr>
                <td class="coluna2">Hor&aacute;rio<input class="form-control"name="nphi" type="text" id="nphi"
                        onkeyup="Mascara_Hora1(this.value)"  style="width:70px" size="5" maxlength="5" /> &agrave; <input
                        type="text" name="nphf" id="nphf"
                        onkeyup="Mascara_Hora2(this.value)"  style="width:70px" size="5" maxlength="5" />horas
                </td>
            </tr>
            <tr>
                <th align="left"><input class="form-check-input"type="checkbox" value="1" style="font-weight: normal;"
                                                    name="pcd[]" id="pcd" style="font-weight: normal;" />Atende ao Discente Portador de
                    Necessidade Especiais
                </th>
            </tr>
            <tr>
                <td class="coluna1">Utiliza&ccedil;&atilde;o</td>
            </tr>
            <tr>
                <td class="coluna2"><select class="custom-select" name="pad">
                        <option value="0">Selecione forma de utiliza&ccedil;&atilde;o...</option>
                        <option value="1">Presencial</option>
                        <option value="2">&Agrave; dist&acirc;ncia</option>
                        <option value="3">Presencial e &Agrave; dist&acirc;ncia</option>
                    </select></td>
            </tr>
        </table>
        <table class="card-body">
            <tr>
                <td colspan="2" align="center">
                    <br>
                    <input type="button" id="gravar" onclick="direciona(1);" class="btn btn-info" value="Gravar" />
                </td>
            </tr>
        </table>
    </form>
</div>