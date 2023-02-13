<?php
$sessao = $_SESSION['sessao'];
if ($sessao->getCodUnidade()==938){
?>

<style>
#unid-list{float:left;list-style:none;margin-top:-3px;padding:0;width:520px;position: absolute;height: 50px;}
#unid-list li{padding: 5px;salvar background: #00008B; border-bottom: #bbb9b9 1px solid;}
#unid-list li:hover{background:#A9A9A9;cursor: pointer;}
#cxunidade{padding: 5px;border: #a8d4b1 1px solid;border-radius:4px;width: 520px;height: 25px;}
</style>
<form class="form-horizontal"  name="vincpdi" method=post action="<?php echo Utils::createLink('indicadorpdi', 'regvincularindicador938');?>">
	<div class="bs-example">
		<ul class="breadcrumb">
			<li class="active"><a href="<?php echo Utils::createLink('indicadorpdi', 'consultaindicadorproprio'); ?>" >Vincular Indicadores</a>  >> <a href="#" >Selecionar Unidade Responsável pelo Indicador</a></li>  
		</ul>
	</div>
	
	<h3 class="card-title">Vincular Unidade Responsável</h3>
    <hr style="border-top: 1px solid #0b559b;"/>
<table>
 <tr><td><label>Unidade</label> </td>
      <td>   <input class="form-control" type="text" id="cxunidade"  name="cxunidade" placeholder="Unidade" autocomplete="off"/>
       	<div id="suggesstion-box"></div></td>
               
               
                </tr>
                </table>
                
    
<input class="form-control"type="hidden" name="ind" value="<?php echo $_POST['ind']; ?>" />
<input class="form-control"type="hidden" name="mapa" value="<?php echo $_POST['mapa']; ?>" />
<input class="form-control"type="hidden" name="des" value="<?php echo $_POST['des']; ?>" />

                  <input class="btn btn-info" type="submit"  value="Finalizar Vínculo" name="vincind" class="btn btn-info"/>		      
    
                
</form>
<?php } ?>