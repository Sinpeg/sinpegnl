<script>
  function tecla(){
    var evt = window.event;
    var tecla = evt.keyCode;
    if ((tecla > 47 && tecla < 58) || (tecla==44)){ 
    	//alert("ok");
    }else{
      alert('Pressione apenas teclas numéricas e a vírgula!');
      evt.preventDefault();
    }
  }
</script>

<?php
//session_start ();
$_SESSION['mensagem']='';

$sessao = $_SESSION ['sessao'];

if (!isset($sessao)) {
	exit();
}

$c=new Controlador();

if ($c->getProfile($sessao->getGrupo())){
	include 'listamapapdu18.php';
}else{
	include 'listamapapdu.php';
}

?>


