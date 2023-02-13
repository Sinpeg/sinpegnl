<tr><td><label>Escolaridade exigida pelo cargo ocupado</label></td><td>             
            
            
            
            
               <select class='form-control' name="escolaridade"  id="escolaridade">
                  <option <?php echo $r['escolaridade']=="1"?'selected':"";?> value="1">Sem formação de nível superior</option>
                  <option <?php echo $r['escolaridade']=="3"?'selected':"";?> value="3">Nível superior sem pós graduação</option>
                   <option <?php echo $r['escolaridade']=="4"?'selected':"";?> value="4">Especialização</option>
                  <option <?php echo $r['escolaridade']=="5"?'selected':"";?> value="5">Mestrado</option>
                  <option <?php echo $r['escolaridade']=="6"?'selected':"";?> value="6">Doutorado</option>
               </select>
               
           
               
               </td> </tr>
            <tr> <td><label>Situação do Docente na IES</label></td><td>             
               <select class='form-control' name="sitdocente" id="sitdocente">
                  <option <?php echo $r['situacao']==1?'selected':"";?> value="1">Esteve em exercício no mínimo por 60 dias de <?php echo $anobase;?></option>
                  <option <?php echo $r['situacao']==2?'selected':"";?> value="2">Afastado para qualificação</option>
                  <option <?php echo $r['situacao']==3?'selected':"";?> value="3">Afastado para exercício em outros órgãos/entidades</option>
                   <option <?php echo $r['situacao']==4?'selected':"";?> value="4">Afastado por outros motivos</option>
                  <option  <?php echo $r['situacao']==5?'selected':"";?> value="5">Afastado para tratamento de saúde</option>
               </select></td> </tr> 
                          
             <tr> <td><label>Docente esteve em exercício  em 31/12?</label></td><td>   
              <input type = "checkbox"  <?php echo $r['exercicio3112']=="1"?'checked':"";?> name = "exercicio3112" value = "0">
             
             
                           
                  <tr> <td><label>Regime de trabalho</label></td><td>             
               <select class='form-control' name="regime">
              <option <?php echo $r['regime']=="1"?'selected':"";?> value="1">Tempo integral com DE</option>
              <option <?php echo $r['regime']=="2"?'selected':"";?> value="2">Tempo Integral sem DE</option>
              <option <?php echo $r['regime']=="3"?'selected':"";?> value="3">Tempo Parcial</option>
              <option <?php echo $r['regime']=="4"?'selected':"";?> value="4">Horista</option>
               </select></td> </tr>            
                           
                <tr> <td><label><input type = "checkbox"   <?php echo $r['substituto']=="1"?'checked':"";?>  name = "substituto" value = "0"> Docente Substituto</label></td>             
             <td><label><input type = "checkbox"  <?php echo $r['visitante']=="1"?'checked':"";?> name = "visitante" value = "0"> Docente Visitante   </label>            
              </td> </tr>   
                <tr> <td><label>Tipo de vínculo de docente visitante à IES </label></td><td>             
                 <select class='form-control' name="regime">
              <option  <?php echo $r['vinculovisitante']=="1"?'selected':"";?> value="1">Em folha</option>
              <option <?php echo $r['vinculovisitante']=="2"?'selected':"";?>  value="2">Bolsista</option>
               </select>
            
               </td> </tr>   
               
               <tr><td><label>Atuação</label></td><td></td></tr>
                  <tr><td><input type = "checkbox" <?php echo $r['atpossspresencial']=="1"?'checked':"";?> name = "atpossspresencial" value = "0"> Ensino de pós-graduação stricto sensu presencial</td>
                  <td><input type = "checkbox" <?php echo $r['atposssEAD']=="1"?'checked':"";?> name = "atposssEAD" value = "0"> Ensino de pós-graduação stricto sensu a distância</td></tr>
                  
                  
                  <tr>
                  <td><input type = "checkbox"  <?php echo $r['atpesquisa']=="1"?'checked':"";?> name = "atpesquisa" value = "0"> Pesquisa</td>
                  <td><input <?php echo $r['bolsapesquisa']=="1"?'checked':"";?> type = "checkbox"  name = "bolsapesquisa" value = "0"> Com Bolsa de Pesquisa</td></tr>
                  
                  <tr><td><input type = "checkbox"  <?php echo $r['atextensao']=="1"?'checked':"";?> name = "atextensao" value = "0"> Extensão</td>
                  
                  <td><input type = "checkbox" <?php echo $r['atgpa']=="1"?'checked':"";?> name = "atgpa" value = "0"> Gestão, planejamento e avaliação</td></tr>
                    
               </tr>
        <tr><td>Cursos de Graduação em que atuou no ano de <?php echo $anobase;?></td><td></td></tr>
         <tr><td><input type = "checkbox" <?php echo $r['atgpresencial']=="1"?'checked':"";?> name = "atgpresencial" value = "0"> Ensino em curso de graduação presencial</td>
         <td><input type = "checkbox" <?php echo $r['atgEAD']=="1"?'checked':"";?> name = "atgEAD" value = "0"> Ensino em curso de graduação a distância</td></tr>
      
      