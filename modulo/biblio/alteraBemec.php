<?php
//session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
$codunidade = $sessao->getCodUnidade();

if (!$aplicacoes[45]) {
    header("Location:index.php");
    exit();
} else {
	
  //  require_once('dao/biblioEmecDAO.php');
  //  require_once('classes/bibliemec.php');
    
    require_once('dao/biblioEmecDAO.php');
    require_once('classes/bibliemec.php');
        
    $daouni = new UnidadeDAO();
    $b = array(); // tipo da estrutura de acessibilidade
    $daobe = new BiblioEmecDAO();
    $cont = 0; // contador
    $selecionado = 0;
    //verifica se a unidade biblio j� est� associada com a biblio do emec
    $rows = $daobe->buscaCodEmecBiblioUnidade($codunidade);

    
     if ($rows->rowCount()>0){
       foreach ($rows as $row) {
    	$selecionado = $row['idBibliemec'];
       }
   }
        
   
    	$rows = $daobe->Lista();
    	 
    	foreach ($rows as $row) {
          $cont++;
       	  $b[$cont] = new Bibliemec();
    	  $b[$cont]->setIdBibliemec($row['idBibliemec']);
    	  $b[$cont]->setCodEmec($row['codEmec']);
    	  $b[$cont]->setNome($row['nome']);
    	   
    	  if (!is_null($row['idUnidade'])){
    	     $rowsu = $daouni->unidadeporunidresp($row['idUnidade']);
   	     
    	     foreach ($rowsu as $unir) {
    	     	 	 $b[$cont]->setSigla($unir['unidresp']);
    	     }//foreach
    	  }//if*/

        }//foreach

        
   // $daobe->fechar();
    
}
?>

<script type="text/javascript">

    function direciona(botao) {
        if (botao == 1) {
            document.biblio.action = "?modulo=biblio&acao=opBemec";
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
    <h3 class="card-title">Selecione a biblioteca do Emec correspondente &agrave; sua unidade:</h3>
    <table>
        <tr align="center" style="font-style:italic;">
            <td></td>
         <!--    <td>C&oacute;digo Emec</td>  -->
                <td>Nome da Biblioteca</td>  
                <td>Unid. Respons&aacute;vel</td>
         </tr>
        <?php 
          for ($i = 1; $i <= $cont; $i++) {
 ?>
                
            <tr>

   <td align="center">

<input class="form-control"type="radio" name="idbibliemec"   value="<?php echo $b[$i]->getIdBibliemec(); ?>"  <?php 
echo "XXXX2";

                if ($b[$i]->getIdBibliemec()==$selecionado){
                	     echo "checked";
                }
                ?>>       
                </td>

        <!--         <td> <?php //echo $b[$i]->getCodEmec(); ?></td> -->
                  <td> <?php echo  $b[$i]->getNome(); ?></td>
                <td> <?php echo  $b[$i]->getSigla(); ?>
                      </tr>
                                        
<?php   }//for
             ?>
    </table>
    <br/> <input type="button" onclick='direciona(1);' value="Gravar" class="btn btn-info"/>
    <?php 
    if ($selecionado!=0){?>
        <input type="button" onclick='direciona(2);' value="Continuar" class="btn btn-info"/>
    <?php } ?>
    
</form>

