<?php

session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
$anogestao =$sessao->getAnobase();
?>
<?php
if (!$aplicacoes[36]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
$c=new Controlador();
$unidade = new Unidade();
$unidade->setCodunidade($codunidade);
$unidade->setNomeunidade($nomeunidade);
$codunidade1=$unidade->getCodunidade();
    
$daodoc = new DocumentoDAO();
if ($c->getProfile($sessao->getGrupo())) {//se grupo for 18  
  $rows = $daodoc->buscadocumentoPrazo($sessao->getAnobase());
}else{
$rows = $daodoc->buscaporRedundancia($unidade->getCodunidade(),$anogestao);
}
$objdoc = array();
$cont = 0;
foreach ($rows as $row) {
   // if ($row['CodDocumento'] == NULL) { // documentos institucionais
        $objdoc[$cont] = new Documento();
        $objdoc[$cont]->setCodigo($row['codigo']);
        $objdoc[$cont]->setAnoInicial($row['anoinicial']);
        $objdoc[$cont]->setAnoFinal($row['anofinal']);
        $objdoc[$cont]->setNome($row['nome']);
        $objdoc[$cont]->setTipo($row['tipo']);

        $cont++;
//    }
}
if (!$c->getProfile($sessao->getGrupo())){
	if ($cont==0)
	    Utils::redirect('documentopdi','inserirdocpdi');
	else Utils::redirect('documentopdi','editardocpdi');
}

?>
<body>
  <!--   <a href="?modulo=documentopdi&acao=inserirdocpdi" >
    <button  type="button" class="btn btn-info btn-lg">Inserir novo mapa</button></a>-->
    <br><br>
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
                <tr>
                    <th>Documento</th>
                    <th>Ano de Início</th>
                    <th>Ano de Fim</th>
                    <th>Editar</th>
                    <th>Exportar</th>
                    <th>Painel</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < $cont; $i++): ?>
                    <tr>
                        <td><?php print $objdoc[$i]->getNome(); ?></td>
                        <td><?php print $objdoc[$i]->getAnoInicial(); ?></td>
                        <td><?php print $objdoc[$i]->getAnoFinal(); ?></td>
                        <td align="center">
                        
                        
                        <form class="form-horizontal" name="teste" method="post" action="?modulo=documentopdi&acao=editardocpdi">
                            <input class="form-control"type="hidden" name="doc" value="<?php echo $objdoc[$i]->getCodigo();?>">   
                            <button id="bar" class="btn btn-info" type="submit" ><img src="webroot/img/editar.gif"/></button>
                            </form>

                        
                        
                        </td>
                        
                         <td align="center">
                                <a href="<?php echo Utils::createLink('documentopdi', 'download', array('codigo' =>$objdoc[$i]->getCodigo())); ?>"
                                 target="_self"><img
                                        src="webroot/img/download.jpg"
                                        alt="Download" width="19" height="19" /> </a>

                        </td>
                           <td align="center">
                                <a href="<?php echo Utils::createLink('mapaestrategico', 'listamapa', array('codigo' =>$objdoc[$i]->getCodigo())); ?>"
                                 target="_self"><img
                                        src="webroot/img/painel.jpg"
                                        alt=<?php echo $objdoc[$i]->getTipo()==1?"Painel Estratégico":"Painel Tático";?> width="30" height="30" /> </a>

                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        
        
    </body>
