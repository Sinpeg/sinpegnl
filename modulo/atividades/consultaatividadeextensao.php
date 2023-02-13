<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[19] || !(($codunidade==971) || ($codunidade==1001) || ($codunidade==1004) || ($codunidade==201)) ) {
    header("Location:index.php");
}else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('dao/atividadeextensaoDAO.php');
    require_once('classes/atividadeextensao.php');
//	require_once('../../includes/classes/unidade.php');
    $lock = new Lock();
    $unidade = new Unidade();
    $daounid = new UnidadeDAO();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $atividadeextensao = array();
    $cont = 0;
    $daoae = new atividadeextensaoDAO();
    
    $rowscodsup = $daounid->RetornaCodUnidadeSuperior($cpga);
    foreach ($rowscodsup as $row)
    {
    	$codunidadesup = $row['CodUnidade'];
    }
    
    if (!$sessao->isUnidade()) {
    	// verifica se a subunidade possui homologação
    	$lock->setLocked(Utils::isApproved(19, $codunidadesup, $codunidade, $anobase));  }
    	 
    	for ($i = 0; $i < count($array_codunidade); $i++) {
    		 
    		$rows_ae = $daoae->buscaaeunidade($array_codunidade[$i], $anobase);
    
       foreach ($rows_ae as $row) 
         {
    			 
    	if ($sessao->isUnidade() && $array_codunidade[$i] != $codunidade && $rows->rowCount() > 0) {
    				$lock->setLocked(true); }
    
        $cont++;
        $atividadeextensao[$cont] = new Atividadeextensao();
        $atividadeextensao[$cont]->setCodigo($row['Codigo']);
        $atividadeextensao[$cont]->setTipo($row['Tipo']);
        $atividadeextensao[$cont]->setSubunidade($row['CodSubunidade']);
        $atividadeextensao[$cont]->setQuantidade($row['Quantidade']);
        $atividadeextensao[$cont]->setParticipantes($row['Participantes']);
        $atividadeextensao[$cont]->setAtendidas($row['PesAtendidas']);
        $atividadeextensao[$cont]->setAno($row['Ano']);
       
        }
    }
 
 $daoae->fechar();

 if ($cont == 0) {
 	Utils::redirect('atividades', 'incluiatividadeextensao');
 	//	$cadeia = "location:incluiatividadeextensao.php";;
 	//	header($cadeia);
 	//exit();
 }

}
//ob_end_flush();
?>



<script type="text/javascript">
    function direciona(botao) {
        if (botao == 1) {
            if (document.ea.registros.value == 4)
                document.getElementById('msg').innerHTML = "O número máximo de registros é 4.";
            else {
                document.getElementById('ea').action = "<?php echo Utils::createLink('atividades', 'incluiatividadeextensao'); ?>";
                document.getElementById('ea').submit();
            }
        }
    }
</script>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("atividades", "incluiatividadeextensao"); ?>" >Atividades de extens&atilde;o</a></li>
		    <li class="active">Consulta</li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"> Atividades de Extens&atilde;o</h3>
    </div>
    <form class="form-horizontal" name="ea" id="ea" method="post" />
        <div class="msg" id="msg"></div>
        <table>
            <tr align="center" style="font-style: italic;">
                <th>Subunidade</th>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Participantes</th>
                <th>Pessoas Atendidas</th>
                <th>Alterar</th>
                <th>Excluir</th>
            </tr>
            <?php foreach ($atividadeextensao as $at) { ?>
                <tr><td>
                        <?php
                        if ($at->getSubunidade() == 1001)
                            echo "Escola de M&uacute;sica";
                        else
                            echo "Escola de Teatro e Dan&ccedil;a";
                        ?></td>
                    <td><?php if ($at->getTipo() == 1)
                        echo "Programa";
                    else
                        echo "Projeto";
                    ?></td>
                    <td align="center"> <?php echo $at->getQuantidade(); ?></td>
                    <td align="center"> <?php echo $at->getParticipantes(); ?></td>
                    <td align="center"> <?php echo $at->getAtendidas(); ?></td>
                    <?php if (!$lock->getLocked()){ ?>
                    <td align="center"><a href="<?php echo Utils::createLink('atividades', 'alteraatividadeextensao', array('codigo'=>$at->getCodigo()));?>"target=_self >
                            <img src=webroot/img/editar.gif alt=Alterar width=19 height=19 /></a></td>
                    <?php } ?>
                            
                    <?php if (!$lock->getLocked()){ ?>
                    <td align="center"><a href="<?php echo Utils::createLink('atividades','opatividadeextensao', array('codigo'=>$at->getCodigo(),'op'=>"excluir"));?>" target=_self >
                                <img src=webroot/img/delete.png alt=Excluir width=19 height=19 /></a></td>
                    <?php } ?>
            </tr>
        <?php } ?>
        </table>
        <input class="form-control"type="text" name="registros" readonly="readonly" size="1"
            value="<?php echo $cont; ?>" style="border: none;background-color: transparent" />registros
        <br />
        <?php if (!$lock->getLocked()){ ?>
            <?php if (!$sessao->isUnidade() && $cont>=2) { } else {?>   
                <table>
                    <tr>
                        <td colspan="2" align="center">
                            <input type="button" value="Incluir Nova Atividade" onclick="direciona(1);" />
                        </td>
                    </tr>
                </table>
            <?php } ?>
        <?php } ?>
    </form>
</div>