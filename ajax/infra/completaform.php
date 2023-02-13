<?php
sleep(1); // cria um delay de 1 segundo
require_once dirname(__FILE__).'/../../dao/PDOConnectionFactory.php';
require dirname(__FILE__).'/../../modulo/infra/dao/tipoinfraDAO.php';
$agrupamento = filter_input(INPUT_POST, 'agrupamento', FILTER_SANITIZE_ENCODED); /* campo para agrupamento */
//echo $agrupamento;
?>
<?php if ($agrupamento != "PCD") : ?>
 <div>
 <label for="pcd">PCD</label>
 <select name="pcd">
 <option value="0">--Selecione se tem PCD--</option>
 <option value="S">Sim</option>
 <option value="N">Não</option>
 </select>
 </div>
<?php endif; ?>
<?php if ($agrupamento != "MODALIDADE"): ?>
 <div>
 <label for="modalidade">Modalidade</label>
 <select name="modalidade">
 <option value="0">--Selecione a modalidade--</option>
 <option value="1">Presencial</option>
 <option value="2">À distância</option>
 <option value="3">Presencial e à distância</option>
 </select> 
 </div>
<?php endif; ?>
<?php if ($agrupamento != "TIPO"): ?>
 <div>
 <label for="tipoinfra">Tipo da infraestrutura</label>
 <select name="tipoinfra">
 <option value="0"> -- Selecione o tipo da infraestrutura -- </option>
 <?php 
 $daoti = new TipoinfraDAO();
 $stmt = $daoti->Lista();
 ?>
 <?php foreach ($stmt as $row): ?>
 <option value="<?php echo $row['Codigo']; ?>"><?php echo $row["Nome"]; ?></option>
 <?php endforeach; ?>
 </select>
 </div>
<?php endif; ?>