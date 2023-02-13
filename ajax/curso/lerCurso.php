<?php
  require_once '../../classes/sessao.php';

sleep(1);
session_start();
$sessao = $_SESSION["sessao"];
require_once '../../dao/PDOConnectionFactory.php';
require_once '../../dao/cursoDAO.php';
$parametro=$_POST["keyword"];
$anobase=$sessao->getAnobase();
$dao=new CursoDAO();

if(!empty($parametro)) {

 if (isset($sessao) && $sessao->getCodUnidade()!=100000){

     $result = $dao->cursoPorStrUni($parametro, $anobase,$sessao->getCodUnidade());
  }else{
      $result = $dao->cursoPorStr($parametro, $anobase);

   }

  //$result = $daouni->unidadePorStr($parametro, 15);

    if(!empty($result)) { //compoe a lista para selecao da unidade?>
       <ul class="itens" id="curso-list">
                <?php foreach($result as $c) { ?>
                <li><span class="subitem"><?php echo $c["NomeCurso"]; ?></span></li>
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
     $("#cxcurso").val(item);
     $("#cxcurso").text(item);
     $("#suggesstion-box2").hide();
     $("#cxcurso").focus();
     $("#insere").hide();
     $("#altera").show();
     $.ajax({
		    type: "POST",
		    url: "ajax/curso/buscacursosel.php",
	        data: $("form[name=formcurso]").serialize(),


		    success: function(response){
			    $("div#altera").html(response);
		       }
   });
     });





 });
</script>
