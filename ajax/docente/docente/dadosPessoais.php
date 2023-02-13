<tr><td><label>CPF</label></td><td><input type="text" class='form-control' name="cpf" disabled size="60" value="<?php print $login; ?>" /></td></tr>


<tr><td><label>Data de Nascimento</label></td>
 
 <td>
 <input placeholder="Data de Nascimento" type="text" id="calendario" name="calendario" 
                                          class='short'  maxlength="10" size="10"
                                         value=" <?php print prepararDataForm( $r['dtnascimento']); ?>" />
                                         </td>
 </tr>
 
 
 
<tr><td><label>Nome, que consta no Cadastro de Pessoa Física</label></td><td><input type="text" class='form-control' name="nome"  maxlength="100" value="<?php print $r['nome']; ?>" /></td></tr>
<tr><td><label>Sexo</label></td><td>
        <select class='form-control'  name="sexo">
        <option <?php echo $r['sexo']==1?'selected':"";?> value="0">Masculino</option>
  <option <?php echo $r['sexo']==0?'selected':"";?> value="1">Feminino</option>
</select> 
</td></tr>

<tr><td><label>Cor/raça</label></td><td>
<select class='form-control'  name="cor">
  <option <?php echo $r['cor']=="0"?'selected':"";?> value="0">Docente não quis declarar cor/raça</option>
  <option <?php echo $r['cor']=="1"?'selected':"";?> value="1">Branca</option>
  <option <?php echo $r['cor']=="2"?'selected':"";?> value="2">Preta</option>
  <option <?php echo $r['cor']=="3"?'selected':"";?> value="3">Parda</option>
  <option <?php echo $r['cor']=="4"?'selected':"";?> value="4">Amarela</option>
  <option <?php echo $r['cor']=="5"?'selected':"";?> value="5">Indígena</option>
</select></td></tr>

<tr><td><label>País de Origem</label></td><td>
<select name="pais"  class='form-control'  id="pais">
   <?php  foreach ($paises as $key => $value) { ?>
            
               <option <?php  echo $key==$r['paisorigem']?"selected":"";?>  value="<?php print  $key;?>"><?php print $value;?></option>
              
          
              <?php     }
            ?>
</select>
</td></tr>
 <tr><td><label>Nacionalidade</label></td><td>
<select class='form-control'  name="nacionalidade" id="nacionalidade">
  <option  value="0">Selecione nacionalidade</option>
  <option <?php echo $r['nacionalidade']=="1"?'selected':"";?> value="1">Brasileira nata</option>
  <option <?php echo $r['nacionalidade']=="2"?'selected':"";?> value="2">Brasileira por naturalização</option>
  <option <?php echo $r['nacionalidade']=="3"?'selected':"";?> value="3">Estrangeira</option>
</select>
</td></tr>
<tr><td><label>Documento Estrangeiro</label></td><td><input type="text" class='form-control' id="passaporte" name="passaporte" value="<?php echo $r['passaporte'];?>"/></td></tr>
<tr><td><label>UF de origem</label></td><td>
    <select name='uf'  class='form-control'  id="uf">
     <option  value=0>Selecione a UF, se país de nascimento, Brasil</option>
               <?php   foreach ($ufs as $key => $value) { ?>
                         		<option  <?php  echo $key==$r['ufnascimento']?"selected=selected":"";?> value="<?php echo $key;?>"><?php echo $value;?></option>
                        <?php }?>
                      </select></td></tr>

<tr><td><label>Município</label></td><td>
<?php 

//Municipio de nascimento
$rows = $daodoc->buscaMunicipio($r['ufnascimento']);
$mun = array();
foreach ($rows as $row) {
    $mun[$row['idMunicipio']]=$row['nome'];
}


?>


                <div id="munselect">
                <select name='mun'  class='form-control'  id="mun">
                     <option  value=0>Selecione a município, se país de nascimento, Brasil</option>
               <?php   foreach ($mun as $key => $value) { ?>
                         		<option  <?php  echo $key==$r['munnascimento']?"selected=selected":"";?> value="<?php echo $key;?>"><?php echo $value;?></option>
                        <?php }?>
                      </select>
                
                
                </div></td></tr>
<tr><td><label>Docente com deficiência, TGD/TEA ou altas habilidades/superdotação</label></td>
<td>
<select class='form-control'  name="temdeficiencia" id="temdeficiencia">
  <option   value="A">Selecione uma opção...</option>
  <option <?php echo $r['pnd']=="0"?'selected':"";?>  value="0">Não</option>
  <option <?php echo $r['pnd']=="1"?'selected':"";?>  value="1">Sim</option>
  <option <?php echo $r['pnd']=="2"?'selected':"";?>  value="2">Não dispõe da informação</option>
</select>
</td></tr>

<tr><td><label>Tipo</label></td><td></td></tr>
 <tr> <td>
 <input type = "checkbox" <?php echo $r['cegueira']=="1"?'checked':"";?> name = "cegueira" id= "cegueira" value = "1"> Cegueira</td>
 <td><input type = "checkbox" <?php echo $r['baixavisao']=="1"?'checked':"";?> name = "baixavisao" id="baixavisao" value = "2"> Baixa visão</td></tr>
 
 
  <tr><td><input type = "checkbox" <?php echo $r['surdez']=="1"?'checked':"";?> name = "surdez" id = "surdez" value = "3"> Surdez</td>
  <td><input type = "checkbox" <?php echo $r['auditiva']=="1"?'checked':"";?> name = "auditiva" id = "auditiva" value = "4"> Deficiência Auditiva</td></tr>
  
  
 <tr><td><input type = "checkbox" <?php echo $r['fisica']=="1"?'checked':"";?> name = "fisica" id = "fisica" value = "5"> Física</td>
 <td><input type = "checkbox" <?php echo $r['surdocegueira']=="1"?'checked':"";?> name = "surdocegueira" id = "surdocegueira" value = "6"> Surdocegueira</td></tr>
 
 
  
 <tr><td><input type = "checkbox" <?php echo $r['mental']=="1"?'checked':"";?> name = "mental" id = "mental" value = "7"> Deficiência Intelectual</td>
 <td><input type = "checkbox" <?php echo $r['autismoinfantil']=="1"?'checked':"";?> name = "autismoinfantil"  id = "autismoinfantil" value = "8"> Transtorno global do desenvolvimento (TGD)/Transtorno do Espectro Autista (TEA)</td></tr>
 <tr><td><input type = "checkbox" <?php echo $r['altashabilidades']=="1"?'checked':"";?> name = "altashabilidades" id = "altashabilidades" value = "9"> Altas habilidades/ superdotação</td><td></td></tr>

 
 
