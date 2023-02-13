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
    $somasemint = 0;
    $qtdeAcadInt=0; // quantidade de uso acadêmico com Internet
    $qtdeAcad=0; // quantidade de uso acadêmico
    $qtdeAdmInt=0; // quantidade de uso administrativo com Internet
    $qtdeAdm=0; // quantidade de uso administrativo
    $soma = 0;
    $daom = new microsDAO();
    $lock = new Lock();
    $codigo = null;
    // Procura em todos os códigos de unidades e subunidades
    for ($i=0; $i<count($array_codunidade); $i++) {
        // busca os micros associados a unidade e ano base
        $rows = $daom->buscamicrosunidade($array_codunidade[$i], $anobase);
        // Se é CPGA, o código de busca é de outra subunidade
        // e esta subunidade possui dados $rowCount()>0
        // Bloqueia edição
        if ($sessao->isUnidade() && $array_codunidade[$i]!=$codunidade && $rows->rowCount()>0) {            
           $lock->setLocked(true);
        }
        if (!$sessao->isUnidade()) {
            // verifica se possui homologação
            $lock->setLocked(Utils::isApproved(6, $codunidadecpga, $codunidade, $anobase));
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
                    $codigo=$row["Codigo"]; // código micros registrado
            
                }
            }
         }*/

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
            $codigo=$row["Codigo"]; // código micros registrado
            
        }

        // configuração dos dados
        $micros->setAcad($qtdeAcad); 
        $micros->setAcadi($qtdeAcadInt);
        $micros->setAdm($qtdeAdm);
        $micros->setAdmi($qtdeAdmInt);
        $micros->setCodigo($codigo);
        // fim
} ?>   

