<?php
require '../../dao/PDOConnectionFactory.php';
require_once "../../modulo/laborv3/dao/laboratorioDAO.php";
require_once '../../classes/unidade.php';
require_once '../../classes/curso.php';
require_once '../../classes/sessao.php';
require_once('../../modulo/laborv3/classes/laboratorio.php');
require_once('../../modulo/laborv3/classes/labcurso.php');
require_once('../../modulo/laborv3/dao/labcursoDAO.php');
require_once('../../modulo/laborv3/classes/tplaboratorio.php');
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
    exit();
}
$nomeunidade = $sessao->getNomeunidade();
$codunidade = $sessao->getCodunidade();

$unidade = new Unidade();
$unidade->setCodUnidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$codcurso = $_POST["curso"];
$codlab = $_POST["codlab"];


$daolc = new LabcursoDAO();
$passou = 0;
$daolab = new LaboratorioDAO();

if (!isset($_POST['cursos']) && $codcurso==0){
    $erro="Selecione um curso!";
}else{
        if (!isset($_POST['cursos'])){// se nao tiver sido checked
            
                $rows = $daolc->buscaCursoLaboratorio($codcurso, $codlab);
                foreach ($rows as $row) {
                    $passou = 1;
                }
                if ($passou == 0) {
                    $curso =new Curso();
                    $curso->setCodcurso($codcurso);
                    $daolab = new LaboratorioDAO();
                    $rows = $daolab->buscaLaboratorio($codlab,$sessao->getAnobase());
                    foreach ($rows as $row) {
                        $tipolab = new Tplaboratorio();
                        $tipolab->setCodigo($row['Codtipo']);
                        $lab = $tipolab->criaLabv3($codlab);
                    }
                    $labcurso = $lab->criaLabcurso(null, $curso,$sessao->getAnobase());
                    
                   $daolc->insere($labcurso);
                   $lab->setSituacao('A');
                   $lab->setAtendecursograd(1);
                   $daolab->alteraSit($lab);
                   $sucesso="Operação realizada com sucesso";
                }
           
        }else{
            //verifica se já não tem laboratórios vinculados
            $lab=new Laboratorio();
            $lab->setCodlaboratorio($codlab);
            $rows =$daolc->buscaCursosLaboratorio($lab);
            foreach ($rows as $row) {
                $passou = 1;
            }
            if  ($passou==1){
                $erro="Este laboratório já tem cursos informados, exclua os cursos para poder selecionar a caixa de seleção.";
            }else{
               
                $lab->setSituacao('A');
                $lab->setAtendecursograd(0);
                $daolab->alteraSit($lab);
                $sucesso="Operação realizada com sucesso";
            }
        }
}

//$daolc->fechar();
//$cadeia = "Location:" . Utils::createLink('laborv3', 'conslabcurso', array('codlab' => $codlab));
//header($cadeia);
    
    
    
 
if ($sucesso != ""): ?>
    <div id="success" class="alert alert-success" role="alert">
        <img src="webroot/img/accepted.png" width="30" height="30"/><?php print $sucesso; ?>
    
        
    </div>
    <?php else : ?>
     <div class="erro alert alert-danger" role="alert">
        <img src="webroot/img/error.png" width="30" height="30"/>
    <?php print $erro; ?>
    </div>
<?php endif; 
?>
