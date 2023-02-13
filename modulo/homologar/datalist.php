<?php
if (!$aplicacoes[43]) {
    Error::addErro("Você não possui permissão para acessar a aplicação solicitada!");
    Utils::redirect();
}
?>
<?php
$codsub = filter_input(INPUT_GET, 'codunidade', FILTER_DEFAULT);
$codapp = filter_input(INPUT_GET, 'codapp', FILTER_DEFAULT);
?>
<?php
// Neste trecho informa se o módulo já foi homologado
$arr = DAOFactory::getHomologacaoDAO()->queryByCodSub($codsub);
$ishomolog = false;
for ($i = 0; $i < count($arr); $i++) {
    $row = $arr[$i];
    if ($row->ano == $anobase && $row->codAplicacao == $codapp) {
        $ishomolog = true;
        $data = $row->dataRegistro;
    }
}
?>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
		    <li class="active">
                <a href="<?php echo Utils::createLink("homologar", "listaplicacao"); ?>">Homologar</a>
                <i class="fas fa-long-arrow-alt-right"></i>
                Buscar
            </li>
            <li style="position: absolute; right: 30px;">
                <?php
                    if ($ishomolog) {
                        $timestamp = strtotime($data);
                        echo "Foi registrada homologação no dia " . date('d/m/Y', $timestamp) . " às " . date('H:i:s', $timestamp);
                    }
                ?>
            </li>
		</ul>
	</div>
</head>


<div class="card card-info">
    <div class="subcaixa">
        <div id="resultado">
        </div>

        <?php
        switch ($codapp) {
            // Prêmios
            case "5":
                include_once 'formPremiosList.php';
                break;
            // Micros 
            case "6":
                include_once "formMicrosList.php";
                break;
            // Laboratórios
            case "7":
                include_once "formLaborList.php";
                break;
            // Prédios
            case "8":
                include_once "formPrediosList.php";
                break;
            // Insfraestrutura de Ensino
        // case "9":
            //   include_once 'formInfraEnsList.php';
            //  break;
            // Estrutura de Acessibildade
            case "10":
                include_once 'formEAList.php';
                break;
            // Produção Artística
            case "18":
                include_once 'formProdArtisticaList.php';
                break;
            // Atividades de Extensão
            case "19":
                include_once 'formProjExtlist.php';
                break;    
            // Quadro de pessoal
            case "24":
                include_once 'formQuadroRHlist.php';
                break;
        }
        ?>

        <table class="card-body">
            <tr>
                <td align="center">
                    <form class="form-horizontal" name="homologacao" method="POST"  id="form-homolog">
                        <input type="button" name="btn-homologar" value="Homologar" class="btn btn-info"/>
                        <input class="form-control"type="hidden" name="codapp" value="<?php echo $codapp; ?>"/>
                        <input class="form-control"type="hidden" name="codunidade" value="<?php echo $codsub; ?>"/>
                    </form>
                </td>
            </tr>
        </table>
    </div>
</div>