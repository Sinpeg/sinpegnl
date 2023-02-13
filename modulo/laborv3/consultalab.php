<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();

if (!$aplicacoes[7]) {
    exit();
} else {
    require_once('dao/labclassecensoDAO.php');
    require_once('classes/labclassecenso.php');
    require_once('dao/laboratorioDAO.php');
    require_once('classes/laboratorio.php');
    require_once('dao/tplaboratorioDAO.php');
    require_once('classes/tplaboratorio.php');
    require_once('dao/tplaboratorioDAO.php');
    require_once('classes/tplaboratorio.php');
    
    $codunidade=$sessao->getCodUnidade();//codigo da unidade do usu��rio
    $anobase=($sessao->getAnobase());
    $daolab = new LaboratorioDAO();
    $daotipolab = new TplaboratorioDAO();
    $daolclasse= new Lab_classecensoDAO();
    $cont = 0;
    $rows_tlab = $daotipolab->Lista($sessao->getAnobase());//classe de validade igual a 2020

    foreach ($rows_tlab as $row) {
        $tiposlab[$cont] = new Tplaboratorio();
        $tiposlab[$cont]->setCodigo($row['Codigo']);
        $tiposlab[$cont]->setNome($row['Nome']);
        $cont++;
    }    

    $contreg = 0;
    $cont1 = 0;
    $locks = array(); // array de lock
    $labs = array(); // array de laboratorios
    $button = true; // bot��o
    $daoun = new UnidadeDAO();
    
    if ($sessao->getCodUnidadeSup()!= 1){
        $unidade = $daoun->queryByUnidadeResponsavel($sessao->getCodUnidadeSup());
        foreach ($unidade as $uni) {
            if ($sessao->getUnidadeResponsavel()==20){
                if ($uni["CodUnidade"]==$sessao->getCodUnidade()){
                    $array_codunidade[] = $uni["CodUnidade"];//Quando for polo, s�� exibe os laborat��rios do polo, array_codunidade tem s�� uma posicao
                }
            } else{
                $array_codunidade[] = $uni["CodUnidade"];
            }
        }
        array_shift($array_codunidade);
        $codigo= $daoun->buscaidunidade($sessao->getCodUnidadeSup());
        
        foreach ($codigo as $cod){
            $codunidaderesp = $cod["CodUnidade"];
        }
        
        $array_codunidade[] = $codunidaderesp;
    }
   
    $rowscodsup = $daoun->RetornaCodUnidadeSuperior($cpga);
    
    foreach ($rowscodsup as $row){
       $codunidadesup = $row['CodUnidade'];
    }   
   
    for ($j = 0; $j < count($array_codunidade); $j++){
        // codigo das unidades registradas no array         
    	//$rows = $daolab->buscaLaboratoriosUnidade($array_codunidade[$j]);
    	$rows = $daolab->buscaLaboratoriosUnidadeAnoD($array_codunidade[$j],$anobase);//busca os labs da unidade
    	 
    	
        foreach ($rows as $row){
            //   $tipo = $row['codtipo'];
            
            // foreach ($tiposlab as $tipolab)             {
                
            //   if ($tipolab->getCodigo() == $tipo)             {                    
            $cont1++;
            // $tplab = $tipolab;
            $lab = new Laboratorio();
            $lab->setCodlaboratorio($row["CodLaboratorio"]); // configura o c��digo do laborat��rio                    
            $lab->setNome($row["nomelab"]); // configura o nome
            $lab->setCapacidade($row["Capacidade"]); // configura a capacidade
            //$lab->setSigla($row["Sigla"]); // sigla do laborat��rio
            //$lab->setLabensino($row["LabEnsino"]); // laborat��rio de ensino
            // $lab->setArea(str_replace(".", ",", $row['Area'])); // ��rea do laborat��rio
            $lab->setSituacao($row["Situacao"]); // situa����o
            //$lab->setAnoativacao($row["AnoAtivacao"]); // ano de ativa����o
            //$lab->setAnodesativacao($row["AnoDesativacao"]); // ano de desativacao
            
            $tipolab=new Tplaboratorio();
            $tipolab->setCodigo( $row['codtipo']);
            $tipolab->setNome( $row['nometipo']);
            
            
            $lab->setTipo($tipolab); // tipo do laborat��rio
            
            //unidade do laboratorio  
            $uni=new Unidade();
            $uni->setCodunidade($row["CodUnidade"]);
            $uni->setNomeunidade($row["NomeUnidade"]);
            $lab->setUnidade($uni);
            $l = new Lock();
            $l->setData($lab);
            // Se o laborat��rio n��o pertencer a unidade ou subunidade
            if ($row["CodUnidade"] != $codunidade) {
                $l->setLocked(true);
            }
            
            // fim
            // Se �� subunidade
            // Bloqueio dos dados
            if (!$sessao->isUnidade()) {
                // Teste se a subunidade possui dados homologados
                $l->setLocked(Utils::isApproved(7, $codunidadesup, $array_codunidade[$j], $lab->getAnoativacao()));
            }
            // Teste se j�� existe dados cadastrados naquele anobase 
            // Se h�� dados de subunidades e o ano base �� igual ao ano de ativa����o
            // bloqueia o bot��o de inser����o
            if ($sessao->isUnidade() && $lab->getAnoativacao() == $anobase && $codunidade != $array_codunidade[$j]) {
                $button = false;
            }
            // Subunidade com dados homologados no ano base
            if (!$sessao->isUnidade() && Utils::isApproved(7, $codunidadesup, $codunidade, $lab->getAnoativacao())
                && $anobase == $lab->getAnoativacao()) {
                $button = false;
            }
            // configura os bloqueios
            $locks[] = $l;
            $cont1++; // contador  
              //  }//if tipo
           // }//foreach tipo
        }
    }
    
    if ($cont1 == 0) {
        Utils::redirect('laborv3', 'incluilab');
    }
} 

