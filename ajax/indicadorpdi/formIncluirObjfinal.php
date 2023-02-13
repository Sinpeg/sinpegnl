<table>
 <tr><td>Documento:</td><td><input type='text' disabled class='form-control' value="<?php echo $nomeDoc;?>" name="documento" id="documento">
       <input type='hidden'  value="<?php echo $coddoc;?>" name='coddoc' id='coddoc'>	
       <input type='hidden'  value="<?php echo $tipo;?>" name='tipoSolicitacaoB' id='tipoSolicitacaoB'></td></tr>
 <tr><td>Objetivo: </td><td style="padding-left=10%;"><input type='text' disabled class='form-control' value="<?php echo $nomeobj;?>" name='nomeObj' id='nomeObj'></td></tr>
  	
  	  <?php  foreach ($rows as $r){?>
<tr><td>Indicador:</td><td><input type="text" disabled class='form-control' name="indicador[]" value="<?php echo $r['nome'];?>"></td></tr>
<tr><td>Métrica:</td><td><input type="text" disabled class='form-control' name="metrica[]" value="<?php echo $r['metrica'];?>"></td></tr>
  	  
			  	<?php echo ($r['meta1']!=NULL)?"<tr><td>Meta ".$anoinicial.":</td><td>".str_replace('.', ',', $r['meta1'])."</td></tr>":"";?>
			  	<?php echo ($r['meta2']!=NULL)?"<tr><td>Meta ".($anoinicial+1).":</td><td>".str_replace('.', ',', $r['meta2'])."</td></tr>":"";?>
			  	<?php echo ($r['meta3']!=NULL)?"<tr><td>Meta ".($anoinicial+2).":</td><td>".str_replace('.', ',', $r['meta3'])."</td></tr>":"";?>
			  	<?php echo ($r['meta4']!=NULL)?"<tr><td>Meta ".($anoinicial+3).":</td><td>".str_replace('.', ',', $r['meta4'])."</td></tr>":"";?>
	<?php }?>
<tr><td>Justificativa:</td>
<td><textarea disabled class="form-control"  name="justInsObj"  " rows="5" cols="10"><?php echo $row['justificativa'];?></textarea></td></tr>
<tr><td>Anexo RAT:</td><td><a href="<?php echo "../public/solicitacoes/".$row['anexo']?>" id="arquivoDadosE"><img width="30" src="webroot/img/download.gif"/></a></td></tr>
	
	
	
	
	
<?php  	 if (($tipousuario=='C') && ($row['situacao']=="A") ){?> 	
       		        								  
        		  <tr id="tr_cancela13" ><td>Situação:</td><td><select class="form-control" id="situacaoIO"  name="situacaoIO">
        	      <option value="">O usuário pode cancelar a solicitação</option>
        	
        	      <option <?php echo $row['situacao']=="A"?"selected":"";?> value="A">Aberta</option>
        		  <option <?php echo $row['situacao']=="C"?"selected":"";?> value="C">Cancelada</option></select></td></tr>
      <?php } else if ($tipousuario=='C') {?>
                 <tr id="tr_deferido13"><td>Situação Atual:</td><td><input name="sitfinal" class='form-control' id="sitfinal" type="text"  disabled 	value="<?php echo $situacao;?>">
            			        								  
        		  <?php }      
 	     
  
       if ($tipousuario=='A' || $tipousuario=='D'){
       		if ($row['situacao']=="A" || $row['situacao']=="I"  ){	?>
       		<tr id="tr_analisa13"><td>Situação:</td><td><select class="form-control" id="situacaoIO" name="situacaoIO">
       	              <option value="">Informe situação...</option>
       	 
        			  <option <?php echo $row['situacao']=="A"?"selected":"";?> value="A">Aberta</option>
        			  <option <?php echo $row['situacao']=="D"?"selected":"";?> value="D">Deferido</option>
        			  <option <?php echo $row['situacao']=="I"?"selected":"";?> value="I">Indeferido</option>
        			  </select></td></tr>
       
       <?php }else if  ( $row['situacao']=="G"){?>
       
              	 <tr id="tr_deferido13"><td>Situação Atual:</td><td><input name="sitfinal" class='form-control' id="sitfinal" type="text"  disabled 	value="<?php echo $situacao;?>">
       
       
       
       <?php if ($sessao->getCodusuario()==159 || $sessao->getCodusuario()==52){?>
       	<tr id="tr_analisa13"><td>Situação:</td><td><select class="form-control" id="situacaoIO" name="situacaoIO">
       	              <option value="">Informe situação...</option>
       	 
        			  <option <?php echo $row['situacao']=="A"?"selected":"";?> value="A">Aberta</option>
        			  <option <?php echo $row['situacao']=="D"?"selected":"";?> value="D">Deferido</option>
        			  <option <?php echo $row['situacao']=="I"?"selected":"";?> value="I">Indeferido</option>
        			  </select></td></tr>
       	
       <?php }
       }	else { 	?>
       	 <tr id="tr_deferido13"><td>Situação Atual:</td><td><input name="sitfinal" class='form-control' id="sitfinal" type="text"  disabled 	value="<?php echo $situacao;?>">
       	
      <?php  }
       }
      
       
       ?>
        	</td></tr>
        
  	
  	
        			     
<tr><td>Comentários:</td><td>

<?php if (empty($htmlC)){?>
		<div id="iframeIO" style="overflow: auto;height: 130px; border:solid 1px;border-color: #E6E6E6;">
		<textarea class="form-control" disable id="comentarioIO" rows="5" cols="10" > </textarea>
		</div>
<?php }else{?>
		<div id="iframeIO" style="overflow: auto;height: 130px; border:solid 1px;border-color: #E6E6E6;">
		<?php echo $htmlC; ?>
		</div>
<?php  } ?></td></tr>

<tr id="tr_comentario1"><td>Comentar:</td><td><textarea class="form-control" id="comentarioIO"> </textarea></td></tr>	
</table>
                
                
                
                
                 
             