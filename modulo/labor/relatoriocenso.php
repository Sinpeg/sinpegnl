<?php
/*
 * Data:22 de abr 2020
 * autor: Carla
 * Para usar este script, deve-se atualizar a tabela comparação2020
 * com os nomes dos cursos e o codigo inep matriz , dos cursos que têm alunos
 * Usar tabela fato_alunosiga
 */


set_time_limit(100000);
//criamos o arquivo
$sessao = $_SESSION["sessao"];
$anobase = $sessao->getAnobase();
$nomearquivo="../public/raa_anexos/labcenso".$anobase.".txt";
$arquivo = fopen($nomearquivo,'w');

//verificamos se foi criado
if ($arquivo == false) die('Não foi possível criar o arquivo.');
//escrevemos no arquivo

require_once('dao/laboratorioDAO.php');
require_once('classes/laboratorio.php');
require_once('dao/tplaboratorioDAO.php');
require_once('classes/tplaboratorio.php');
require_once('classes/Labinconsistente.php');
require_once('dao/labcursoDAO.php');
require_once('classes/labcurso.php');
$codunidade=$sessao->getCodUnidade();//codigo da unidade do usuário

//$mysqli = new mysqli('amana.ufpa.br', 'dados', 'JGgwo1FsSgbs', 'dwcenso');
//$mysqli->set_charset('utf8');

// Caso algo tenha dado errado, exibe uma mensagem de erro
/*if (mysqli_connect_errno()) {
    printf("Conexão mysqli falhou: %s\n", mysqli_connect_error());die;
    exit();
}else{
    echo "conectou";die;
}*/





$daolab = new LaboratorioDAO();
$daotipolab = new TplaboratorioDAO();
$daolabc = new LabcursoDAO();
$daoc = new CursoDAO();
$cont = 0;
//pega tipo do lab
$rows_tlab = $daotipolab->Lista();
foreach ($rows_tlab as $row) {
    $tiposlab[$cont] = new Tplaboratorio();
    $tiposlab[$cont]->setCodigo($row['Codigo']);
    $tiposlab[$cont]->setNome($row['Nome']);
    $cont++;
}
$cont=0;
//tabela comparacao
$rowscm=$daoc->Listacomparacao();
$cmarray1=array();
$cmarray2=array();
$cm=0;
foreach ($rowscm as $c){
    $cm++;
    $cmarray1[$cm]=$c['codinep'];
    $cmarray2[$cm]=$c['nomecurso'];
}
//tabela curso
$rowsc=$daoc->listacursocenso();
$cursocenso1=array();
$cursocenso2=array();
/*foreach ($rowsc as $c){
    $cursocenso1[$contcn]=$c['']
}*/
$rowsc=$daoc->Listacenso();
$cursoarray=array();

foreach ($rowsc as $c){
    if (($sessao->getAnobase()<2018 && $c['AnoValidade']==2014)
    || ($sessao->getAnobase()>=2018 && $c['AnoValidade']==2018)){
        $cont++;
        
        $co=new Curso();
        $co->setCodcurso( $c['CodCurso'] );
        
        $co->setNomeCurso($c['NomeCurso']);
        $co->setCodemec($c['idcursoinep']);
        
        $co->setAnovalidade($c['AnoValidade']);
        
        $cursoarray[$cont]=$co; 
        
        
}
}
//pega laboratório 
$cont1 = 0;
$rows = $daolab->buscaLaboratoriosCenso($anobase);


