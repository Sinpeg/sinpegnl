<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[19]) {
    header("Location:index.php");
}else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
//	$responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
//	require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('modulo/atividades/dao/atividadeextensaoDAO.php');
    require_once('modulo/atividades/classes/atividadeextensao.php');
//	require_once('../../includes/classes/unidade.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $atividadeextensao = array();
    $cont = 0;
    $daoae = new atividadeextensaoDAO();
    $codsub = filter_input(INPUT_GET, 'codunidade', FILTER_DEFAULT);
    $rows_ae = $daoae->buscaaeunidade($codsub, $anobase);
    foreach ($rows_ae as $row) {
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
    $daoae->fechar();
}

if ($cont == 0) {
    Utils::redirect('atividades', 'incluiatividadeextensao');
//	$cadeia = "location:incluiatividadeextensao.php";;
//	header($cadeia);
    //exit();
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

<h3 class="card-title"> Atividades de Extens&atilde;o</h3>
<div class="msg" id="msg"></div>
<table>
    <tr align="center" style="font-style: italic;">
        
        <th>Tipo</th>
        <th>Quantidade</th>
        <th>Participantes</th>
        <th>Pessoas Atendidas</th>
        
    </tr>
    <?php foreach ($atividadeextensao as $at) { ?>
        <tr>
            <td><?php if ($at->getTipo() == 1)
                echo "Programa";
            else
                echo "Projeto";
            ?></td>
            <td align="center"> <?php echo $at->getQuantidade(); ?></td>
            <td align="center"> <?php echo $at->getParticipantes(); ?></td>
            <td align="center"> <?php echo $at->getAtendidas(); ?></td>
            
        </tr>
<?php } ?>
</table>

