<?php

require_once '../../dao/PDOConnectionFactory.php';
require_once '../../classes/sessao.php';
require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
session_start();
$sessao = new Sessao();
$sessao = $_SESSION['sessao'];
if (!isset($_SESSION["sessao"])) {
    echo "Sua sessão expirou, faça login novamente!";
    exit();
}
$tipounidade=$_POST['tipo'];
$sigla=$_POST['sigla'];
$siafi=$_POST['siafi'];
$perfil=$_POST['perfil'];
if ($sessao->getCodUnidade()==100000){
    $uresp=1;
    if (isset($_POST['uresp'])){
        $uresp=$_POST["uresp"];//id da responsavel
    }
}else{
    $uresp=$sessao->getIdunidade();
}


if (isset($_POST['codigouni'])){
	$codigo=$_POST['codigouni'];
	$nomeunidade=strtoupper($_POST['cadunidadealt']);
}else{
    $nomeunidade=strtoupper($_POST['cadunidade']);
    $codigo=0;
}

if(empty($nomeunidade)){
	echo "O campo unidade deve ser informado.";
}else if  ($tipounidade=="0"){
	echo  "O campo tipo deve ser informado.";
}else if  ($perfil=="0"){
    echo  "O campo categoria deve ser informado.";
}else if (!isset($siafi) && !isset($_POST["ehsubunidade"])) {
	echo 'O campo siafi deve ser informado para unidades responsáveis por outras unidades!';
}else if (!isset($sigla) && !isset($_POST["ehsubunidade"])) {
	echo 'O campo sigla deve ser informado para unidades responsáveis por outras unidades!';
}else if ($uresp=="0" && isset($_POST["ehsubunidade"])) {
		echo 'Informe a unidade responsável pela subunidade!';

}else{
$u=new Unidade();
$dao= new UnidadeDAO();
$u->setSigla($sigla);
$u->setTipounidade($tipounidade);
$u->setNomeunidade($nomeunidade);
$u->setSiafi($siafi);

$dao->spgravaunidade($nomeunidade, $uresp,$tipounidade, $sigla,$siafi,$codigo,$perfil);


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

    */?>


<?php
}

?>


