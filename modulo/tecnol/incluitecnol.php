<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[11]) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
//    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();

    if (isset($_GET['codcurso'])) {
        $codcurso = $_GET['codcurso'];
    } else {

        $codcurso = addslashes($_POST["codcurso"]);
    }
    if (is_string($codcurso)) {
//        require_once('../../includes/dao/PDOConnectionFactory.php');
        // var_dump($usuario);
//        require_once('../../includes/dao/cursoDAO.php');
//        require_once('../../includes/classes/curso.php');;

        require_once('dao/tptecnassistDAO.php');
        require_once('classes/tipotecnologiassistiva.php');

        require_once('dao/tecnassistivaDAO.php');
        require_once('classes/tecnologiassistiva.php');
//        require_once('../../includes/classes/unidade.php');
        $unidade = new Unidade();
        $unidade->setCodunidade($codunidade);
        $unidade->setNomeunidade($nomeunidade);
		
        $glossario = array("Recursos pedagógicos que permitem ou facilitam o aprendizado de pessoas com deficiência visual", //tatil
        "Materiais gravados com voz humana em diferentes mídias que possibilitam o acesso a diversos conteúdos às pessoas cegas e com baixa visão. Exemplo: áudio livro", //audio
        "Material que é feito por meio do Sistema Braille que são sinais formados através de combinações de seis pontos e permite a pessoas com deficiência visual ler e escrever", //Braille
        "Material impresso em caracteres maiores que o usual a fim de possibilitar o acesso a pessoas com deficiências visuais", //impresso ampliado
        "Profissional especializado em serviços de tradução/ interpretação, que possam intermediar informações, entre surdos e ouvintes", //Guia interprete
        "Oferecimento  da  matéria  língua brasileira de sinais pelo curso", //Curso de libras
        "Materiais didáticos digitais  com  reprodução  em  áudio,  com  sincronização de  trechos  selecionados, e  que  permitam: ler em  caracteres  ampliados, anexar anotações aos arquivos do livro e exportar o texto para impressão em Braille", //Material digital
        "Material didático elaborado em língua brasileira de sinais para o ensino de surdos", //Material em libras
        "Recursos com a finalidade de possibilitar a interação de pessoas com diferentes graus de comprometimento motor e/ou de comunicação e linguagem, em processos de ensino e aprendizagem. Exemplos: tela sensível ao toque, ou ao sopro, detector de ruídos, programas especiais de computador, etc.", // Informatica acessivel
        "Recursos  que  possibilitam  a  eliminação  de  barreiras  na disponibilidade  de  comunicação,  tanto  de  conteúdo  quanto  de  apresentação  da  informação, permitindo que o aluno tenha acesso à informação e ao conhecimento, independentemente de sua limitação. Exemplo: lupas, prancha de comunicação, softwares de leitura, dentre outros.", //Acessibilidade informatica
        "Material  didático com  características  de  fonte, corpo, número de caracteres, entrelinhas, espaços entre as palavras e as letras, cor do papel e da tinta, opacidade do papel e das ilustrações que viabilizem sua utilização com autonomia por parte da pessoa com baixa visão"); //Material impresso acessivel
        
        for($i=0; $i<11; $i++) $vett[$i] = "<a href='#' class='help' data-trigger='hover' data-content='".$glossario[$i]."' title='".$glossario[$i]."'><span class='glyphicon glyphicon-question-sign'> </span></a>";
        
        $tiposta = array();
        $cont = 0;
        $daotipotecno = new TptecnassistDAO();
        $daota = new TecnassistivaDAO();
        $daocur = new CursoDAO();
        $rows_tta = $daotipotecno->tiponaoinserido($codcurso, $anobase);

        foreach ($rows_tta as $row) {
            $tiposta[$cont] = new Tipotecnologiassistiva();
            $tiposta[$cont]->setCodigo($row['Codigo']);
            $tiposta[$cont]->setNome($row['Nome']);
            $cont++;
        }
        $cont1 = 0;
        $rows_cur = $daocur->buscacurso($codcurso);
        $conttacurso = 0;
        foreach ($rows_cur as $row) {
            $curso = $unidade->criaCurso($row['CodCampus'], $row['CodCursoSis'], $row['CodCurso'], $row['NomeCurso'], $row['DataInicio'], $row['CodEmec']);
        }

        $daota->fechar();
    }
}
?>

