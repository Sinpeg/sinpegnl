<?php
require_once('../../dao/PDOConnectionFactory.php');
require_once('../../modulo/uparquivo/classes/arquivo.php');
require_once('../../modulo/uparquivo/dao/arquivoDAO.php');
require_once '../../classes/sessao.php';
require_once '../../classes/usuario.php';
require_once '../../dao/usuarioDAO.php';

require_once '../../classes/unidade.php';
require_once '../../dao/unidadeDAO.php';
session_start();
$sessao=$_SESSION["sessao"];
if ($sessao->getCodUnidade() != 100000) {
	exit();
}

$ano = $_POST ['ano'];
$assunto = $_POST ['assunto'];
$nomeunidade = addslashes( strtoupper ( $_POST ['unidade'] ) );

$usuarios = array ();
$dao = new ArquivoDAO ();
$i = 0;
$rows = $dao->buscaUnidadeAdmin1( $ano,  $assunto );//$nomeunidade,
foreach ( $rows as $row ) {
	$i ++;
	$usuarios [$i] = new Usuario ();
	$usuarios [$i]->setCodusuario ( $row['CodUsuario'] );
	$usuarios [$i]->setResponsavel ( $row['Responsavel'] );
	$usuarios [$i]->adicionaItemArquivo ( $row['Codigo'], $row ['Assunto'], null, $row ['Nome'], null, null, $row ['Comentario'], $row ['DataAlteracao'], $row ['DataInclusao'], $anobase );
	$usuarios [$i]->criaUnidade ( $row ['CodUnidade'], $row ['NomeUnidade'], null );
}
// $i é o tamanho do vetor usuarios
$dao->fechar ();
?>

<head>
<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />

<link rel="stylesheet" href="../../estilo/msgs.css" />
</head>
		<h3>Arquivos enviados</h3>
		<div class="msg" id="msg"></div>
		<?php if ($i > 0){?>
		<table id="tablesorter" class="table table-bordered table-hover">
		 <tfoot>
            <tr>
                <th colspan="7" class="ts-pager form-horizontal">
                    <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                    <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                    <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                    <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                    <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                    <select class="pagesize input-mini" title="Select page size">
                        <option selected="selected" value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                    </select>
                    <select class="pagenum input-mini" title="Select page number"></select>
                </th>
            </tr>
        </tfoot>
		<thead>
			<tr align="center">
				<th>Unidade</th>
				<th>Arquivo</th>
				<th>Tipo</th>
				<th>Inclus&atilde;o</th>
				<th>Altera&ccedil;&atilde;o</th>
				<th>Comet&aacute;rio</th>
				<th>Baixar</th>
			</tr>
			</thead>
			<tbody>
		<?php
		}
		for($j = 1; $j <= $i; $j ++) {
			?>
        <tr>
				<td><?php print $usuarios[$j]->getUnidade()->getNomeunidade();?></td>

        <?php 	foreach ($usuarios[$j]->getArquivos() as $u){  ?>
			    <td><?php print $u->getNome(); ?></td>
				<td><?php
				
if ($u->getAssunto () == 1) {
					print "Apresenta&ccedil;&atilde;o do Relat&oacute;rio de Atividades";
				} else
					print "Outros";
				?></td>
				<td>
				<?php
	
$data1 = explode ( '-', $u->getDatainclusao () );
				$data = $data1 [2] . '/' . $data1 [1] . '/' . $data1 [0];
				
				print $data;
				?>
				</td>
				<td align="center">
				<?php
				if ($u->getDataalteracao () != NULL) {
					$data2 = explode ( '-', $u->getDataalteracao () );
					$data3 = $data2 [2] . '/' . $data2 [1] . '/' . $data2 [0];
					print $data3;
				}
				?>
				</td>
				<td align="center">
                 <?php print $u->getComentario();?>
				</td>
				<td align="center"><a
					href="?modulo=uparquivo&acao=downarquivo&codigo=<?php print  $u->getCodigo();?>"
					target="_self"><img src="webroot/img/download.jpg"
						style="border: none; background-color: transparent" alt="Excluir"
						width="19" height="19" /> </a></td>
			</tr>
<?php }?>
        <?php }?>
        </tbody>
</table>
		<input type="text" name="registros" readonly="readonly" size="1"
			value="<?php echo $i;?>"
			style="border: none; background-color: transparent" />registro(s)<br />


