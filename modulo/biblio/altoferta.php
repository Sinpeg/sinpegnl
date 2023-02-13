<?php
//session_start();//aqui
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();

if (!$aplicacoes[45]) {
    header("Location:index.php");
    exit();
} else {
 
	
	require_once('dao/blofertaDAO.php');
	require_once('dao/localofertaDAO.php')/
	require_once('classes/localoferta.php');
	require_once('classes/bibliemec.php');
	require_once('classes/Bloferta.php');
	require_once('dao/biblioEmecDAO.php');
	require_once('dao/bibliocensoDAO.php');
	
    $b = array(); // tipo da estrutura de acessibilidade
    $daobo = new BlofertaDAO();
    
    $daolo = new LocalofertaDAO();
    
    $cont = 0; // contador
    $selecionado = 0;
    
    
    $daobe = new BiblioEmecDAO();
    $cont = 0; // contador
    
    
    //verifica se a unidade biblio j� est� associada com a biblio do emec
    $rows = $daobe->buscaCodEmecBiblioUnidade($codunidade);
    
    
    if ($rows->rowCount()>0){
    	foreach ($rows as $row) {
    		//$idbiblicenso=$row['idbiblicenso'];;
    		$nome =$row['nome'];;
    		$codEmec=$row['codEmec'];
    		$idBibliemec=$row['idBibliemec'];
    	}
    }
    $dao = new BibliocensoDAO();
    $rows=$dao->buscaBibli($idBibliemec, $sessao->getAnobase());
    if ($rows->rowCount()>0){
    	foreach ($rows as $row) {
    		//$idbiblicenso=$row['idbiblicenso'];;		
    		$idbiblicenso=$row['idBiblicenso'];
    	}
    }
    $be =new Bibliemec();
    $be->setIdBibliemec($idBibliemec);
    $be->setCodEmec($codEmec);
    
     
    $rows = $daolo->Lista1();
   
    
    if (is_numeric($idBibliemec)){
    	
    foreach ($rows as $row) {
    	$cont++;
    	$b[$cont] = new Localoferta();
    	$b[$cont]->setIdLocal($row['idLocal']);
    	$b[$cont]->setNome($row['nome']);
    
    }
    
    
    	$rows = $daobo->buscaporIdbibliemc($idBibliemec);
    	foreach ($rows as $row){
   		
    		for ($i=1;$i<=$cont;$i++){
    			if ($b[$i]->getIdLocal()==$row['idloferta']){
    				//$blo->setLocaloferta($b[$i]);//� um obj
    				
    				$b[$i]->criaBloferta($be);

    			}	
    		}//for
    	}//for
    }    
    	
        

        

 //   $daobo->fechar();
  //  $daolo->fechar();
    
}
?>
<script type="text/javascript">

    function direciona(botao) {
        if (botao == 1) {
            document.biblio.action = "?modulo=biblio&acao=opOferta";
            document.biblio.submit();
        }else if (botao == 2) {
        	document.biblio.action = "?modulo=biblio&acao=altbiblicenso";
            document.biblio.submit();
         }
        else  {
            document.biblio.action = "../saida/saida.php";
            document.biblio.submit();
        }
    }
</script>

<form class="form-horizontal" name="biblio" id="biblio" method="post">
      
	<h4>Selecione os locais de oferta atendidos pela  <?php echo $nome;   ?> (<?php echo $codEmec; ?>)</h4>
	<input class="form-control"type="hidden" name="idBibliemec"  value="<?php echo $idBibliemec; ?>">
		<input class="form-control"type="hidden" name="idbiblicenso"  value="<?php echo $idbiblicenso; ?>">
		<input class="form-control"type="hidden" name="nome"  value="<?php echo $nome; ?>">
		<input class="form-control"type="hidden" name="codemec"  value="<?php echo $codEmec; ?>">
		
	<table>
		<tr align="center" style="font-style: italic;">
			<td></td>
			<td>C&oacute;digo Emec</td>
			<td>Local de oferta</td>
		</tr>
        <?php  for ($i = 1; $i <= $cont; $i++) {  ?>
            <tr>
			<td align="center">
			<input type="checkbox" class="form-check-input" name="loferta[<?php echo $b[$i]->getIdLocal();?>]"
					<?php 
                if (!is_null($b[$i]->getBloferta())){
                	     echo "checked";
                }
                ?>></td>

			<td> <?php echo ($b[$i]->getIdLocal()); ?></td>
			<td> <?php echo ($b[$i]->getNome()); ?></td>
		</tr>
                                        
         <?php   }//for
             ?>
    </table>
	<br /> <input type="button" onclick='direciona(1);' value="Gravar"
		class="btn btn-info" />
    <?php 
    if ($selecionado!=0){?>
        <input type="button" onclick='direciona(2);' value="Continuar"
		class="btn btn-info" />
    <?php } ?>
    
</form>