<script language="javascript">
        function SomenteNumero(e) {
            var tecla = (window.event) ? event.keyCode : e.which;
            //0 a 9 em ASCII
            if ((tecla > 47 && tecla < 58)) {
                document.getElementById('msg').innerHTML = " ";
                return true;
            }
            else {
                if (tecla == 8 || tecla == 0) {
                    document.getElementById('msg').innerHTML = " ";
                    return true;//Aceita tecla tab
                }
                else {
                    document.getElementById('msg').innerHTML = "Todos os campos devem conter apenas n�meros.";
                    return false;
                }
            }
        }
        function Soma() {
            var soma = 0;
            var somaadm = 0;
            qtde = new Array(document.formAlterar.qtdAdm.value, document.formAlterar.qtdAdmi.value,
                    document.formAlterar.qtdAca.value, document.formAlterar.qtdAcai.value);
            for (var i = 0; i < qtde.length; i++) {
                if (!isNaN(parseInt(qtde[i]))) {
                    soma += parseInt(qtde[i]);
                    if (i == 1) {
                        document.getElementById('totaladm').innerHTML = soma;
                        somaadm = soma;
                    }
                    if (i == 3) {
                        document.getElementById('totalacad').innerHTML = soma - somaadm;
                    }
                }
            }
            document.getElementById('totalgeral').innerHTML = soma;
        }
        function teste() {
            if (document.formAlterar.AI.checked) {
                document.formAlterar.acesso.value = "S";
            }
            else
                document.formAlterar.acesso.value = "N";
        }
        function valida() {
            var passou = true;
            if (document.formAlterar.qtdAca.value == "") {
                document.getElementById('msg').innerHTML = "O campo Micros de uso acad&ecirc;mico sem acesso &agrave; Internet &eacute; obrigat&oacute;rio.";
                document.formAlterar.qtdAca.focus();
            } else if (document.formAlterar.qtdAcai.value == "") {
                document.getElementById('msg').innerHTML = "O campo Micros de uso acad&ecirc;mico com acesso &agrave; Internet &eacute; obrigat&oacute;rio.";
                document.formAlterar.qtdAcai.focus();
            } else if (document.formAlterar.qtdAdm.value == "") {
                document.getElementById('msg').innerHTML = "O campo Micros de uso administrativo sem acesso &agrave; Internet &eacute; obrigat&oacute;rio.";
                document.formAlterar.qtdAdm.focus();
            } else if (document.formAlterar.qtdAdmi.value == "") {
                document.getElementById('msg').innerHTML = "O campo Micros de uso administrativo com acesso &agrave; Internet &eacute; obrigat&oacute;rio.";
                document.formAlterar.qtdAdmi.focus();
            } else
                passou = false;

            if (passou) {
                return false;
            }
            else {
                return true;
            }
        }
        function direciona() {
            if (valida()) {
                document.getElementById('formAlterar').action = "<?php echo Utils::createLink('micros', 'opmicros'); ?>";
                document.getElementById('formAlterar').submit();
            }
        }
        $(function() {
            $('a.fechar').click(function() {
                $('.ui-widget').fadeOut(800, function() {
                });
            });
        });
    </script>
    <head>
        <div class="bs-example">
            <ul class="breadcrumb">
                <li><a href="<?php echo Utils::createLink("micros", "incluimicros"); ?>" >Computadores</a></li>
                <li><a href="<?php echo Utils::createLink("micros", "consultamicros"); ?>" >Consulta</a></li>
                <li class="active">Alterar</li>
            </ul>
        </div>
	</head>
    <?php if (!$lock->getLocked()){ ?>  
        <div class="ui-widget">
            <div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;"> 
                <a href="#fechar" class="fechar">Fechar [x]</a>
                <p>
                    <span class="ui-icon ui-icon-alert" 
                        style="float: left; margin-right: .3em;"></span>
                    <strong>Dicas:</strong>
                </p>
                <ul>
                    <li>(*) A unidade que utilizar notebooks de propriedade dos alunos como material de acompanhamento dos cursos, deverá incluí-los neste campo. </li>
                    <li> O número de microcomputadores de uso acadêmico + número de microcomputadores na administração deverá ser igual ao número total de microcomputadores da unidade. </li>
                    <li> Observe também que o número de micros com a cesso à internet já está contido no número total de microcomputador</li>
                    <li> A digitação do número de microcomputadores de uso acadêmico e o nº de micros na administração deverá ser obrigatória, neste caso até o zero é válido e deverá ser informado.</li>
                </ul>     
            </div>
        </div>
    <?php } ?>
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"> Computadores com/sem acesso &agrave; Internet</h3>
        </div>
        <form class="form-horizontal" name="formAlterar" id="formAlterar" method="post">
            <div class="msg" id="msg"></div>
            <div class="card-body">
                <table class="table table-bordered table-hover" width="450px">
                    <tr>
                        <td></td>
                        <td>Qtde. com Internet</td>
                        <td>Qtde. sem Internet</td>
                        <td> Totais </td>
                    </tr>
                    <tr>
                        <td>Uso Acad&ecirc;mico</td>
                        <td class = "coluna2"><input class="form-control"type="text" name="qtdAcai" style="width:100px;" size="5" maxlength="5" value='<?php echo $micros->getAcadi(); ?>' onchange="Soma()";
                                onkeypress="return SomenteNumero(event);"  <?php echo $lock->getDisabled(); ?> /></td>
                        <td class = "coluna2"><input class="form-control"type="text" name="qtdAca"  style="width:100px;" size="5" maxlength="5" value='<?php echo $micros->getAcad(); ?>' onchange="Soma()";
                                onkeypress="return SomenteNumero(event);" <?php echo $lock->getDisabled();; ?> /></td>
                        <td><b id='totalacad'></b></td>
                    </tr>
                    <tr>
                        <td>Uso Administrativo</td>
                        <td class = "coluna2"><input class="form-control"type="text" name="qtdAdmi" onchange="Soma();"
                                maxlength="5" size="5" style="width:100px;" value='<?php echo $micros->getAdmi(); ?>'
                                onkeypress="return SomenteNumero(event);" <?php echo $lock->getDisabled(); ?> /></td>
                        <td class = "coluna2"><input class="form-control"type="text" style="width:100px;" name="qtdAdm" onchange="Soma();"
                                maxlength="5" size="5" value='<?php echo $micros->getAdm(); ?>'
                                onkeypress="return SomenteNumero(event);" <?php echo $lock->getDisabled(); ?> /></td>
                        <td><b id='totaladm'></b></td>
                    </tr>
                    <tr><td>Total Geral</td><td></td><td></td><td><b id='totalgeral'></b></td></tr>
                </table>
            </div>
            <table class="card-body" >
                <tr>
                    <td align="center">    
                        <?php if (!$lock->getLocked()){ ?>  
                        <input class="form-control"name="operacao" type="hidden" value="A" />
                        <input class="form-control"name="codigo" type="hidden" value="<?php echo $micros->getCodigo(); ?>" />
                        <br>
                        <input type="button" class=" btn btn-info" onclick="direciona();" id="gravar" value="Gravar" <?php echo $lock->getDisabled(); ?> />
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <script>
        window.onload = Soma();
    </script>
<?php } ?>