echo Utils::deleteModal('Remover Laboratório', 'Você tem certeza que deseja remover o laboratório selecionado?');  ?>

<head>
    <div class="bs-example">
        <ul class="breadcrumb">
            <li class="active">Consultar laboratórios</li>
        </ul>
    </div>
    <?php require_once 'notificacaolabor.php'; ?>
    <br>
</head>

<?php if ($cont1 > 0){ ?>
    <script>
        $(function () {
            $("#exportar_pdf").click(function () {
                $("#pdf").submit();
            });
        });
    </script>

    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Laboratórios Novos</h3>
        </div>
        <form class="form-horizontal" name="pdf" id="pdf" method="POST" action="relatorio/labor/exportarpdf.php">
            <div class="download">
                <ul class="pdf">
                    <li><a href="#" id="exportar_pdf">Exportar em PDF</a></li>
                </ul>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover" id="tabelaLab">
                    <thead>
                        <tr>
                            <th>Unidade</th>
                            <th>Nome </th>
                            <th>Tipo</th>
                            <th>Capacidade</th>
                            <th>Alterar</th>
                            <th>Excluir</th>
                            <th>Situacao</th>
                        </tr>
                    </thead>

                    <tbody> 
                        <?php 
                        for ($i = 0; $i < count($locks); $i++) {
                            $lab1 = $locks[$i]->getData(); ?>
                            <tr>
                                <td><?php print ($lab1->getUnidade()->getNomeunidade()); ?></td>
                                <td><?php print ($lab1->getNome()); ?></td>
                                <td><?php print ($lab1->getTipo()->getNome()); ?></td>
                                <td><?php print ($lab1->getCapacidade()); ?></td>
                                <td align="center">                                                                     
                                    <a
                                    href="<?php echo Utils::createLink('laborv3', 'altlab', array('codlab' => $lab1->getCodlaboratorio(), 'operacao' => 'A')) ?>"                    
                                    target="_self"><img src="webroot/img/editar.gif" alt="Alterar" width="19" height="19" /> </a>
                                </td>
                                <?php 
                                //O locks s�� verifica se foi homologado ou n��o, se foi homologado n��o permite mais alterar        
                                
                                if (!$locks[$i]->getLocked() && $codunidade==$lab1->getUnidade()->getCodUnidade()){ ?>
                                    <td align="center">
                                        <a href="<?php echo Utils::createLink('laborv3', 'dellab', array('codlab' => $lab1->getCodlaboratorio())); ?>" class="delete-link" target="_self"><img src="webroot/img/delete.png" alt="Excluir" width="19" height="19" /> </a>
                                    </td>
                                <?php } else { ?>
                                    <td>       
                                        <button "disabled"  title='Não é possível excluir, pois o laboratório pertence a outra subunidade!' data-trigger='hover'> 
                                            <img src='webroot/img/delete.no.png' alt='Ajuda' data-trigger='hover' width="17" height="17" >
                                        </button>
                                    </td>
                                <?php } ?>
                                <td align="center">
                                    <?php // if (!$locks[$i]->getLocked() ): ?>
                                    <?php 
                                    if ($sessao->getAnobase()<2021 ){ ?>
                                        <?php
                                        $num = $daolab->buscaVinculoLabCurso($lab1->getCodlaboratorio(),$sessao->getAnobase());?>
                                    
                                        <?php foreach ($num as $row){
                                            if($row['qtdCursos'] >= 1){?> 
                                                <a href="<?php echo Utils::createLink('laborv3', 'conslabcurso', array('codlab' => $lab1->getCodlaboratorio())); ?>" 
                                                target="_self"><img src="webroot/img/busca.png"  alt="Visualizar"/> </a>
                                            <?php }else{?>
                                                <a href="<?php echo Utils::createLink('laborv3', 'conslabcurso', array('codlab' => $lab1->getCodlaboratorio())); ?>" target="_self"> 
                                                <img src="webroot/img/add.png"  alt="Adicionar"/> </a>
                                            <?php }
                                            //data-toggle="popover" title="Popover Header" data-content="Some content inside the popover"                        		
                                        }?>
                                    <?php } else{ ?>   
                                        <?php 
                                        if ($lab1->getSituacao()=="V"){
                                            print "<font color='red'><b>Pendência: Vincular curso</b></font>";
                                        }else  if ($lab1->getSituacao()=="C"){
                                            print "<font color='red'><b>Pendência: Definir nova classe</b></font>";
                                        }else  if ($lab1->getSituacao()=="A"){
                                            print "<b>Ativo</b>";
                                        }else  if ($lab1->getSituacao()=="D"){
                                            print "Desativado";
                                        }
                                        ?>   
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div><br/>
        </form>            
        <table class="card-body">
            <tr>
                <td align="center" colspan="7">
                    <?php if ($button){ ?>
                        <form class="form-horizontal" name="fconsultar" method="post" action="<?php echo Utils::createLink('laborv3', 'incluilab'); ?>">
                            <input class="form-control"value="Incluir novo laboratório" class="btn btn-info" type="submit"  id="botao" class="btn btn-info"/>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>
<?php } else {
    print "Nenhum laboratório registrado.";
}
?>

<script>
 $(function () {
    $('#tabelaLab').DataTable({
      "paging": true,
      "sort": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
});
</script>