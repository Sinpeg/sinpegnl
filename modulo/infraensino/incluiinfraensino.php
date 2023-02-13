<?php
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[9]) {
    header("Location:index.php");
}  
else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeUnidade();
    $codunidade = $sessao->getCodUnidade();
    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnoBase();
    require_once('dao/infraensinoDAO.php');
    require_once('classes/infraensino.php');
    require_once('dao/tipoinfraensinoDAO.php');
    require_once('classes/tipoinfraensino.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $tiposie = array();
    $cont = 0;
    $daotie = new TipoinfraensinoDAO();
    $daoie = new infraensinoDAO();
    $rows_tie = $daotie->Lista();
    foreach ($rows_tie as $row) {
        $cont++;
        $tiposie[$cont] = new Tipoinfraensino();
        $tiposie[$cont]->setCodigo($row['Codigo']);
        $tiposie[$cont]->setNome($row['Nome']);
    }
    $cont1 = 0;
    $soma = 0;
    $tamanho = count($tiposie);
    $rows_ie = $daoie->buscaieunidade($codunidade, $anobase);
    foreach ($rows_ie as $row) {
        $tipo = $row['Tipo'];
        for ($i = 1; $i <= $tamanho; $i++) {
            if ($tiposie[$i]->getCodigo() == $tipo) {
                $cont1++;
                $tiposie[$i]->criaInfraensino($row["Codigo"], $unidade, $anobase, $row["Quantidade"]);
                $soma += $row["Quantidade"];
            }
        }
    }
    $daoie->fechar();
} 
if ($cont1 > 0) {
    Utils::redirect('infraensino', 'consultainfraensino');
} else {?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active">Infraestrutura de ensino na unidade</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="gravar" id="gravar" method="post" >
    <h3 class="card-title">Infraestrutura de Ensino na Unidade</h3>
    <div class="msg" id="msg"></div>
    <table>
        <tr style="font-style:italic;">
            <th>Itens</th>
            <th>Quantidade</th>
        </tr>
        <tr>
            <td>Aparelho de DVD</td>
            <td><input class="form-control"type="text" name="qtdDVD" size="5" value='' onkeydown="TABEnter();" maxlength="6"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Equipamento de &Aacute;udio</td>
            <td><input class="form-control"type="text" name="qtdAudio" size="5" value='' onkeydown="TABEnter();" maxlength="6"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Equipamento de Climatiza&ccedil;&atilde;o-Ar,Central de Ar,etc.</td>
            <td><input class="form-control"type="text" name="qtdAr" size="5" value='' onkeydown="TABEnter();" maxlength="6"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Equipamentos de Computa&ccedil;&atilde;o</td>
            <td><input class="form-control"type="text" name="qtdPC" size="5" value='' onkeydown="TABEnter();" maxlength="6"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' />
            </td>
        </tr>
        <tr>
            <td>Equipamentos de Videoconferência/Teleconferência</td>
            <td><input class="form-control"type="text" name="qtdVideoconferencia" size="5" value='' onkeydown="TABEnter();" maxlength="6"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Equipamentos Espec&iacute;ficos-Microsc&oacute;pio, Roteador, etc.</td>
            <td><input class="form-control"type="text" name="qtdEspecificos" size="5" value='' onkeydown="TABEnter();" maxlength="6"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Equipamentos Eletr&ocirc;nico-Inform&aacute;ticos</td>
            <td><input class="form-control"type="text" name="qtdEletronico" size="5" value='' onkeydown="TABEnter();" maxlength="6"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>M&oacute;veis Altamente Relevantes</td>
            <td><input class="form-control"type="text" name="qtdMoveis" size="5" value='' onkeydown="TABEnter();" maxlength="6"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Outros Equipamentos Relevantes</td>
            <td><input class="form-control"type="text" name="qtdOutrosequipamentos" size="5"
                       onchange="Soma();" value='' onkeydown="TABEnter();" maxlength="6"
                       onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Projetor Multimidia-Data Show, Projetores,etc.</td>
            <td><input class="form-control"type="text" name="qtdProjetores" size="5" value='' onkeydown="TABEnter();" maxlength="6"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Retroprojetor e Televis&atilde;o</td>
            <td><input class="form-control"type="text" name="qtdTV" size="5" value='' onkeydown="TABEnter();" maxlength="6"
                       onchange="Soma();" onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr>
            <td>Inova&ccedil;&otilde;es Tecnol&oacute;gicas Significativas</td>
            <td><input class="form-control"type="text" name="qtdInovacoes" onchange="Soma();" onkeydown="TABEnter();" maxlength="6"
                       size="5" value='' onkeypress='return SomenteNumero(event)' /></td>
        </tr>
        <tr style="font-style:italic;">
            <td>Total geral</td>
            <td><b id='totalgeral'></b></td>
        </tr>
    </table>
    <input class="form-control"name="operacao" type="hidden" value="I" /> <input type="button" class="btn btn-info" onclick='direciona(1);' value="Gravar" />
</form>
<?php } ?>