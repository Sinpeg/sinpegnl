<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[14]) {
    header("Location:index.php");
}
//$anobase = $sessao->getAnoBase();
$hierarquia = $sessao->getCodestruturado();
$daou = new UnidadeDAO();
$rs = $daou->unidadeporcodigo($codunidade);
foreach ($rs as $r) {
	$codestruturado = $r['hierarquia_organizacional'];
}
//require_once('../../includes/dao/PDOConnectionFactory.php');
//require_once('../../includes/dao/unidadeDAO.php');
//require_once('../../includes/classes/unidade.php');
require_once('classes/local.php');

//require_once('../../includes/dao/psaudemensalDAO.php');
//require_once('../../includes/classes/psaudemensal.php');
$daou = new UnidadeDAO();
if ($sessao->getUnidadeResponsavel()>1){
    $rows=$daou->buscaidunidade($sessao->getUnidadeResponsavel());
    foreach ($rows as $r){
        $codunidade=$r['CodUnidade'];// quandoo usuario for subunidade
        $nomeunidade=$r['NomeUnidade'];
        $codestruturado = $r['hierarquia_organizacional'];
        $resp=$r['unidade_responsavel'];
    }
    
}else{
    $rs = $daou->unidadeporcodigo($sessao->getCodUnidade());
    foreach ($rs as $r) {
        $nomeunidade=$r['NomeUnidade'];
        $codestruturado = $r['hierarquia_organizacional'];
        $resp=$r['unidade_responsavel'];
    }
}
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$unidade->setCodestruturado($codestruturado);
$unidade->setUnidaderesponsavel($resp);

if($codunidade == 1644) {
    $tipounidade = "L";
} else {
    $tipounidade = "P";
}
$rows = $daou->buscaSubunidadesCodestruturado($tipounidade, $codestruturado);
foreach ($rows as $row) {
    $unidade->adicionaItemSubunidade($row['CodUnidade'], $row['NomeUnidade'], $row['hierarquia_organizacional']);
}

//Buscar Locais
$locais = array(); // Array que armazena os locais
$cont = 0;
$rows = $daou->buscalocais($codestruturado);
$ehlocal=0;

foreach ($rows as $row) {
    $cont++;
    $locais[$cont] = new Local();
    $locais[$cont]->setCodigo($row['CodUnidade']);
    $locais[$cont]->setNome($row['NomeUnidade']);
    $locais[$cont]->setTipo($row['TipoUnidade']);
    if ($locais[$cont]->getCodigo()==$sessao->getCodUnidade()){
    	$ehlocal=1;
    	$codlocal=$locais[$cont]->getCodigo();
    	$nomelocal=$locais[$cont]->getNome();
    	$tipolocal=$locais[$cont]->getTipo();
    	
    	
    }
}
//Fim Buscar Locais

$daou->fechar();
//ob_end_flush();
?>

<div class="bs-example">
    <ul class="breadcrumb">
        <li class="active">
            <a href="<?php echo Utils::createLink('prodsaude4', 'incluipsaude5'); ?>">
            Consultar Produção da Área da Saúde</a> 
            <i class="fas fa-long-arrow-alt-right"></i>
            <a href="#" >Cadastrar Produção da Área de Saúde</a>
        </li>  
    </ul>
</div>

