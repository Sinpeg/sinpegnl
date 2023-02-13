<?php
$sessao = $_SESSION['sessao'];
if (!isset($_SESSION["sessao"])) {
	exit();
}

$daodoc=new DocumentoDAO();

$codunidade= $sessao->getCodunidade();
$anogestao= $sessao->getAnobase();
$rdoc = $daodoc->painelbuscaporRedundancia($codunidade, $anogestao);
?>
<!--
  
<style>

.card {
  /* Add shadows to create the "card" effect */
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
  width: 460px;
  border-radius: 10px;
}

.card2 {
  /* Add shadows to create the "card" effect */
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
  transition: 0.3s;
  width: 100%;
  border-radius: 10px;
}


/* On mouse-over, add a deeper shadow */
.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}
.card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    background-color: #eceff1;
    border-radius: 10px 10px 0px 0px;
    
}
</style>
-->



<div class="bs-example">
    <ul class="breadcrumb">
        <li class="active">Dashboard</li>
    </ul>
</div>

<div class="card card-info">
  <div class="card-header">
    <h3 class="card-title">Dashboard</h3>
  </div>
  <form class="form-horizontal" id="form" method='post' action="<?php echo AJAX_DIR . 'painelmedicao/grafresultado.php'; ?>">

    <table class="card-body">
      <tr>
        <td class="coluna1">Documento</td>
      </tr>
      <tr>
        <td class="coluna2">
          <select class="custom-select" name="documento"> 
            <?php foreach ($rdoc as $row){?>
              <option <?php echo $row['CodUnidade']!=938?'selected':'';  ?> value=<?php print $row["codigo"]; ?>><?php print $row['nome'] ?><?php print ' (' . $row['anoinicial'] . '-' . $row['anofinal'] . ')';
              $anoini=$row["anoinicial"]; 
              ?>
            <?php } ?>
          </select>
        </td>
      </tr>
      <tr>
        <td class="coluna1">Período</td>
      </tr>
      <tr>
        <td class="coluna2">
          <select class="custom-select" name="periodo"> 
            <?php for ($i=$anoini;$i<=$anogestao;$i++) { ?> 
              <!-- <option value=" // echo $i;?>/1"> // echo $i;?>/1</option> -->
              <option value="<?php echo $i;?>/2"><?php echo $i;?></option>
            <?php } ?>
          </select>
        </td>
      </tr>
    </table>

    <table class="card-body">
      <tr>
        <td align="center">
          <br>
          <input class="btn btn-info" type="submit"  value="Visualizar" id="resposta-ajax-doc1" class="btn btn-info btn" />
        </td>
      </tr>
    </table>
    <br>
  </form>
</div>
<br>
<div id="exibicao"></div>
<script>
    /*  $('div#resultado').empty();
        $.ajax({url: "ajax/resultadopdi/tabresultado.php", type: 'POST', data:$('form[name=pdi-resultado]').serialize(), success: function(result){
            $("div#resultado").html(result);
        }});*/
        
    $('#resposta-ajax-doc1').click(function(event) {
      event.preventDefault();
      $("#exibicao").html("");
      $("#exibicao").addClass("ajax-loader");
      $.ajax({
        url: $("#form").attr("action"),
        type: "POST",
        data: $("form").serialize(),
        success: function(data) {
            //alert(data);
            $("#exibicao").html(data);
            $("#exibicao").removeClass("ajax-loader");
        }
      });
    });
</script>



