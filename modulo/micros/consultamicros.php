<?php
if (!$aplicacoes[6]) {
    header("Location:index.php");
} else {
    require_once('dao/microsDAO.php');
    require_once('classes/micros.php');

    $micros = new Micros();
    $cont = 0;
    $somaacad = 0;
    $somaadm = 0;
    $somaint = 0;
    $somasemint=0;
    $qtdeAcadInt = 0; // quantidade de uso acadêmico com Internet
    $qtdeAcad = 0; // quantidade de uso acadêmico
    $qtdeAdmInt = 0; // quantidade de uso administrativo com Internet
    $qtdeAdm = 0; // quantidade de uso administrativo
    $soma = 0;
    $daom = new microsDAO();
    $lock = new Lock();
    $codigo = null;
    
    if (!$sessao->isUnidade()) {
        // verifica se possui homologação
        $lock->setLocked(Utils::isApproved(6, $cpga, $codunidade, $anobase));
                 
    }
    //echo count($array_codunidade);
    // Procura em todos os códigos de unidades e subunidades
    for ($i = 0; $i < count($array_codunidade); $i++) {
        // busca os micros associados a unidade e ano base
        $rows = $daom->buscamicrosunidade($array_codunidade[$i], $anobase);
        // Se é CPGA, o código de busca é de outra subunidade
        // e esta subunidade possui dados $rowCount()>0
        // Bloqueia edição
        if ($sessao->isUnidade() && $array_codunidade[$i] != $codunidade && $rows->rowCount() > 0) {
            $lock->setLocked(true);
        }

        foreach ($rows as $row) {
            $somaacad += $row['QtdeAcadInt'] + $row['QtdeAcad'];
            $somaadm += $row['QtdeAdm'] + $row['QtdeAdmInt'];
            $somaint += $row['QtdeAcadInt'] + $row['QtdeAdmInt'];
            $somasemint += $row['QtdeAcad'] + $row['QtdeAdm'];
            $soma += $row['QtdeAcad'] + $row['QtdeAdm'];
            // somatório para consolidação dos dados
            $qtdeAcad += $row["QtdeAcad"];
            $qtdeAcadInt += $row["QtdeAcadInt"];
            $qtdeAdm += $row["QtdeAdm"];
            $qtdeAdmInt += $row["QtdeAdmInt"];
            // fim
            $codigo = $row["Codigo"]; // código micros registrado
            $cont++;
        }

        /*if($i>0){
              $j = $i-1;               
            if($array_codunidade[$i] != $array_codunidade[$j]){
                foreach ($rows as $row) {
                    $somaacad += $row['QtdeAcadInt'] + $row['QtdeAcad'];
                    $somaadm += $row['QtdeAdm'] + $row['QtdeAdmInt'];
                    $somaint += $row['QtdeAcadInt'] + $row['QtdeAdmInt'];
                    $somasemint += $row['QtdeAcad'] + $row['QtdeAdm'];
                    $soma += $row['QtdeAcad'] + $row['QtdeAdm'];
                    // somatório para consolidação dos dados
                    $qtdeAcad += $row["QtdeAcad"];
                    $qtdeAcadInt += $row["QtdeAcadInt"];
                    $qtdeAdm += $row["QtdeAdm"];
                    $qtdeAdmInt += $row["QtdeAdmInt"];
                    // fim
                    $codigo = $row["Codigo"]; // código micros registrado
                    $cont++;
                     
                }
            }
        }*/
        // configuração dos dados
        $micros->setAcad($qtdeAcad);
        $micros->setAcadi($qtdeAcadInt);
        $micros->setAdm($qtdeAdm);
        $micros->setAdmi($qtdeAdmInt);
        $micros->setCodigo($codigo);
        // fim
    }
    if ($cont==0) {
        Utils::redirect('micros', 'incluimicros');
    }
}
?>

<script type="text/javascript">
    function send(action){
        switch (action) {
            case 'alterar':
                url = "<?php echo Utils::createLink('micros', 'alteramicros'); ?>";
                break;
            case 'incluir':
                url = 'incluimicros';
                break;
        }
        document.forms[0].action = url;
        document.forms[0].submit();
    }
</script>

<head>
	<div class="bs-example">
		<ul class="breadcrumb">
            <li class="active">
                <a href="<?php echo Utils::createLink("micros", "incluimicros"); ?>" >Computadores</a>
		        <i class="fas fa-long-arrow-alt-right"></i>
                Consulta
            </li>
		</ul>
	</div>
</head>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title"> Computadores com/sem acesso &agrave; Internet</h3>
    </div>
    <form class="form-horizontal" name="gravar" method="post">
        
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr align="center" style="font-weight: bold;">
                        <th></th>
                        <th>Qtde. com Internet</th>
                        <th>Qtde. sem Internet</th>
                        <th>Totais </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Uso Acad&ecirc;mico</td>
                        <td align="center"><?php echo $micros->getAcadi(); ?></td>
                        <td align="center"><?php echo $micros->getAcad(); ?></td>
                        <td align="center"><?php echo $somaacad; ?></td>
                    </tr>
                    <tr>
                        <td>Uso Administrativo</td>
                        <td align="center"><?php echo $micros->getAdmi(); ?></td>
                        <td align="center"><?php echo $micros->getAdm(); ?></td>
                        <td align="center"><?php echo $somaadm; ?></b></td>

                    </tr>
                    <tr>
                        <td>Total Geral</td>
                        <td align="center"><?php echo $somaint; ?></td>
                        <td align="center"><?php echo $somasemint; ?></td>
                        <td align="center"><?php echo $somaacad + $somaadm; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <ul class="excel">
            <li><a href="relatorio/micros/relatorioForm.php">Planilha (versão completa)</a></li>
        </ul>
        <table class="card-body">
            <tr>
                <td colspan=4 align="center">
                    <?php if (!$lock->getLocked()) { ?>
                            <input class="form-control"name="codigo" type="hidden" value="<?php echo $micros->getCodigo(); ?>" />
                            <input type="button" id="gravar" class="btn btn-info" value="Alterar" onclick="send('alterar');" />
                    <?php } ?>
                </td>
            </tr>
        </table>
    </form>
</div>