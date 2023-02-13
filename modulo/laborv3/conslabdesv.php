<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
 header("Location:index.php");
} else {
// $sessao = $_SESSION["sessao"];
 $nomeunidade = $sessao->getNomeunidade();
 $codunidade = $sessao->getCodunidade();
 require_once('dao/laboratorioDAO.php');
 require_once('classes/laboratorio.php');
 require_once ('classes/tplaboratorio.php');
 require_once('dao/labcursoDAO.php');
}
$daolab = new LaboratorioDAO();
$rows = $daolab->buscaLaboratoriosUnidade1($codunidade);
$daolabcurso = new LabcursoDAO();
$lab = array();
$cslab = 0;
foreach ($rows as $row) {
 $rows1 = $daolabcurso->buscaCursosLaboratorio1($row['CodLaboratorio']);
 if ($rows1->rowCount() == 0) {
 $lab[$cslab] = new Laboratorio();
 $lab[$cslab]->setNome($row['Nome']);
 $lab[$cslab]->setCodlaboratorio($row['CodLaboratorio']);
 $lab[$cslab]->setCapacidade($row['Capacidade']);
 $tplab = new Tplaboratorio();
 $tplab->setNome($row['Tipo']);
 $lab[$cslab]->setTipo($tplab);
 $cslab++;
 }
}
//var_dump($lab);
?>
<h3 class="card-title">Laboratórios sem cursos vinculados</h3>
<table>
 <thead>
 <th>Nome</th> 
 <th>Tipo</th> 
 <th>Capacidade</th> 
 <th>Excluir</th>
 <th>Vincular curso</th>
</thead>
<tbody>
 <?php for ($i = 0; $i < $cslab; $i++): ?>
 <tr>
 <td><?php echo ($lab[$i]->getNome()); ?></td>
 <td><?php echo ($lab[$i]->getTipo()->getNome()); ?></td>
 <td><?php echo $lab[$i]->getCapacidade(); ?></td>
 <td align="center"><a href="<?php echo Utils::createLink('labor', 'dellab', array('codlab' => $lab[$i]->getCodlaboratorio())); ?>"
 target="_self"><img src="webroot/img/delete.png"
 alt="Excluir" width="19" height="19" /> </a>
 <td align="center"><a
 href="<?php echo Utils::createLink('labor', 'inccursolab', array('codlab' => $lab[$i]->getCodlaboratorio())); ?>"
 target="_self"><img src="webroot/img/add.png"
 alt="Excluir"/> </a>
 </td>
 </tr>
 <?php endfor; ?>
</tbody>
</table>