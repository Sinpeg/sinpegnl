<?php
//set_include_path(';../../includes');
//require_once('../../includes/classes/sessao.php');
session_start();
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[30]) {
    header("Location:index.php");
}  else {
    $sessao = $_SESSION["sessao"];
//    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
//    $responsavel = $sessao->getResponsavel();
    $anobase = $sessao->getAnobase();
//    require_once('../../includes/dao/PDOConnectionFactory.php');
    require_once('dao/incubadoraDAO.php');
    require_once('classes/incubadora.php');
    //$incubadora=array();
    $cont = 0;
    $daoin = new IncubadoraDAO();
    $incubadora = new Incubadora();
    $rows_in = $daoin->buscainc($anobase);
    foreach ($rows_in as $row) {
        if ($row['CodUnidade'] == $codunidade) {
            $cont++;
            $incubadora->setCodigo($row['Codigo']);
            $incubadora->setEmpresasgrad($row['empresasgrad']);
            $incubadora->setEmpgerados($row['empgerados']);
            $incubadora->setProjaprovados($row['projaprovados']);
            $incubadora->setEventos($row['eventos']);
            $incubadora->setNempreendedores($row['nempreendedores']);
            $incubadora->setCapacitrh($row['capacitrh']);
            $incubadora->setConsultorias($row['consultorias']);
            $incubadora->setPartempfeiras($row['partempfeiras']);
            $incubadora->setAno($row['Ano']);
        }
    }
    $daoin->fechar();
    if ($cont == 0) {
        Utils::redirect('incubadora', 'incluincub');
//        header("location:incluincub.php");
//        exit();
    }
}
//ob_end_flush();
?>
<script>
    function send(action)
    {
        switch (action) {
            case 'alterar':
                url = '?modulo=incubadora&acao=alteraincub';
                break;
            case 'incluir':
                url = '?modulo=incubadora&acao=incluincub';
                break;
            case 'sair':
                url = "../saida/saida.php";
                break;
        }

        document.forms[0].action = url;
        document.forms[0].submit();
    }
</script>
<form class="form-horizontal" name="gravar" method="post" action="incluiextensao.php">

    <h3 class="card-title">Produ&ccedil;&atilde;o da Incubadora de Empresas</h3>

    <?php if ($cont > 0) { ?>
        <table>
                <tr style="font-style: italic;" align="center">
                    <td>Itens</td>
                    <td>Quantidade</td>
                </tr>
            <tr><td>Empresas Graduadas</td><td> <?php echo $incubadora->getEmpresasgrad(); ?></td> </tr>
            <tr><td>Empregos Gerados</td><td> <?php echo $incubadora->getEmpgerados(); ?></td> </tr>
            <tr><td>Projetos Aprovados (SEBRAE, FINEP, Fundos Setoriais, etc.)</td><td> <?php echo $incubadora->getProjaprovados(); ?></td> </tr>
            <tr><td>Eventos Promovidos (Cursos, Palestras, Workshops, F&oacute;runs)</td><td> <?php echo $incubadora->getEventos(); ?></td> </tr>
            <tr><td>N&uacute;mero de Empreendedores Capacitados</td><td> <?php echo $incubadora->getNempreendedores(); ?></td> </tr>
            <tr><td>Capacita&ccedil;&atilde;o de Recursos Humanos (Cursos)</td><td> <?php echo $incubadora->getCapacitrh(); ?></td> </tr>
            <tr><td>Consultorias Promovidas</td><td> <?php echo $incubadora->getConsultorias(); ?></td> </tr>
            <tr><td>Participa&ccedil;&atilde;o das Empresas em Feiras</td><td> <?php echo $incubadora->getPartempfeiras(); ?></td> </tr>
        </table> <br/>

        <input type="button" value="Alterar" onclick="send('alterar');" />

        <?php
    } else {
        Utils::redirect('incubadora', 'incluincub');
    }
    ?>
</form>