<div class="card card-info">
    <div class="card-header">
        <h3 class="card-title">Produ&ccedil;&atilde;o da &Aacute;rea da Sa&uacute;de</h3>
    </div>
 
    <form class="form-horizontal" name="fpsaude4" id="fpsaude4" method="post" action="">        
        <table class="card-body">        
            <div class="msg" id="msg"></div>
            <div id="resultado"></div>
            <tr>
                <td class="coluna1">  
                    <label>M&ecirc;s</label>
                </td>
            </tr>
            <tr>
                <td class="coluna2">
                    <select class="custom-select" name="mes" class="sel1">
                            <option value="1">janeiro</option>
                            <option value="2">fevereiro</option>
                            <option value="3">mar&ccedil;o</option>
                            <option value="4">abril</option>
                            <option value="5">maio</option>
                            <option value="6">junho</option>
                            <option value="7">julho</option>
                            <option value="8">agosto</option>
                            <option value="9">setembro</option>
                            <option value="10">outubro</option>
                            <option value="11">novembro</option>
                            <option value="12">dezembro</option>
                    </select>	
                </td>
            </tr>
            
            <tr>
                <td class="coluna1"><label>Subunidade</label></td>
            </tr>

            <tr>     
                <td class="coluna2">
                    <?php if ($sessao->isUnidade() || $ehlocal==1){ ?>
                        <select class="custom-select" name="subunidade" id="subunidade_busca" class="sel1">
                            <option selected="selected" value="0" > Selecione uma subunidade...</option>
                            <?php foreach ($unidade->getSubunidades() as $sub) { ?>
                                <option value="<?php print $sub->getCodunidade(); ?>">
                                    <?php print ($sub->getNomeunidade()); ?></option>
                            <?php } ?>
                        </select>
                    <?php } else {?>
                        <select class="custom-select" name="subunidade" id="subunidade_busca" class="sel1">
                            <option selected="selected" value="0" > Selecione uma subunidade...</option>
                        
                            <option value="<?php print $sessao->getCodUnidade(); ?>"><?php print ($sessao->getNomeUnidade()); ?></option>
                        </select>
                    <?php } ?>
                </td>
            </tr>
            
            <?php  if ($codunidade == 270) {//nmt?>
                <tr>
                    <td class="coluna1"><label> Local </label></td>
                </tr>
                <tr>
                    <td class="coluna2">
                        <?php if ($sessao->getUnidadeResponsavel()==1){?>
                            <select id="local" name="local" class="sel1">
                                <option value="0">Selecione local...</option>
                                <?php foreach ($locais as $l) { ?>
                                    <option  value="<?php print $l->getCodigo(); ?>">
                                    <?php print ($l->getNome()); ?></option>
                                <?php } ?>
                            </select>
                        <?php }else{// alteracao para locais se tornarem unidades e poderem fazer registro?>
                            <select id="local" name="local" class="sel1">
                                <option value="0">Selecione local...</option>
                                <option  value="<?php print $codlocal; ?>">
                                <?php print ($nomelocal); ?></option>
                            </select>            
                        <?php } ?>
                    </td>
                </tr>
            <?php }//270 ?>
            
            <tr>
                <td class="coluna1"><label>Serviço</label></td>
            </tr>
            <tr>
                <td class="coluna2">
                    <span id="txtHint1">
                        <select  id="servico"  name="servico" class="sel1" >
                            <option value="0">Selecione um servi&ccedil;o...</option>                     
                        </select>
                    </span>
                </td>
            </tr>
            
            <tr>
                <td class="coluna1">
                    <label>Procedimento</label>
                </td>
            </tr>
            <tr>
                <td class="coluna2">
                    <span id="txtHint2">
                        <select id="procedimento" name="procedimento" class="sel1">
                            <option value="0">Selecione um procedimento...</option>
                        </select>
                    </span>
                </td>
            </tr>
            
            <?php if ($codunidade == 270) { ?>
                <tr>
                    <td class="coluna1">
                        <label>Discentes</label>
                    </td>
                </tr>
                <tr>
                    <td class="coluna2">
                        <input class="form-control"type="text" name="ndisc" value="" size="5" maxlength="5" onkeypress='return SomenteNumero(event);' class="short"/>
                    </td>
                </tr>
                
                <tr>
                    <td class="coluna1"><label>Docentes</label></td>
                </tr>
                <tr>
                    <td class="coluna2"><input class="form-control"type="text" name="ndoc" value="" size="5" maxlength="5" onkeypress='return SomenteNumero(event);' class="short" /></td>
                </tr>
                
                <tr>
                    <td class="coluna1"><label>Pesquisadores</label></td>
                </tr>
                <tr>
                    <td class="coluna2"><input class="form-control"type="text" name="npesq" value="" size="5" maxlength="5" onkeypress='return SomenteNumero(event);' class="short" /></td>
                </tr>
                
                <tr>
                    <td class="coluna1"><label>Pessoas atendidas</label></td>
                </tr>
                <tr>
                    <td class="coluna2"><input class="form-control"type="text" name="npaten" value="" size="5" maxlength="5" onkeypress='return SomenteNumero(event)' class="short" /></td>
                </tr>
                
                <tr>
                    <td class="coluna1"><label>Número de procedimentos</label></td>
                </tr>
                <tr>
                    <td class="coluna2"><input class="form-control"type="text" name="nproc" value="" size="5" maxlength="5" onkeypress='return SomenteNumero(event)' class="short" /></td>
                </tr>
            
            <?php } else if ($codunidade == 202) { //IFCH ?>
                <tr>
                    <td class="coluna1"><label>Pessoas atendidas</label></td>
                </tr>
                <tr>
                    <td class="coluna2"><input class="form-control"type="text" name="npaten" value="" size="5" maxlength="5" onkeypress='return SomenteNumero(event)' class="short" /></td>
                </tr> 
            <?php } else { ?>
                <tr>
                    <td class="coluna1"><label>Exames realizados</label></td>
                </tr>
                <tr>
                    <td class="coluna2"><input class="form-control"type="text" name="nexames" value="" size="5" maxlength="5" onkeypress='return SomenteNumero(event)' class="short" /></td>
                </tr>
            <?php } ?>
        </table>

        <div class="card-body">
            <input class="form-control"name="operacao" type="hidden" value="I" />
            <input type="button" value="Gravar" name="Gravar" class="btn btn-info btn"/>	   
        </div>
    </form>
</div>
<script>
    $(function() {
        $('input[name=Gravar]').click(function() {
                
            
            $('div#resultado').empty();
            $.ajax({
                url: "ajax/prodsaude4/incprodsaude4.php", 
                type: 'POST', 
                data:$('form[name=fpsaude4]').serialize(), 
            success: function(data) {
                    $('div#resultado').html(data);
                }});
            
            });   
    });
</script>