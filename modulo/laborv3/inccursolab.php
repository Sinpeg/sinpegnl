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
$selecao="";
if (!$aplicacoes[7]) {
    exit();
}

$anobase=$sessao->getAnobase();
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
$codlab = $_GET["codlab"];
//$rows = $daocur->buscacursonivelcampus($codunidade,$anobase); ateração 27/abril/2021
//A linha abaixo pega todos os cursos da instituição
$rows = $daocur->buscacursonivelcampus1($anobase,$codlab);
	
$cursos = array();
foreach ($rows as $row) {
    $cursos[$cont] = new Curso();
    $cursos[$cont]->setCodcurso($row['CodCurso']);
    
    $cursos[$cont]->setNomecurso($row['NomeCurso']);
    $cursos[$cont]->setCodemec($row['idcursoinep']);
    $cont++;
}

$daocur->fechar();

///////////////////////////////
$unidade = new Unidade();
$daolab=new LaboratorioDAO();
//Instanciar lab

if (is_numeric($codlab) && $codlab != ""){

	$daolabcurso = new LabcursoDAO();
	$rows = $daolab->buscaLaboratorio($codlab,$anobase);	
	
	foreach ($rows as $row) {
		//cria tipo do lab para criar lab
		$tipolab = new Tplaboratorio();
		$tipolab->setCodigo($row["Tipo"]);
		$unidade->setCodunidade($row["CodUnidade"]);	
		$lab = $unidade->criaLab($codlab, $tipolab, $row["nomelab"], null, null, null, null, null, null, null, null, null, null, null, null);
	    $lab->setAtendecursograd($row['atendecursograd']);
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

<head>
    <div class="bs-example">
		<ul class="breadcrumb">
		    <li class="active">
                <a href="<?php echo Utils::createLink("laborv3", "conslabcurso",array('codlab' => $codlab)); ?>">Consultar Cursos Vinculados</a>
			    <i class="fas fa-long-arrow-alt-right"></i>
                Incluir cursos vinculados
            </li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"> Vincular Laboratório a Cursos</h3>
    </div>
    <form class="form-horizontal" name="fgravar" method="post" action="<?php echo Utils::createLink('laborv3', 'oplabcurso'); ?>">
        <table class="card-body">

            <div class="msg" id="msg"></div>

            <input class="form-control"name="codlab" type="hidden" value="<?php print $_GET["codlab"]; ?>" /> <input class="form-control"name="operacao" type="hidden" value="I" />

                <tr>
                    <td class="coluna1">
                        <input class="form-check-input" type="checkbox" value="S" id="cursos"
                                name="cursos" <?php if ($lab->getAtendecursograd()=="" || $lab->getAtendecursograd()==1)
                                                            $sit="";
                                                    else $sit="checked";
                                echo $sit;
                                ?>/>
                        Este laboratório não é utilizado pelos alunos de cursos de graduação em aulas e/ou estágios de práticas profissionais!</td>
                </tr>
                    
                <tr>
                    <td>Laborat&oacute;rio: <?php echo $lab->getNome();?></td>
                </tr>
        </table>
            
        <div id="cursos1"> 
            <table class="card-body">
                <tr>
                    <td class="coluna1">Curso</td>
                </tr>
                <tr>
                    <td class="coluna2">
                        <select class="custom-select" name="curso">
                            <option value=0>Selecione o curso...</option>
                            <?php foreach ($cursos as $c) { ?>
                                <option value="<?php print $c->getCodcurso(); ?>"><?php print $c->getNomecurso()."-".$c->getCodemec(); ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        
        <br/>
        
        <table class="card-body">
            <tr>
                <td align = "center">
                    <?php if ($botao && !$lock->getLocked()){?>
                        <input class="form-control"value="Gravar" type="button" class="btn btn-info" id="gravar" />
                        <a href="<?php echo Utils::createLink("laborv3", "consultalab"); ?>"></a>
                    <?php }?>
                </td>
            </tr>
        </table>
    </form>
</div>


<script>
$('#gravar').click(function(){

	$('div#msg').empty();
    

    $.ajax({url: 'ajax/laborv3/oplabcurso.php', type: 'POST', data:$('form[name=fgravar]').serialize() , 
        success: function(data) {
        	 $('div#msg').html(data);  
         //	alert(data);
       
    }});
});
 $("#cursos").change(function() {
        if (this.checked) {
        //alert("mac"); 
          $('#cursos1').hide();
          
        } else {
          //alert("magc"); 
           $('#cursos1').show();
        }
    });
    
    
 
    
    
    $( window ).load(function() {
       if ($('#cursos').is(":checked")){
          $('#cursos1').hide();
          
        } else {
           $('#cursos1').show();
        }
    
    
     
    });
    
   

</script>
