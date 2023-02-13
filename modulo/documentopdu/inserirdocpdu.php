<?php
//include_once ('../../../includes/dao/PDOConnectionFactory.php');
//include_once ('../../../includes/classes/sessao.php');
include_once('dao/DocumentoDAO.php');
include_once('classe/Documento.php');
session_start();
$sessao = $_SESSION['sessao'];
$codunidade = $sessao->getCodUnidade();
$aplicacoes = $sessao->getAplicacoes();
?>
<?php
if (!$aplicacoes[36]) {
    print "Erro ao acessar esta aplicação";
    exit();
}
?>
<?php
$daodoc = new DocumentoDAO();
$rows = $daodoc->lista();
$objdoc = array();
$cont = 0;
foreach ($rows as $row) {
    if ($row['CodDocumento'] == NULL) {
        $objdoc[$cont] = new Documento();
        $objdoc[$cont]->setCodigo($row['codigo']);
        $objdoc[$cont]->setNome($row['nome']);
        $cont++;
    }
}
?>
<script>
    $(function() {
        $('#missao').hide();
        $('#visao').hide();
    });
    var aberto = false;
    $(function() {
        $('.missao').click(function() {
            $('a.missao').text('[-] Missão');
            $('a.visao').text('[+] Visão');
            $('#visao').hide();
            if (aberto == false) {
                $('#missao').fadeIn(600, function() {
                    aberto = true;
                })
            }
        });
    });
    $(function() {
        $('.visao').click(function() {
            if (aberto == true) {
                $('a.visao').text('[-] Visão');
                $('a.missao').text('[+] Missão');
                aberto = false;
                $('#missao').hide(600, function() {
                    $('#visao').fadeIn(600, function() {
                    });
                });
            }
        });
    });
</script>
<fieldset>
    <legend>Cadastrar Documento</legend>
    <div id="resultado"></div>
    <form class="form-horizontal" name="adicionar" method="POST" action="ajax/documentopdu/registradoc.php">
        <div>
            <label>PDI:</label>
            <select class="custom-select" name="pdi" class="sel1">
                <option value="0">Selecione o documento...</option>
                <?php for ($i = 0; $i < $cont; $i++): ?>
                    <option value="<?php print $objdoc[$i]->getCodigo(); ?>"><?php print $objdoc[$i]->getNome(); ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label>Nome:</label>
            <input class="form-control"type="text" class="txt" name="nomedoc" size="60"/>
        </div>
        <div>
            <label>Ano Inicial:</label>
            <input class="form-control"type="text" class="short" name="anoinicial" maxlength="4" size="4"/>
        </div>
        <div>
            <label>Ano Final:</label>
            <input class="form-control"type="text" class="short" name="anofinal" maxlength="4" size="4" />
        </div>
        <div>                     
            <span class="intermediario">Situação:</span>
            <span class="intermediario"><input class="form-control"type="radio" value="A" name="situacao"/>ativado</span>
            <span class="intermediario"><input class="form-control"type="radio" value="D" name="situacao"/>desativado</span>
        </div>
        <div>
            <a href="#" class="missao">[+] Missão</a>
        </div>
        <div id="missao">
            <label>Missão:</label>
            <textarea class="area" name="missao" cols="60" rows="5"></textarea>
        </div>
        <div>
            <a href="#" class="visao">[+] Visão</a>
        </div>
        <div id="visao">
            <label>Visão:</label>
            <textarea class="area" name="visao"  cols="60" rows="5"></textarea>
        </div>
        <div>
            <input class="btn btn-info" type="submit"  value="Salvar" name="salvar" class="btn btn-info"/>
        </div>
    </form>
</fieldset>