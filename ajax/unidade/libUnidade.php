<?php

require_once '../../classes/sessao.php';

sleep(1);
session_start();
$sessao = $_SESSION["sessao"];
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/unidadeDAO.php';
$parametro=$_POST["keyword"];

$daouni=new UnidadeDAO();
if(!empty($parametro)) {

 if ($sessao->getCodUnidade()==100000){
  	      $result = $daouni->unidadePorStr($parametro, 15);

  }else{
	$hier=$sessao->getCodestruturado();
	//echo $sessao->getCodUnidade()." kkk ".$parametro."  ".$sessao->getCodestruturado();die;

     $result = $daouni->subunidadePorStr($parametro, $hier, 15);
   }


    if(!empty($result)) { //compoe a lista para selecao da unidade?>
       <ul class="itens" id="unid-listl">
                <?php foreach($result as $uni) { ?>
                <li><span class="subitem"><?php echo $uni["NomeUnidade"]; ?></span></li>
               <?php  } ?>
        </ul>
<?php }

} ?>

 <script>


$(document).ready(function()
 {
    $('ul.itens li').click(function(e)
    {
        var item=$(this).find("span.subitem").text();
     $("#libunidade").val(item);
     $("#libunidade").text(item);
     $("#cxsugestao0").hide();
     $("#libunidade").focus();

  /*   $.ajax({
		    type: "POST",
		    url: "ajax/unidade/buscaunisel.php",
	        data: $("form[name=formlibunidade]").serialize(),


		    success: function(response){
			    $("div#altera").html(response);
		       }
   });*/

     
     });




 });
</script>