$labarray=array();
    foreach ($rows as $row)
    {
       
        if ($row["CodUnidade"]!=973) {//escola de aplicação
                $cont1++;
                $lab = new Laboratorio();
                $lab->setCodlaboratorio($row["CodLaboratorio"]); // configura o código do laboratório
                $lab->setNome($row["Nome"]); // configura o nome
                $lab->setCapacidade($row["Capacidade"]); // configura a capacidade
                $lab->setSigla($row["Sigla"]); // sigla do laboratório
                $lab->setLabensino($row["LabEnsino"]); // laboratório de ensino
                $lab->setArea(str_replace(".", ",", $row['Area'])); // área do laboratório
                $lab->setSituacao($row["Situacao"]); // situação
                $lab->setAnoativacao($row["AnoAtivacao"]); // ano de ativação
                $lab->setAnodesativacao($row["AnoDesativacao"]); // ano de desativacao
                $tp=new Tplaboratorio();
                $tp->setCodigo($row['Tipo']);
                $lab->setTipo($tp); // tipo do laboratório
                //unidade do laboratorio
                $uni=new Unidade();
                $uni->setCodunidade($row["CodUnidade"]);
                $uni->setNomeunidade($row["NomeUnidade"]);
                $uni->setSigla($row["siglauni"]);
                $uni->setIdunidaderesponsavel($row["unidade_responsavel"] );
                $lab->setUnidade($uni);
                $labarray[$cont1]=$lab;
        }
       
    } 
    
//pega labcurso   
    $lccurso=array();
    $rows=$daolabc->Lista();
    $contlc=0;
    $sigla="";
    
    foreach ($rows as $r){
       
            $lc=new Labcurso();
            $lc->setCodlabcurso($r['CodLabCurso']);
            $c=new Curso();
            $c->setCodcurso($r['CodCurso']);
            //$c->setCodemec($r['CodEmec']);
            $lc->setCurso($c);
            for ($i=1;$i<=count($cursoarray);$i++){
                if ($lc->getCurso()->getCodcurso()==$cursoarray[$i]->getCodcurso()){
                    $lc->getCurso()->setNomecurso($cursoarray[$i]->getNomecurso());
                    $lc->getCurso()->setCodemec($cursoarray[$i]->getCodemec());
                }
            }
            $l=new Laboratorio();
            $l->setCodlaboratorio($r['CodLaboratorio']);
            $lc->setLaboratorio($l);
            $contlc++;
            $lccurso[$contlc]=$lc;
        
    }
        $linhas="10|569\n";
   // $linhasinconsitentes;
    $contincon=0;
    $linhasinconsitentes=array();
    $linhaoutrosprob="";
    for ($i=1;$i<=count($labarray);$i++){
         //busca o emec do curso no postgresql
     //   $labarray[$i]->setLabcursos(0);
         $codemec=0;
         $linhacurso="";
             //echo $labarray[$i]->getCodlaboratorio()."<br>";
          $passou=0;
          //pega todos os cursos vinculados ao labarray
         for ($j=1;$j<=count($lccurso);$j++){
             if ($lccurso[$j]->getLaboratorio()->getCodlaboratorio()==$labarray[$i]->getCodlaboratorio()){
                 //cmarray lista de comparacao
                $codemec=buscavalornovetor($cmarray1, $cmarray2, $lccurso[$j]->getCurso()->getNomecurso());
                
                $inclui=False;
                if ($codemec!=0){
                    $inclui=$labarray[$i]->adicionaItemLabcursoscenso($codemec,$lccurso[$j]->getCurso()); 
                    if ($inclui){
                       $linhacurso.="12|".$codemec."\n";//codemec
                    }
                }else{//Procura no sisraa anovalidade=2018
                    $inclui=$labarray[$i]->adicionaItemLabcursoscenso($lccurso[$j]->getCurso()->getCodemec(),$lccurso[$j]->getCurso());
                    if ($inclui){
                        if (empty($lccurso[$j]->getCurso()->getCodemec())){
                            $linhacurso.="12|cursoinexistente\n";
                           $passou=1;
                        }else{
                            $linhacurso.="12|".$lccurso[$j]->getCurso()->getCodemec()."\n";
                        }
                    }else{
                        $linhacurso.="12|cursoinexistente\n";
                        $passou=1;
                    }
                }
                
              
             }
         }//fim loop labcurso
        
         
         
         $linhalab="11|".$labarray[$i]->getNome()."|".
             $labarray[$i]->getCodlaboratorio()."|".
             $labarray[$i]->getTipo()->getCodigo()."||\n";
            // "|". $labarray[$i]->getUnidade()->getNomeunidade()."|".
             
             
         if (!empty($linhacurso) && $passou!=1){
             $linhalab.=$linhacurso;
             $linhas.=$linhalab;
         }else if ($passou==1){
             $linhaoutrosprob.=$linhalab.$linhacurso;
         }else{
         
                 $udao=new UnidadeDAO();
                 $parametro=$labarray[$i]->getUnidade()->getIdunidaderesponsavel()==1
                 ?1
                 :$labarray[$i]->getUnidade()->getIdunidaderesponsavel();
                 
                 $sigla=$labarray[$i]->getUnidade()->getSigla();
                 
                 if ($parametro!=1){
                    $rows=$udao->buscaidunidade($parametro);
                    foreach ($rows as $r){
                        $sigla=$r["sigla"];
                    }
                 }
                 
                 
                 $contincon++;
                 $li=  new Labinconsistente();
                 $li->setNomelab($labarray[$i]->getNome());
                 $li->setClasselab($labarray[$i]->getTipo()->getCodigo());
                 $li->setCodlab($labarray[$i]->getCodlaboratorio());
                 $li->setSigla($sigla);
                 $linhasinconsitentes[$contincon]=$li;
            
         }
    }
        

    
    
