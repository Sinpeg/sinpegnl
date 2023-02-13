<?php
//session_start(); - Sessão já inicialiada

$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

//Importando bibliotecas
require_once('dao/labcursoDAO.php');
require_once('classes/labcurso.php');
require_once('classes/laboratorio.php');
require_once('classes/tplaboratorio.php');
require_once('dao/laboratorioDAO.php');

if (!$aplicacoes[7]) {
    header("Location:index.php");
}



$cont = 1;
$daocur = new CursoDAO();
// Recupera o código da unidade responsável pela subunidade
// No trecho de código abaixo, faz o bloqueio da subunidade
$daoun = new UnidadeDAO();
$rows = $daoun->unidadeporcodigo($codunidade);
foreach ($rows as $row) {
    $id_unidade = $row["unidade_responsavel"]; // recebe o id da unidade responsável
    $rows1 = $daoun->buscaidunidade($id_unidade);
    foreach ($rows1 as $row1) {
        // echo $row1["unidade_responsavel"];
        if ($row1["unidade_responsavel"] == 1) {
           $codunidade = $row1["CodUnidade"];
        }
    }
}
// Fim do trecho para recuperar o codigo da subunidade

	//$rows = $daocur->buscacursonivelcampus($codunidade,$anobase); ateração 27/abril/2021
	//A linha abaixo pega todos os cursos da instituição
	$rows = $daocur->buscacursonivelcampus1($anobase,$codlab);
	
$cursos = array();
foreach ($rows as $row) {
    $cursos[$cont] = new Curso();
    $cursos[$cont]->setCodcurso($row['CodCurso']);
    $cursos[$cont]->setNomecurso($row['NomeCurso']);
    $cont++;
}


$daocur->fechar();

///////////////////////////////
$codlab = $_GET["codlab"];
$unidade = new Unidade();
$daolab=new LaboratorioDAO();
//Instanciar lab
if (is_numeric($codlab) && $codlab != ""){

	$daolabcurso = new LabcursoDAO();
	$rows = $daolab->buscaLaboratorio($codlab);	
	
	foreach ($rows as $row) {
		//cria tipo do lab para criar lab
		$tipolab = new Tplaboratorio();
		$tipolab->setCodigo($row["Tipo"]);
		$unidade->setCodunidade($row["CodUnidade"]);	
		$lab = $unidade->criaLab($codlab, $tipolab, $row["Nome"], null, null, null, null, null, null, null, null, null, null, null, null);
	
	}
}
////////////////////////////////

$botao=$unidade->getCodunidade()==$sessao->getCodUnidade()?true:false;



	    
    //Inclusão para impedir a edição após vinculado

    // Trecho para bloqueio
    $lock = new Lock();
    // verifica a infraestrutura informada é da unidade
  
        if (!$botao) {
            $lock->setLocked(true);
            $botao=false;
        }
    
    if (!$sessao->isUnidade()) {
        $lock->setLocked(Utils::isApproved(7, $codunidadecpga, $codunidade, $sessao->getAnobase()));
    }
   //--------------------------------------------- 
    



?>
<form class="form-horizontal" name="fgravar" method="post" action="<?php echo Utils::createLink('labor', 'oplabcurso'); ?>">
    <?php if (!$botao){?>

<div class="alert alert-warning">
  <strong>Atenção!</strong> O laboratório pertence a outra unidade, portanto não é possível vincular cursos!
</div>
    <?php }?>


    <input class="form-control"name="codlab" type="hidden" value="<?php print $_GET["codlab"]; ?>" /> <input class="form-control"name="operacao" type="hidden" value="I" />
    <h3 class="card-title"> Vincular Laboratório a Cursos</h3>
    <br />
    <table>
        <tr>
            <td>Laborat&oacute;rio:</td>
            <td><?php echo $lab->getNome();?></td>
        </tr>
        <tr>
            <td>Curso:</td>
            <td><select class="custom-select" name="curso">
                    <option value=0>Selecione o curso...</option>
                    <?php foreach ($cursos as $c) { ?>
                        <option value="<?php print $c->getCodcurso(); ?>"><?php print $c->getNomecurso(); ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
    </table><br/>
    <?php if ($botao && !$lock->getLocked()){?>
    <input class="form-control"value="Gravar" class="btn btn-info" type="submit"  class="btn btn-info" name="botgravar" />&ensp;&ensp;
    <a href="<?php echo Utils::createLink("labor", "consultalab"); ?>">
    <?php }?>
        <input type="button" class="btn btn-info" onclick="javascript:history.go(-1);" value="Voltar"  />
    </a>
</form>
