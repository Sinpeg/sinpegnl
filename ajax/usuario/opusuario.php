<?php
/**
 * Alterado por: Diego do Couto
 * Em: 08/06/2014
 * diegocouto at ufpa.br
 */
?>
<?php
require '../../dao/PDOConnectionFactory.php';
require '../../dao/unidadeDAO.php';
require '../../dao/usuarioDAO.php';
require '../../classes/sessao.php';
require '../../classes/usuario.php';
require '../../classes/unidade.php';

session_start();
ini_set('display_errors', 'on');//habilita mensagens de erro

$sessao = $_SESSION["sessao"]; /* sessão */
$nomeunidade = $sessao->getNomeunidade(); /* nome da unidade */
$codUnidSession = $sessao->getCodunidade();  /* código da unidade na sessão */
$aplicacoes = $sessao->getAplicacoes(); /* aplicações carregadas */

if (!$aplicacoes[23]) {
    exit();
}

/* configura os parâmetros POST */
$operacao = filter_input(INPUT_POST, 'operacao', FILTER_DEFAULT);
$login = filter_input(INPUT_POST, 'login', FILTER_DEFAULT);
$senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);
$confsenha = filter_input(INPUT_POST, 'confirma', FILTER_DEFAULT);
$nome = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
$email = filter_input(INPUT_POST, 'email', FILTER_DEFAULT);
$codigo = filter_input(INPUT_POST, 'codigo', FILTER_DEFAULT); // código do usuário no banco
$unidade = filter_input(INPUT_POST, 'unidade', FILTER_DEFAULT); // código da unidade
$erro = null;

$_SESSION["cad_login"] = $login;
$_SESSION["cad_nome"] = $nome;
$_SESSION["cad_email"] = $email;

/* fim */

/* Entrada permitida para login [a-zA-Z]_- */
$erro=NULL;

if (!preg_match("/^([a-zA-Z](_|[0-9]|\-)*){4,}$/", $login)) {
    $erro= "Login deve conter no mínimo 4 caracteres. Regras: "
            . "<ul>"
            . "<li> Começa obrigatoriamente com letra(s);"
            . "<li> Após a(s) letra(s) são permitidos números e os caracteres '-' e '_'."
            . "</ul>";
} else if ($senha != $confsenha) {
    $erro="As senhas não conferem.";
} else if (strlen($senha) < 8) {
    $erro= "A senha deve conter no mínimo 8 caracteres.";
} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erro= "E-mail inválido!";   
} else if ($operacao == "I" && empty($unidade)) {
    $erro= "Selecione uma unidade!";
}

if ($erro!=NULL){
	 //<div class="alert alert-danger">
     //                                       <button type="button " class="close" data-dismiss="alert">&times;</button>
     //                                       <?php print $erro; 
     //                                   </div>
 }else {
    // dados estão válidos
	
    $usuario = new Usuario();
    $usuario->setCodusuario($codigo); // configura o código
    $usuario->setResponsavel($nome);
    $usuario->setLogin($login);
    $usuario->setSenha(md5($senha));
    $usuario->setEmail($email);
    $objun = new Unidade();
    $objun->setCodunidade($unidade);
    $usuario->setUnidade($objun);
    $dao = new UsuarioDAO();
    // consulta o login
    $rows = $dao->buscaLogin($login);
    $row = $dao->buscaCodUnidade($unidade);

    $busca = $dao->buscaNome($unidade);
	foreach($busca as $bus){
		$names = $bus['NomeUnidade'];
	}
    ///$_SESSION["cad_nomeunidade"] = $names;

    $alt = false;
    if ($operacao == "A") { // tentativa de alterar os dados
        if ($rows->rowCount() == 0) { // usuário não existe
            $alt = true; // pode alterar os dados
        } else {
            // usuário existe
            $rows = $dao->buscaCodigoLogin($login, $codigo);
            if ($rows->rowCount() == 1) { // o usuário é o mesmo da base
                $alt = true;
            } else{ // o usuário tentou alterar o nome de algum outro
                $erro="O nome do usuário já existe";
            }
        }
        // se for permitido alterar
        if ($alt) {
            if ($codUnidSession == 100000) {
                $dao->altera2($usuario);
            } else {
                $dao->altera1($usuario);
            }
                $msg="Dados atualizados com sucesso!";
        } else if (!$alt && $rows->rowCount()>0) {
            $erro="O login informado já existe na base de dados.";
        }
    } else if ($operacao=="I") {
        // operação de inclusão
        $insert = false;
        if ($rows->rowCount()==1) { // login existente
            $erro="O login informado já existe na base de dados.";
            //Utils::redirect('usuario', 'incusuario');
        }else if($row->rowCount() >= 1){ // Unidade existente
            $erro="A unidade selecionada já possui um usuário cadastrado. Efetue uma busca pelo nome da subunidade.";
        }else {
            $dao->insere1($usuario);
            $msg ="Dados registrados com sucesso!";
            $insert = true;
        }
    }
    $dao->fechar();
}

if ($erro!=NULL){?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php print $erro; ?>
    </div>
<?php }else {
	?>
	<div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
         <strong><?php print $msg; ?></strong>
    </div>
	<?php 
}

/*if (($operacao == "A") || ($operacao=="I" && $insert)) {
	$_SESSION["sucess"] = true;
    Utils::redirect('usuario', 'altusuario', array('login' => $login));
}
else {
	$_SESSION["sucess"] = true;
    Utils::redirect('usuario', 'altusuario');
}*/
