<?php
if (!isset($_SESSION["sessao"])) {
    exit();
}

$sessao = $_SESSION["sessao"];
$nomeunidade = $sessao->getNomeUnidade();
$codunidade = $sessao->getCodUnidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$aplicacoes = $sessao->getAplicacoes();
$loginsessao = $sessao->getLogin();
$codigo = $sessao->getCodusuario();
$hierarquia = $sessao->getCodestruturado();

if ($aplicacoes[23]) {
    if (($codunidade == 100000) || ($sessao->getUnidadeResponsavel()==1)) {// se for usuário administrador
        $login = filter_input(INPUT_GET, 'login', FILTER_DEFAULT);
        if (empty($login)) { // se não estiver configurado o login
            $login = $loginsessao; // então pega o login do administrador
        }
    } else {
        $login = "";
        $dao = new UsuarioDAO();
        $rows = $dao->buscaUnidadeLogin($nomeunidade,$hierarquia);
        foreach ($rows as $row) {
            if ($row["CodUsuario"] == $codigo) {
                $login = $row["Login"];
            }
        }
    }
    
    if ($login != "" && is_string($login)) {
        $dao = new UsuarioDAO();
        $rows = $dao->buscaLogin($login);
        foreach ($rows as $row) {
            $usuario = new Usuario();
            $usuario->setCodusuario($row["CodUsuario"]);
            $usuario->setResponsavel($row["Responsavel"]);
            $usuario->setLogin($login);
            $usuario->setEmail($row["Email"]);
            $usuario->criaUnidade($row["CodUnidade"], $row["nome"], null);
        }
    }
} else {
    $mensagem = urlencode(" ");
}
?>

<head>
    <div class="bs-example">
        <ul class="breadcrumb">
            <li class="active">Alteração de cadastro de usuário</li>
        </ul>
    </div>
</head>

<div id="resposta-ajax"></div>
<div class="card card-info">        
    <div class="card-header">
        <h3 class="card-title">Alterar usuário</h3>
    </div>
    <form class="form-horizontal" name="us" id="us" method="post" action="<?php echo Utils::createLink('usuario', 'opusuario'); ?>">

        <table class="card-body">
            <div class="msg" id="msg"></div>
            <tr>
                <td class="coluna1">Login</td>
            </tr>
            <tr>
                <td class="coluna2">
                    <input class="form-control"type="text" size="15" maxlength="10" name="login" readonly
                        id="login" size="10" value='<?php print $usuario->getLogin(); ?>'
                        onchange="loginajax();" />
                </td>
            </tr>
            <tr>
                <td class="coluna1">Senha</td>
            </tr>
            <tr>    
                <td class="coluna2"><input class="form-control"type="password" size="15" maxlength="8" name="senha"
                        size="10" value='' /></td>
            </tr>
            <tr>
                <td class="coluna1">Confirma senha</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="password" size="10" maxlength="8" name="confirma"
                        onblur="confirmasenha();" size="10" value='' /></td>
            </tr>
            <tr>
                <td class="coluna1">Nome</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" name="nome" size="80"
                        value='<?php print $usuario->getResponsavel(); ?>' />
                </td>
            </tr>
            <tr>
                <td class="coluna1">E-mail</td>
            </tr>
            <tr>
                <td class="coluna2"><input class="form-control"type="text" name="email" size="80"
                        value='<?php print $usuario->getEmail(); ?>' />
                </td>
            </tr>
            <?php if ($codunidade == 100000) { ?>
                <tr>
                    <td class="coluna1">Unidade</td>
                </tr>
                <tr>
                    <td class="coluna2">
                        <input class="form-control"type="text" size="60" id="parametro" name="parametro" value="<?php echo $usuario->getUnidade()->getNomeunidade();?>" />
                        <input type="button" onclick="usajax();" value="Buscar" />
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <table class="card-body">
            <tfoot>
                <td colspan="2" align="center">
                    <br>
                    <input class="form-control"name="codigo" type="hidden" readonly="readonly" value="<?php print $usuario->getCodusuario(); ?>" />
                    <input class="form-control"name="operacao" type="hidden" readonly="readonly" value="A" />
                    <input class="btn btn-info" type="submit"  id="gravar" name="gravar" value="Gravar" class="btn btn-info"/>
                </td>
              </tr>
            </tfoot>
        </table>        
        <div class="msg" id="msg1"></div>

    </form>
</div>

<script>
    $(function() {
        $('input[name=gravar]').click(function() {
            $('div#msg').empty();		
            $.ajax({
                url: "ajax/usuario/opusuario.php", 
                type: 'POST', 
                data:$('form[name=us]').serialize(),
                success: function(data) {                
                    $('div#msg').html(data);
                }
            });   
        });
    });

    $('#us').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
            e.preventDefault();
            return false;
        }
    });
</script>

