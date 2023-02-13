<?php

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
require_once '../../classes/curso.php';
require_once '../../dao/cursoDAO.php';
session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
if (!isset($_SESSION["sessao"])) {
    echo "Sua sessão expirou, faça login novamente!";
    exit();
}
$campus=$_POST['campus'];
$unidade=$_POST['unidade'];
$codsigaa=$_POST['codsigaa'];
$codemec=$_POST['codemec'];
$nivel=$_POST['nivel'];
$situacao=$_POST['situacao'];
$modalidade=$_POST['modalidade'];
$formato=$_POST['formato'];
$dao=new CursoDAO;


if (!empty($_POST['codigo'])){
    $codigo=$_POST['codigo'];
    $nomecurso=strtoupper(addslashes($_POST['cxcursoalt']));
}else{
    $codigo=0;
    $nomecurso=strtoupper(addslashes($_POST['cxcurso']));
}
if (!empty($_POST['codsigaa'])){
$codsigaa=$_POST['codsigaa'];
}else{
    $codsigaa=null;
    
}
$codemec=$codemec==0?NULL:$codemec;

if($nomecurso==""){
	echo "O campo curso deve ser informado.";
}else if  ($unidade=="0"){
	echo  "O campo unidade deve ser informado.";
}else if ($campus=="0") {
	echo 'O campo campus deve ser informado !';
}else if ($nivel=="0") {
	echo 'O campo nível deve ser informado !';
}else if ($formato=="0") {
	echo 'O campo formato deve ser informado !';
}else if ($modalidade=="0"){
	echo 'O campo modalidade deve ser informado !';
}else if ($codsigaa!="" && !is_numeric($codsigaa)){
	echo 'O campo matriz deve ser numérico!';
}else{

if ($sessao->getCodUnidade()!=100000){
	$unidade=$sessao->getCodUnidade();
}
$dao->spgravacurso($nomecurso,
$campus,
$unidade,
$codsigaa,
$codemec,
$nivel,
$situacao,
$modalidade,
$formato,
$codigo);
}

/*$rows=$dao->buscarCodUnidadeByNome($u->getNomeunidade());
if ($rows!=null && $rows->rowCount()>0){
	$unidade=new Unidade();
	$unidade->setCodunidade($r['CodUnidade']);
	$alteracao=1;
}
if ($alteracao==1){
	$dao->altera($u);
}else{
	//busca codigo pois a tabela nao tem autoincremento
    $rows=$dao->buscaUltimaUnidade();
    foreach ($rows as $r){
    	$codigo=$r["ultimocodigo"]+1;
    	if ($codigo==100000){
    		$codigo++;
    	}
    	$u->setCodunidade($codigo);
    }
    $codigo=$dao->insere($u);

    if ($idorg!=1){
    	$hierarq=$hierarqresp.$codigo.".";
    }else{
    	$hierarq=".1.".$codigo.".";
    }
    $u->setCodestruturado($hierarq);
    $dao->alterahier($u);
    $string= "Operação realizada com sucesso!";

    */

    ?>





