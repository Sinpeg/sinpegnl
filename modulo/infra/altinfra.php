<?php
if (!$aplicacoes[8]) {
    header("location:index.php");
}
else if (!isset($_SESSION["sessao"])) {
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
$daotipoinfra = new TipoinfraDAO();
$daoin = new InfraDAO();
$cont = 1;
$rows_ti = $daotipoinfra->Lista();
foreach ($rows_ti as $row) {
    $tiposti[$cont] = new Tipoinfraestrutura();
    $tiposti[$cont]->setCodigo($row['Codigo']);
    $tiposti[$cont]->setNome($row['Nome']);
    $tiposti[$cont]->setValidade($row['validade']);
    $cont++;
}
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$rows_ti = $daoin->buscainfra($_GET["codin"]);
$conti = 0;
foreach ($rows_ti as $row1) {
    $tipo = $row1['Tipo'];
    foreach ($tiposti as $tipoti) {
        if ($tipoti->getCodigo() == $tipo) {
            $conti++;
            $tiposi = $tipoti;
            $tiposi->criaInfraestrutura($row1["CodInfraestrutura"], $unidade, $row1['AnoAtivacao'], $row1['Nome'], $row1['Sigla'], $row1['HoraInicio'], $row1['HoraFim'], $row1['Adistancia'], $row1['PCD'], $row1['Area'], $row1['Capacidade'], $row1['AnoDesativacao'], $row1['Situacao']);
        }
    }
}
$selecionado1 = "";
$selecionado2 = "";
$selecionado3 = "";
$selecionado4 = "";
$selecionado5 = "";
$selecionado6 = "";
if ($tiposi->getInfra()->getPcd() == "S") {
    $selecionado5 = "checked";
}
if ($tiposi->getInfra()->getAdistancia() == "1") {
    $selecionado1 = "selected";
} elseif ($tiposi->getInfra()->getAdistancia() == "2") {
    $selecionado2 = "selected";
} elseif ($tiposi->getInfra()->getAdistancia() == "3") {
    $selecionado6 = "selected";
}
if ($tiposi->getInfra()->getSituacao() == "A") {
    $selecionado3 = "selected";
} else {
    $selecionado4 = "selected";
}
$daoin->fechar();
// Avaliação do bloqueio
// Subunidade
$lock = new Lock();
if (!$sessao->isUnidade()) {
    $lock->setLocked(Utils::isApproved(8, $codunidadecpga, $codunidade, $anobase));
}
// neste caso avalia se o dado passado pertence à unidade
else {
    $rows_ti = $daoin->buscainfra($_GET["codin"]);
    foreach ($rows_ti as $row) {
        // se o dado não pertencer à unidade 
        // bloqueia a edição
        if ($row["CodUnidade"]!=$codunidade) {
            $lock->setLocked(true);
        }
    }
}
?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
            <li class="active">
                <a href="<?php echo Utils::createLink("infra", "consultainfra"); ?>">Infraestrutura</a>
                <i class="fas fa-long-arrow-alt-right"></i>
                Alterar infraestrutura
            </li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Infraestrutura</h3>
        <div class="msg" id="msg"></div>
    </div>
    <form class="form-horizontal" name="finfra" method="post">
        <input class="form-control"name="codti" type="hidden" value="<?php print $tiposi->getInfra()->getCodinfraestrutura(); ?>" />
        <input class="form-control"name="operacao" type="hidden" value="A" />
        <table class="card-body">
            <tr>
                <td class="coluna1">Situa&ccedil;&atilde;o</td>
            </tr>
            <tr> 
                <td class="coluna2"><select <?php echo $lock->getDisabled(); ?> name="situacao">
                        <option value="A" <?php print $selecionado3; ?>>Ativado</option>
                        <option value="D" <?php print $selecionado4; ?>>Desativado</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="coluna1">Tipo de Infraestrutura</td>
            </tr>
            <tr>
                <td class="coluna2"><select class="custom-select" name="codtinfra" <?php echo $lock->getDisabled(); ?>>
                        <?php
                        foreach ($tiposti as $tti) {
                            if ($tiposi->getCodigo() == $tti->getCodigo()) {
                                ?>
                                <option selected="selected"
                                        value="<?php print $tti->getCodigo(); ?>">
                                    <?php print $tti->getNome(); ?></option>
                            <?php } elseif($tti->getValidade() >= 2021) { ?>
                                <option value="<?php print $tti->getCodigo(); ?>">
                                    <?php print $tti->getNome(); ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="coluna1">Nome</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="npn" maxlength="100" size=80 value="<?php print $tiposi->getInfra()->getNome(); ?>" />
                </td>
            </tr>
            <tr>
                <td class="coluna1">Sigla</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="npa" maxlength="20" size=30 value="<?php print $tiposi->getInfra()->getSigla(); ?>" />
                </td>
            </tr>
            <tr>
                <td class="coluna1">Capacidade/Quantidade</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"<?php echo $lock->getDisabled(); ?> type="text" name="npc" onkeypress='return SomenteNumero(event);' maxlength="4" value="<?php print $tiposi->getInfra()->getCapacidade(); ?>" />
                <a href="#" class="help" data-trigger="hover" data-content='Os tipos de infraestrutura que não possuírem denominação como: salas de aula, salas administrativas, salas de pesquisa, alojamentos, banheiros e depósitos não precisam ser incluídos um a um, no campo Capacidade/Quantidade deve ser incluída a quantidade de cada tipo e no campo área deve ser incluída a área total do tipo de infraestutura. O campo de futebol não precisa de capacidade.' title="Ajuda" ><span class="glyphicon glyphicon-question-sign"></span></a>
                </td>
            </tr>
            <tr>
                <td class="coluna1">&Aacute;rea
                    <input class="form-control" <?php echo $lock->getDisabled(); ?> 
                        type="text" style="width:70px" name="npr" size="4" onchange="mascaradec(this.value);" 
                        maxlength="7" value="<?php $npr = addslashes(str_replace(".", ",", $tiposi->getInfra()->getArea()));
                        print $npr; ?>" 
                    /> m<sup>2</sup>
                    <a href="#" class="help" data-trigger="hover" data-content='O formato válido para o campo Área possui de 1 a 3 casas de números inteiros e duas casas decimais (d,dd ou dd,dd ou ddd,dd).' title="Ajuda" ><span class="glyphicon glyphicon-question-sign"></span></a>
                </td>
            </tr>
            <tr>
                <td class="coluna1">
                    Hor&aacute;rio

                    <input class="form-control" 
                        <?php echo $lock->getDisabled(); ?> type="text" id="hora" name="nphi" style="width:70px;" size="5" 
                        maxlength="5" onkeyup="Mascara_Hora1(this.value)" onkeypress='return SomenteNumero(event);' 
                        value="<?php print $tiposi->getInfra()->getHorainicio() ?>" 
                    /> 

                    &agrave;s   
                                        
                    <input class="form-control" <?php echo $lock->getDisabled(); ?> type="text" style="width:70px" name="nphf" size="5" maxlength="5" 
                        onkeyup="Mascara_Hora2(this.value)" onkeypress='return SomenteNumero(event);' value="<?php print $tiposi->getInfra()->getHorafim(); ?>"
                    /> horas
                </td>
            </tr>
            <tr>
                <th align="left" colspan="2"><input class="form-check-input"<?php echo $lock->getDisabled(); ?> type="checkbox" onchange="teste();" name="pcd[]" <?php print $selecionado5; ?> />Atende ao Discente Portador de Necessidade Especiais
                </th>
            </tr>
            <tr>
                <td class="coluna1">Utiliza&ccedil;&atilde;o</td>
            </tr>
            <tr>
                <td class="coluna2"><select <?php echo $lock->getDisabled(); ?> name="pad">
                        <option value="0">Selecione Forma de Utiliza&ccedil;&atilde;o...</option>
                        <option value="1" <?php print $selecionado1; ?>>Presencial</option>
                        <option value="2" <?php print $selecionado2; ?>>&Agrave; dist&acirc;ncia</option>
                        <option value="3" <?php print $selecionado6; ?>>Presencial e &Agrave; dist&acirc;ncia</option>
                    </select></td>
            </tr>
        </table>
        <table class="card-body">
            <tr>
                <td colspan="2" align="center">
                    <br>
                    <?php if(!$lock->getLocked()){ ?>
                        <input class="form-control"<?php echo $lock->getDisabled(); ?> type="button" onclick="direciona(1);" class="btn btn-info" value="Gravar" />
                    <?php } ?>
                </td>
            </tr>
        </table>
    </form>
</div>