<?php

define('MODULO_DIR', (dirname(__FILE__)) . '/../../modulo/');
require '../../dao/PDOConnectionFactory.php';
require '../../classes/sessao.php';
require '../../util/Utils.php';

require_once MODULO_DIR . 'iniciativa/dao/IndicIniciativaDAO.php';
require_once MODULO_DIR . 'iniciativa/dao/IniciativaDAO.php';
require_once MODULO_DIR . 'iniciativa/classe/IndicIniciativa.php';
require_once MODULO_DIR . 'iniciativa/classe/Iniciativa.php';
require_once MODULO_DIR . 'indicadorpdi/classe/Mapaindicador.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultIniciativaDAO.php';
require_once MODULO_DIR . 'resultadopdi/classes/ResultIniciativa.php';
require_once MODULO_DIR . 'resultadopdi/dao/ResultadoDAO.php';
require_once MODULO_DIR . 'resultadopdi/classes/Resultado.php';

session_start();
$sessao = $_SESSION['sessao'];
$aplicacoes = $sessao->getAplicacoes();
if (!isset($sessao)) {
    exit();
}
if (!$aplicacoes[29]) {
    print ("O usuário não possui permissão para acessar esta aplicação.");
    exit();
}
$coddoc=$_POST["coddoc"];
$codmi=$_POST["codindicador"];
$mperiodo=$_POST["mperiodo"];
$periodo=$_POST["periodo"];
$codmeta=$_POST["meta"];
$metrica=$_POST["metrica"];
$codmapa=$_POST["codmapa"];
$passou=false;

$daores = new ResultadoDAO();
$rows = $daores->buscaresultaperiodometa($codmeta, $mperiodo);
$objresultado=NULL;
foreach ($rows as $row) {
   $passou=true;
}

if (!$passou){
    echo '<br><p style="color=red">Informe o resultado do indicador e em seguida o resultado da iniciativa!</p>';
}else{


$mi=new MapaIndicador();
$mi->setCodigo($mi);

 $daoii=new IndicIniciativaDAO();
    //echo $objmeta->getMapaindicador()->getCodigo();
                //$rows=$daoii->iniciativaPorMapIndicador($objmeta->getMapaindicador()->getCodigo());
                $rows=$daoii->iniciativaPorMapIndicador($codmi);
                $vinic=array();
                $i=0;
                foreach ($rows as $r){
                    $vinic[$i]=new Iniciativa();
                    $vinic[$i]->setCodIniciativa($r['codIniciativa']);
                    $vinic[$i]->setNome($r['nome']);
                    $vinic[$i]->setFinalidade($r['finalidade']);
                    $vinic[$i]->criaIndicIniciativa($r['codindinic'],$mi);
                    
                    $i++;
                }

?>
<br>
             <p><strong>Lançar situação das inciativas associadas ao indicador</strong></p>
             <table >
                    <tr>
                        <th>Inciativa</th>
                        <th>Finalidade</th>
                        <th>Ação</th>
                    </tr>

                    <?php 
                        for ($j = 0; $j < count($vinic); $j++){ ?>
                        <tr>


                            <td><?php echo $vinic[$j]->getNome(); ?></td>
                            <td><?php echo $vinic[$j]->getFinalidade(); ?></td>
                            <?php  
                            $daor=new ResultIniciativaDAO();
                            $rows=$daor->iniciativaPorResultado($vinic[$j]->getIndicIniciativa()->getCodigo(), $sessao->getAnobase(),$mperiodo); 
                           //alterar
                            if ($rows==NULL || $rows->rowCount() == 0) { // não encontrou nenhum resultado
                                $img1 = 'webroot/img/add.png';
                                $metrica = '-';
                                $url1 = Utils::createLink('resultadopdi', 'adicionarinic', array(
                                            'mperiodo'=>$mperiodo,
                                            'periodo'=>$periodo,
                                            'meta' => $codmeta,
                                            'iniciativa' => $vinic[$j]->getCodIniciativa(),
                                            'objetivo' => $codmapa,
                                            'indicador' =>$codmi,
                                            'documento' => $coddoc)
                                );
                            }
                            else { // caso contrário, deve-se alterar o resultado
                                foreach ($rows as $row) {
                                    $img1 = "webroot/img/editar.gif";
                                    $metrica = $metrica;
                                    $url1 = Utils::createLink('resultadopdi', 'altresultIniciativa', array(
                                                'mperiodo'=>$mperiodo,
                                                'periodo'=>$periodo,
                                                'meta' => $codmeta,
                                                'iniciativa' => $vinic[$j]->getCodIniciativa(),
                                                'objetivo' =>$codmapa,
                                                'indicador' => $codmi,
                                                'documento' => $coddoc
                                                    )
                                    );
                                    
                                }
                            } 
                            
                            ?>
                            <td><a href="<?php print $url1; ?>"><img src="<?php print $img1; ?>"/></a></td>
                        </tr>
                    <?php 
                         }
                    
                   ?>
            </table>

<?php } ?>