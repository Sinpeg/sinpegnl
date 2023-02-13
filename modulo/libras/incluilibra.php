<?php
session_start();
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[12]) {
    header("Location:index.php");
} else {
    $sessao = $_SESSION["sessao"];
    $nomeunidade = $sessao->getNomeunidade();
    $codunidade = $sessao->getCodunidade();
    $anobase = $sessao->getAnobase();
    $urespons = $sessao->getUnidadeResponsavel();
    
    require('dao/librasDAO.php');
    require('classes/libras.php');
    
    $daolibra = new LibrasDAO();
    $unidade = new Unidade();
    $unidade->setCodunidade($codunidade);
    $unidade->setNomeunidade($nomeunidade);
    $cont = 0;
    
    if($urespons==1)
    	$rows = $daolibra->buscaCNLibras($codunidade, $anobase);
    else
    	$rows = $daolibra->buscaCNLibras($urespons, $anobase);
    
    
    foreach ($rows as $row) {
        $campus = new Campus;
        $campus->setCodigo($row["codcampus"]);
        $campus->setNome($row["nomecampus"]);
        $unidade->adicionaItemCursos($campus, $row['CodCursoSis'], $row['CodCurso'], $row['NomeCurso'], $row['DataInicio'], $row['CodEmec']);
        $cont++;
    }
    $daolibra->fechar();
}
?>
<form class="form-horizontal" name="cunidades" id="cunidades" method="post" action="<?php echo Utils::createLink('libras', 'oplibra'); ?>">
    <h3 class="card-title">Libras no curr&iacute;culo</h3>
    Selecione os cursos que apresentam libras em seu curr&iacute;culo:
    <table id="tablesorter" class="table table-bordered table-hover">
         <tfoot>
            <tr>
                <th colspan="7" class="ts-pager form-horizontal">
                    <button type="button" class="btn first"><i class="icon-step-backward glyphicon glyphicon-step-backward"></i></button>
                    <button type="button" class="btn prev"><i class="icon-arrow-left glyphicon glyphicon-backward"></i></button>
                    <span class="pagedisplay"></span> <!-- this can be any element, including an input class="form-control"-->
                    <button type="button" class="btn next"><i class="icon-arrow-right glyphicon glyphicon-forward"></i></button>
                    <button type="button" class="btn last"><i class="icon-step-forward glyphicon glyphicon-step-forward"></i></button>
                    <select class="custom-select" title="Select page size">
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
            <?php if ($cont > 0) { ?>
                <tr align="center" style="font-style: italic;">
                    <th></th>
                    <th>Campus</th>
                    <th>C&oacute;digo Emec</th>
                    <th>Nome do curso</th>
                </tr>
            <?php } ?>
        </thead>
        <tbody>
            <?php foreach ($unidade->getCursos() as $curso) { ?>
                <tr>
                    <td><input class="form-check-input" type="checkbox" name="cursos[]"
                               value="<?php print $curso->getCodcurso(); ?>" /></td>
                    <td><?php print $curso->getCampus()->getNome(); ?>
                    </td>
                    <td><?php print $curso->getCodemec(); ?>
                    </td>
                    <td><?php print $curso->getNomecurso(); ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <br /> <input class="btn btn-info" type="submit"  value="Gravar" />
</form>