<script language="javascript">
    function direciona(botao) {
        switch (botao) {
            case 1:				
                document.getElementById("tecA").action = "?modulo=tecnol&acao=optecnol";                
                document.getElementById("tecA").submit();

                break;
            case 2:
                document.forms[0].action = "../saida/saida.php";
                document.forms[0].submit();
                break;
        }

    }
</script>
<head>
	<div class="bs-example">
		<ul class="breadcrumb">
            <li class="active">
                <a href="<?php echo Utils::createLink("tecnol", "cursounidades"); ?>">Tecnologia assistiva</a>
                <i class="fas fa-long-arrow-alt-right"></i>
			    Incluir
            </li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Tecnologia Assistiva
                <a href="#" class="help" data-trigger="hover" 
                    data-content="" title="Recursos e Serviços que 
                    contribuem para proporcionar ou ampliar habilidades 
                    funcionais de pessoas com deficiência e consequentemente
                    promover Vida Independente e Inclusão">
                    <span class="glyphicon glyphicon-question-sign"> </span>
                </a>
        </h3>
    </div>
    <form class="form-horizontal" name="teca" id="tecA" method="POST">
        <div class="card-body">
            Curso:
            <?php print $curso->getNomecurso(); ?>
            <input class="form-control"name="codcurso" type="hidden" value=<?php print $codcurso; ?> />
            <input class="form-control"name="operacao" type="hidden" value="I" /> <input
                name="nomecurso" type="hidden"
                value="<?php print $curso->getNomecurso() ?>" />
        </div>
        
        <div class="card-body">
            <table width="400px" class="table table-bordered table-hover">
                <tr style="font-style:italic;">
                    <td>Itens</td>
                    <td>Possui</td>
                </tr>
                <?php foreach($tiposta as $tpp) {?>
                <tr>
                    <td> <?php print ($tpp->getNome()." ");
                    switch($tpp->getNome()){
                        case "Material pedagógico tátil (adaptação em alto-relevo, de gráficos, gravuras e ilustrações)":
                            echo $vett[0];
                            break;
                            
                        case "Material em áudio":
                            echo $vett[1];
                            break;
                            
                        case "Material em Braile":
                            echo $vett[2];
                            break;
                            
                        case "Material em formato impresso em caracter ampliado":
                            echo $vett[3];
                            break;
                            
                        case "Guia - Intérprete":
                            echo $vett[4];
                            break;
                            
                        case "Inserção da disciplina de língua brasileira de sinais no curso":
                            echo $vett[5];
                            break;
                            
                        case "Material didático digital acessível":
                            echo $vett[6];
                            break;
                            
                        case "Material didático em língua brasileira de sinais":
                            echo $vett[7];
                            break;
                            
                        case "Recursos de informática acessível":
                            echo $vett[8];
                            break;
                            
                        case "Recursos de acessibilidade à comunicação":
                            echo $vett[9];
                            break;
                            
                        case "Tradutor e intérprete de língua brasileira de sinais":
                            echo $vett[4];
                            break;
                            
                        case "Material didático em formato impresso acessível":
                            echo $vett[10];
                            break;
                            }?></td>
                    <td align="center"><input class="form-check-input" type="checkbox" name="<?php print $tpp->getCodigo();?>" />
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
        <table class="card-body">
            <tr>
                <td align="center">
                    <br>
                    <input type="button" onclick="direciona(1);" value="Gravar" class="btn btn-info" />
                </td>
            </tr>
        </table>

    </form>
</div>