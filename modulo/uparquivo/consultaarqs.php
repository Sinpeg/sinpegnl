<?php
ini_set('display_errors','on');
if (!isset($_SESSION["sessao"])) {
   echo "Você precisa logar no sistema!";die;
}
$sessao = $_SESSION["sessao"];
if ($sessao->getUnidadeResponsavel()!=1){
	header("location:index.php");
}
$codunidade = $sessao->getCodUnidade();
$responsavel = $sessao->getResponsavel();
$anobase = $sessao->getAnoBase();
$data = $sessao->getData();
$usuarios = array();
$dao = new ArquivoDAO();

if (!empty($_GET['msg'])){
	print $_GET['msg'];
}

$i = 0;
$rows = $dao->buscaUnidade($anobase, $codunidade);
foreach ($rows as $row) {
    $i++;
    $usuarios[$i] = new Usuario();
    $usuarios[$i]->setCodusuario($row['CodUsuario']);
    $usuarios[$i]->setResponsavel($row['Responsavel']);
    $usuarios[$i]->adicionaItemArquivo($row['Codigo'], $row['Assunto'], null, $row['Nome'], null, null, $row['Comentario'], null, $row['DataInclusao'], $anobase);
}

//$i é o tamanho do vetor usuarios
$dao->fechar();

if ($i == 0) {
    include 'modulo/uparquivo/entarquivo.php';
} else {
    ?>
    <script language="JavaScript">
        function direciona(botao) {
            if (botao == 1) {
                if (document.farquivo.registros.value == 2)
                    document.getElementById('msg').innerHTML = "O número máximo de arquivos é 2.";
                else {
                    document.getElementById('farquivo').action = "<?php echo Utils::createLink("uparquivo", "entarquivo"); ?>";
                    document.getElementById('farquivo').submit();
                }
            }
            else if (botao == 2) {
                document.getElementById('farquivo').action = "../saida/saida.php";
                document.getElementById('farquivo').submit();
            }
        }

        //Função para deletar arquivo	
        function confirmaDelete(link){
			//alert (link);
        	$(function() {
        		$( "#delete-dialog" ).dialog({
        			resizable: false,
        			height: "auto",
        			width: 400,
        			modal: true,
        			buttons: {
                        "Deletar": function() {
                            $( this ).dialog( "close" );
                            window.location=link;
                            
                        },
                        Cancelar: function() {
                            $( this ).dialog( "close" );
                        }
        	        }
        	    });
        	  });
        }
    </script>
    <head>
        <div class="bs-example">
            <ul class="breadcrumb">
                <li class="active">Consultar arquivos enviados</li>
            </ul>
        </div>

        <?php echo Utils::deleteModal('Arquivo', 'Você tem certeza que deseja excluir este arquivo?'); ?>
    </head>

    <div class="card card-info">
        <div class="card-header"><h3 class="card-title">Arquivos enviados</h3></div>
        <form class="form-horizontal" name="farquivo" id="farquivo" method="post">
            <div class="msg" id="msg"></div>

            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <tr>
                        <th>Nome do Arquivo</th>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>Editar</th>
                        <th>Excluir</th>
                        <th>Baixar</th>
                    </tr>
                    <?php for ($j = 1; $j <= $i; $j++) { ?>

                        <?php foreach ($usuarios[$j]->getArquivos() as $u) {
                            ?>
                            <tr>

                                <td><?php print basename($u->getNome()); ?></td>
                                <td><?php
                                    if ($u->getAssunto() == 1) {
                                        print "Anexo";
                                    }
                                    else
                                        print "Outros";
                                    ?></td>
                                <td>
                                    <?php
                                    $data1 = explode('-', $u->getDatainclusao());
                                    $data = $data1[2] . '/' . $data1[1] . '/' . $data1[0];
                                    print $data;
                                    ?>
                                </td>
                                <td align="center">
                                    <?php if ($usuarios[$j]->getResponsavel() == $responsavel) { ?>
                                        <a href="<?php echo Utils::createLink('uparquivo', 'alteraarquivo', array('codigo' => $u->getCodigo())); ?>" target="_self"><img
                                                src="webroot/img/editar.gif"
                                                alt="Alterar" width="19" height="19" /> </a>
                                        <?php } ?>
                                </td>
                                <td align="center">
                                    <?php if ($usuarios[$j]->getResponsavel() == $responsavel) { ?>
                                        <a href="<?php echo Utils::createLink('uparquivo', 'delarquivo', array('codigo' => $u->getCodigo())); ?>" class="delete-link" target="_self">
                                            <img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /> 
                                        </a>
                                    <?php } ?>

                                </td>
                                <td align="center">
                                    <?php //if ($usuarios[$j]->getResponsavel() == $responsavel ) { 
                                            if ($sessao->getAnobase()<2021){?>
                                        <a href="<?php echo Utils::createLink('uparquivo', 'downarquivo', array('codigo' => $u->getCodigo())); ?>"
                                        target="_self"><img
                                                src="webroot/img/download.jpg"
                                                alt="Excluir" width="19" height="19" /> </a>
                                        <?php } else {?>
                                        <a href="<?php print "../public/raa_anexos1/ano".trim($anobase).'/'.$u->getNome()  ?>"
                                        target="_self"><img  src="webroot/img/download-2.png"  alt="Download" width="25" height="25" /> </a>
        
                                            
                                        <?php }
                                // }?>

                                </td>
                            </tr>

        <?php } ?>
                    <?php } ?>
                </table>
                <?php echo $i; ?> registro(s)
            </div>
            <div class="card-body" align="center">
                <input class="form-control" type="hidden" name="registros" readonly="readonly" size="1" value="<?php echo $i; ?>" style="border: none;background-color: transparent" /> 
                <input type="button" onclick="direciona(1);" value="Incluir arquivo" class="btn btn-info" />
            </div>
        </form>
    </div>
<?php } ?>

