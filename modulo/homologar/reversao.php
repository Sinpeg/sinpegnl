<?php
if (!$aplicacoes[44]) {
    Error::addErro("Você não possui permissão para acessar a aplicação solicitada!");
    Utils::redirect();
}
?>
<?php
// objetos
$ob_h = array(); // array de objetos de homologação
$ob_u = array(); // array de objetos das unidades
$ob_s = array(); // array de objetos das subunidades
$ob_a = array(); // array de objetos das aplicações
$cont = 0;
// recupera a homologação
$arr = DAOFactory::getHomologacaoDAO()->queryAllOrderBy("dataRegistro");
for ($i = 0; $i < count($arr); $i++) {
    $row = $arr[$i];
    if ($row->situacao == "S" && $row->ano==$anobase) { // todas as situações que devem ser revertidas
        // unidade
        $ob_h[] = $row;
        $daoun = new UnidadeDAO();
        $row1 = $daoun->unidadeporcodigo($row->codUnidade); // código da unidade 
        foreach ($row1 as $r1) {
            $u = new Unidade();
            $u->setNomeunidade($r1["NomeUnidade"]);
            $ob_u[] = $u; // configura o array de unidades
        }
        // subunidade
        $row2 = $daoun->unidadeporcodigo($row->codSub); // código da subunidade
        foreach ($row2 as $r2) {
            $s = new Unidade(); //subunidade também é unidade
            $s->setNomeunidade($r2["NomeUnidade"]);
            $s->setCodunidade($row->codSub);
            $ob_s[] = $s;
        }
        // aplicações
       
        $row3 = DAOFactory::getHomologacaoDAO()->queryByCodAplicacao($row->codAplicacao);  // getAplicacaoDAO()->load
        $ob_a[] = $row3;
        $cont++;
    }
}
?>
<table class="tablesorter tablesorter-dropbox">
    <thead>
        <tr>
            <th>Unidade</th>
            <th>Subunidade</th>
            <th>Aplicação</th>
            <th>Data da Homologação</th>
            <th>Horário</th>
            <th>Desbloqueio</th>
        </tr>
    </thead>
    <tbody>
        <?php for ($i = 0; $i < $cont; $i++): ?>
            <tr>
                <td><?php echo $ob_u[$i]->getNomeunidade(); ?></td>
                <td><?php echo $ob_s[$i]->getNomeunidade(); ?></td>
                <td><?php echo utf8_encode($ob_h[$i]->codAplicacao); ?></td>
                <?php
                $dataHomolog = $ob_h[$i]->dataRegistro;
                $timestamp = strtotime($dataHomolog);
                $aleatory = hash("sha256", "sisraaSalt" . rand(0, 100000));
                $id = " id = $aleatory ";
                
                $codap = $ob_h[$i]->codAplicacao; // código da aplicação
                $codsub = $ob_s[$i]->getCodunidade(); // código da subunidade
                
                $str = "?x=$aleatory&codap=$codap&codsub=$codsub&anobase=$anobase";
                $revert = '<a class="reversao" href="#reversao' . $str . '"><img src="webroot/img/lock.png"' . $id . '/></a>';
                ?>
                <td><?php echo date('d-m-Y', $timestamp) ?></td>
                <td><?php echo date('H:i:s', $timestamp); ?></td>
                <td><?php echo $revert; ?></td>

            </tr>
<?php endfor; ?>
    </tbody>
</table>
<div id="result-reversao"></div>