fwrite($arquivo, $linhas);
fclose($arquivo);


$lab->separaUnidade($linhasinconsitentes);
$lab->separaConteudo($linhasinconsitentes);
$lab->imprimeConteudo($anobase);
$lab->imprimeConteudoPorUnidade($anobase);

$nomearquivo="../public/raa_anexos/laboutrosproblemas".$anobase.".txt";
$arquivo = fopen($nomearquivo,'w');
fwrite($arquivo, $linhaoutrosprob);
fclose($arquivo);





function buscavalornovetor($vcodemec,$vnome,$valor){
    for ($i=1;$i<=count($vnome);$i++){
        if ($valor==$vnome[$i]){
            return $vcodemec[$i];
        }
    }
    return 0;
}

    
    

?>

<fieldset>
<legend>Exportação do arquivo de laboratórios para o Censup</legend>
<div id="resultado"></div>
<form class="form-horizontal" name="adicionar" method="POST" action="">
<table><tr><td>
<label>Arquivo para importar no Censup:</label></td>
<td>
<a href='<?php echo "../public/raa_anexos/labcenso".$anobase.".txt";?>' target="_blank">Laboratórios</a>
</td></tr>
<tr><td>
<label>Arquivo com todas as unidades:</label></td>
<td>
<a href='<?php echo "../public/raa_anexos/labincCompleto".$anobase.".txt";?>' target="_blank">Laboratórios com Inconsistências</a>

</td></tr>
<tr><td>
<label>Arquivo(s) com inconsistências por unidade:</label></td>
<td> Não é obrigatório o vínculo de cursos de Pós-graduação aos laboratórios.<br>
Para executar esta aplicação, a tabela comparacao2020 deve estar atualizada.<br>
<?php   
$vsiglas=$lab->getVetorsiglas();
for  ($i=1;$i<=count($vsiglas);$i++){?>
    <a href='<?php echo "../public/raa_anexos/labinc".$vsiglas[$i].$anobase.".txt";?>' target="_blank">Inconsistências do <?php echo $vsiglas[$i]?> </a><br>
<?php } ?>
</td></tr>

<tr><td>
<label>Arquivo com outros problemas:</label></td>
<td>
<a href='<?php echo "../public/raa_anexos/laboutrosproblemas".$anobase.".txt";?>' target="_blank">Laboratórios com Inconsistências</a>

</td></tr>
</table>
        
    </form>
</fieldset>
