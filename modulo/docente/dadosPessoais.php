<div class="panel panel-primary">

  <div class="panel-heading titulo">Dados Pessoais</div>      

 
 <table class="tableBody">
 <?php 
 $bloco_1 = substr($login,0,3);
 $bloco_2 = substr($login,3,3);
 $bloco_3 = substr($login,6,3);
 $dig_verificador = substr($login,-2);
 $cpf_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."-".$dig_verificador;
 ?>
 
<tr><td>CPF</td><td><input class="form-control"type="text" class='form-control' name="cpf2" id="cpf2" disabled size="60" value="<?php print $cpf_formatado; ?>"<input class="form-control"type="hidden" class='form-control' name="cpf" id="cpf" disabled size="60" value="<?php print $login; ?>" /></td></tr>


<tr><td>Data de Nascimento</td>
 
 <td>
 <input class="form-control"placeholder="Data de Nascimento" type="text" id="calendario" name="calendario" 
                                          class='short form-control'  maxlength="10" size="10"
                                         value=" <?php print prepararDataForm( $r['dtnascimento']); ?>" />
                                         </td>
 </tr>
 
 
 
<tr><td>Nome, que consta no Cadastro de Pessoa Física</td><td><input class="form-control"type="text" class='form-control' name="nome"  maxlength="100" value="<?php print $r['nome']; ?>" /></td></tr>
<tr><td>Sexo</td><td>
        <select class='form-control'  name="sexo">
        <option <?php echo $r['sexo']==1?'selected':"";?> value="0">Masculino</option>
  <option <?php echo $r['sexo']==0?'selected':"";?> value="1">Feminino</option>
</select> 
</td></tr>

<tr><td>Cor/raça</td><td>
<select class='form-control'  name="cor">
  <option <?php echo $r['cor']=="0"?'selected':"";?> value="0">Docente não quis declarar cor/raça</option>
  <option <?php echo $r['cor']=="1"?'selected':"";?> value="1">Branca</option>
  <option <?php echo $r['cor']=="2"?'selected':"";?> value="2">Preta</option>
  <option <?php echo $r['cor']=="3"?'selected':"";?> value="3">Parda</option>
  <option <?php echo $r['cor']=="4"?'selected':"";?> value="4">Amarela</option>
  <option <?php echo $r['cor']=="5"?'selected':"";?> value="5">Indígena</option>
</select></td></tr>

<tr><td>País de Origem</td><td>
<select class="custom-select" name="pais"  class='form-control'  id="pais">
   <?php  foreach ($paises as $key => $value) { ?>
            
               <option <?php  echo $key==$r['paisorigem']?"selected":"";?>  value="<?php print  $key;?>"><?php print $value;?></option>
              
          
              <?php     }
            ?>
</select>
</td></tr>
 <tr><td>Nacionalidade</td><td>
<select class='form-control'  name="nacionalidade" id="nacionalidade">
  <option  value="0">Selecione nacionalidade</option>
  <option <?php echo $r['nacionalidade']=="1"?'selected':"";?> value="1">Brasileira nata</option>
  <option <?php echo $r['nacionalidade']=="2"?'selected':"";?> value="2">Brasileira por naturalização</option>
  <option <?php echo $r['nacionalidade']=="3"?'selected':"";?> value="3">Estrangeira</option>
</select>
</td></tr>
<tr><td>Documento Estrangeiro</td><td><input class="form-control"type="text" class='form-control' id="passaporte" name="passaporte" value="<?php echo $r['passaporte'];?>"/></td></tr>
<tr><td>UF de origem</td><td>
    <select class="custom-select" name='uf'  class='form-control'  id="uf">
     <option  value=0>Selecione a UF, se país de nascimento, Brasil</option>
               <?php   foreach ($ufs as $key => $value) { ?>
                         		<option  <?php  echo $key==$r['ufnascimento']?"selected=selected":"";?> value="<?php echo $key;?>"><?php echo $value;?></option>
                        <?php }?>
                      </select></td></tr>

<tr><td>Município</td><td>
<?php 

//Municipio de nascimento
$rows = $daodoc->buscaMunicipio($r['ufnascimento']);
$mun = array();
foreach ($rows as $row) {
    $mun[$row['idMunicipio']]=$row['nome'];
}


?>


                <div id="munselect">
                <select class="custom-select" name='mun'  class='form-control'  id="mun">
                     <option  value=0>Selecione a município, se país de nascimento, Brasil</option>
               <?php   foreach ($mun as $key => $value) { ?>
                         		<option  <?php  echo $key==$r['munnascimento']?"selected=selected":"";?> value="<?php echo $key;?>"><?php echo $value;?></option>
                        <?php }?>
                      </select>
                
                
                </div></td></tr>
<tr><td>Docente com deficiência, TGD/TEA ou altas habilidades/superdotação</td>
<td>
<select class='form-control'  name="temdeficiencia" id="temdeficiencia">
  <option   value="A">Selecione uma opção...</option>
  <option <?php echo $r['pnd']=="0"?'selected':"";?>  value="0">Não</option>
  <option <?php echo $r['pnd']=="1"?'selected':"";?>  value="1">Sim</option>
  <option <?php echo $r['pnd']=="2"?'selected':"";?>  value="2">Não dispõe da informação</option>
</select>
</td></tr>

<tr><td>Tipo:</td><td></td></tr>
 <tr> <td>
 <input class="form-control"type = "checkbox" <?php echo $r['cegueira']=="1"?'checked':"";?> name = "cegueira" id= "cegueira" value = "1"> Cegueira</td>
 <td><input class="form-control"type = "checkbox" <?php echo $r['baixavisao']=="1"?'checked':"";?> name = "baixavisao" id="baixavisao" value = "2"> Baixa visão</td></tr>
 
 
  <tr><td><input class="form-control"type = "checkbox" <?php echo $r['surdez']=="1"?'checked':"";?> name = "surdez" id = "surdez" value = "3"> Surdez</td>
  <td><input class="form-control"type = "checkbox" <?php echo $r['auditiva']=="1"?'checked':"";?> name = "auditiva" id = "auditiva" value = "4"> Deficiência Auditiva</td></tr>
  
  
 <tr><td><input class="form-control"type = "checkbox" <?php echo $r['fisica']=="1"?'checked':"";?> name = "fisica" id = "fisica" value = "5"> Física</td>
 <td><input class="form-control"type = "checkbox" <?php echo $r['surdocegueira']=="1"?'checked':"";?> name = "surdocegueira" id = "surdocegueira" value = "6"> Surdocegueira</td></tr>
 
 
  
 <tr><td><input class="form-control"type = "checkbox" <?php echo $r['mental']=="1"?'checked':"";?> name = "mental" id = "mental" value = "7"> Deficiência Intelectual</td>
 <td><input class="form-control"type = "checkbox" <?php echo $r['autismoinfantil']=="1"?'checked':"";?> name = "autismoinfantil"  id = "autismoinfantil" value = "8"> Transtorno global do desenvolvimento (TGD)/Transtorno do Espectro Autista (TEA)</td></tr>
 <tr><td><input class="form-control"type = "checkbox" <?php echo $r['altashabilidades']=="1"?'checked':"";?> name = "altashabilidades" id = "altashabilidades" value = "9"> Altas habilidades/ superdotação</td><td></td></tr>

 </table>
 </div>
 
