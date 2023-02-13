<?php
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[26] && !($codunidade==971)) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
//    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
//    $aplicacoes = $_SESSION["sessao"]->getAplicacoes();
//    if (!$aplicacoes[26]) {
////        $mensagem = urlencode(" ");
////        $cadeia = "location:../saida/erro.php?codigo=2&mensagem=" . $mensagem;;
////        header($cadeia);
////        exit();
//    }
//    require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('dao/edprofissionallivreDAO.php');
    require_once('classes/edprofissionallivre.php');
    require_once('classes/tdmedprofissionallivre.php');
    require_once('dao/tdmedprofissionallivreDAO.php');
//    require_once('../../includes/classes/unidade.php');
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);

    $tipos = array();
    $daot = new TdmedprofissionallivreDAO();
    $dao = new EdprofissionallivreDAO();
    $cont = 0;
    $rowst = $daot->buscaporunidade($codunidade);

    foreach ($rowst as $row) {
        $cont++;
        $tipos[$cont] = new Tdmedprofissionallivre();
        $tipos[$cont]->setCodigo($row['Codigo']);
        $tipos[$cont]->setCategoria($row['Categoria']);
    }
    $tamanho = count($tipos);
    $cont1 = 0;
    $rows = $dao->buscaeduc($anobase);
    foreach ($rows as $row) {
        $tipo = $row['Categoria'];
        for ($i = 1; $i <= $tamanho; $i++) {
            if ($tipos[$i]->getCodigo() == $tipo) {
                $cont1++;
                $tipos[$i]->adicionaItemEdprofl($row['Codigo'], $row['NomeCurso']
                        , $row['Ingressantes1'], $row['Ingressantes2'], $row['Matriculados1'], $row['Matriculados2'], $row['Aprovados1'], $row['Aprovados2'], $row['Concluintes1'], $row['Concluintes2'], $row['Ano']);
            }
        }
    }
    if ($cont1 == 0) {
        Utils::redirect('cledprofissional', 'inccleducprof');
//        header("location:inccleducprof.php");
//        exit();
    }
//    ob_end_flush();
}
?>
<script type="text/javascript">
    function direciona() {
        document.ea.action = "<?php echo Utils::createLink('cledprofissional','inccleducprof'); ?>";
        document.ea.submit();
    }
</script>
<?php echo Utils::deleteModal('SisRAA', 'Você deseja remover o item selecionado?'); ?>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
			<li><a href="<?php echo Utils::createLink("cledprofissional", "inccleducprof"); ?>">Ed. profissional e cursos livres</a>
			<li class="active">Consulta</li>
		</ul>
	</div>
</head>
<form class="form-horizontal" name="ea" id="ea" method="post">
    <h3 class="card-title">Educa&ccedil;&atilde;o Profissional e Cursos Livres</h3>
    <table>
        <tr>
            <td>Categoria</td>
            <td>Nome do curso</td>
            <td>Ingr.</td>
            <td>Matr.</td>
            <td>Apr.</td>
            <td>Conc.</td>
            <td>Alterar</td>
            <td>Excluir</td>
        </tr>
        <?php
        for ($i = 1; $i <= $tamanho; $i++) {
            $tamanho1 = count($tipos[$i]->getEdproflivres());
            if ($tamanho1 != 0) {
                ?>
                <tr>
                    <th align="left" rowspan="<?php echo $tamanho1; ?>"><?php print ($tipos[$i]->getCategoria()); ?></th>
                    <?php foreach ($tipos[$i]->getEdproflivres()as $a) { ?>
                        <td><?php print ($a->getNomecurso()); ?></td>
                        <td><?php print $a->getIngressantes1() + $a->getIngressantes2(); ?></td>
                        <td><?php print $a->getMatriculados1() + $a->getMatriculados2(); ?></td>
                        <td><?php print $a->getAprovados1() + $a->getAprovados2(); ?></td>
                        <td><?php print $a->getConcluintes1() + $a->getConcluintes2(); ?></td>
                        <td align="center">
                            <a href="<?php echo Utils::createLink('cledprofissional', 'altcledprof', array('codigo'=>$a->getCodigo())); ?>"
                               target="_self" ><img src="webroot/img/editar.gif" alt="Alterar" width="19" height="19" /></a>
                        </td>
                        <td align="center">
                            <a href="<?php echo Utils::createLink('cledprofissional', 'delcledprof', array('codigo'=>$a->getCodigo())); ?>" class="delete-link"
                               target="_self" ><img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /></a>
                        </td></tr>
                    <?php
                }
                ?>
                <?php
            }
        }
        ?>
    </table>
    <input type="button" onclick="direciona(1);" value="Incluir" />