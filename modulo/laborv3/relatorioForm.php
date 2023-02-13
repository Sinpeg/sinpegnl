<?php
$sessao = $_SESSION["sessao"];
$aplicacoes = $sessao->getAplicacoes();
if (!$aplicacoes[7]) {
    header("Location:index.php");
}
?>
<script type="text/javascript">
    $(document).ready(
            function() {
                $("#anual").hide();
                $("#serie").hide();
                $("#curso").hide();
                $("#infolabs").hide();
            });
    $(function() {
        $("#pesquisa-anual").click(function() {
            $("#serie").hide();
            $("#anual").show(600);
            $("#tipografico").show(600);
            if ($("#consulta option[value='3']").length == 0) {
                $('#consulta').append(new Option("Número de cursos por laboratórios", "3"));
            }

        });
        $("#pesquisa-serie").click(function() {
            $("#anual").hide(600);
            $("#serie").show(600);
//            $("#tipografico").hide(600);
            $("#consulta option[value=3]").remove();
            $("#curso").hide();
        });
    });

    $(function() {
        $("#consulta").on('keydown change', function() {
            if ($("select[name=consulta]").val() == "3" && $("#pesquisa-anual").is(":checked")) {
                $("#curso").show(600);
            }
            else {
                $("#curso").hide(600);
            }
        });
    });

    $(function() {
        $("#labinf").click(function() {
            if ($("#labinf").is(":checked"))
                $("#infolabs").show(200);
            else {
                $("#infolabs").hide();
                $("input[name=so]").attr('checked', false);
                $("input[name=cab]").attr('checked', false);
            }
        })
    })
</script>
<?php require_once "dao/tplaboratorioDAO.php"; ?>
<?php require_once "dao/categoriaDAO.php"; ?>
<form class="form-horizontal" name="busca-grafico" id="us" method="get" role="form">
    <fieldset>
        <div id="chart-erro"></div>
        <legend>Buscar laboratórios</legend>
        <div id="tipo_pesquisa">
            <label>Pesquisa:</label>
            <input class="form-control"type="radio" name="pesquisa" id="pesquisa-anual" value="anual"/> Anual
            <input class="form-control"type="radio" name="pesquisa" id="pesquisa-serie" value="serie"/> Série histórica
        </div>
        <div id="anual">
            <label for="anounico">Ano</label> 
            <!-- campo select -->
            <?php
            $ano0 = 2011; // ano inicial
            $anofinal = date('Y');
            ?>
            <select class="custom-select" name="anounico">
                <?php for ($i = $ano0; $i <= $anofinal; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div id="serie">
            <label for="ano">Período </label>
            <input class="form-control"type="text" size="4" maxlength="4" id="ano" name="ano" value="" class="ano" /> 
            a <input class="form-control"type="text" size="4" maxlength="4" name="ano1" value="" class="ano" />
        </div>
        <div>
            <!-- Tipo de Consulta -->
            <label>Tipo de consulta</label>

            <select class="custom-select" name="consulta" id="consulta">
                <option value="1">Total de laboratórios (independente de curso)</option>
                <option value="2">Soma das áreas dos laboratórios (independente de curso)</option>
            </select>
        </div>
        <div id="curso">
            <div>
                <label for="nivelcurso">Nível do curso: </label>
                <select class="custom-select" name="nivelcurso">
                    <option value="0">--Selecione o nível do curso--</option>
                    <option value="1">Graduação</option>
                    <option value="2">Pós-graduação</option>
                    <option value="3">Escola de Aplicação</option>
                    <option value="4">Graduação e Pós-graduação</option>
                    <option value="5">Todos os níveis</option>
                </select>
            </div>
        </div>
        <!-- Categoria dos laboratórios -->
        <div>
            <label for="categoria">Categoria: </label>
            <select class="custom-select" name="categoria">
                <option value="0">-- Selecione a categoria --</option>
                <option value="todas">Todas</option>
                <?php
                $daocat = new CategoriaDAO();
                $rows = $daocat->Lista();
                ?>
                <?php foreach ($rows as $row) : ?>
                    <option value="<?php echo $row['Codigo']; ?>"><?php echo $row["Nome"]; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Subcategoria dos laboratórios -->
        <div id="tipo-ajax">
        </div>
        <!-- Situação dos laboratórios -->

        <label for="situacao">Situação do laboratório </label> <select
            class="sel1" id="situacao" name="situacao">
            <option value="A">ativos</option>
            <option value="D">desativados</option>
            <option value="T">todas</option>
        </select>

        <!-- Fim do campo para a situação -->
        <!-- Somente para os laboratórios de informática -->
        <div>
            <input class="form-check-input" name="labprat" type="checkbox" value="labprat" /><label for="labprat">Laboratório de aulas práticas</label>
        </div>
        <div>
            <input class="form-check-input" name="labinf" type="checkbox" value="labinf" id="labinf" /><label for="labinf">Laboratório de informática</label>
            <div style="margin: 10px 1px 0px 20px;" id="infolabs">
                <div>
                    <input class="form-check-input" name="so" type= "checkbox" value="so" /><label for="so">Sistema Operacional</label>
                </div>
                <div>
                    <input class="form-check-input" name="cab" type="checkbox" value="cab" /><label for="cab">Cabeamento estruturado</label>
                </div>
            </div>
            <!-- Unidade específica -->
            <div>
                <label for="txtUnidade">Unidade </label> 
                <input class="form-control"type="text" size="60" id="txtUnidade" name="txtUnidade"/><a href="#" class="help" data-trigger="hover" data-content='Informe o nome da unidade específica ou palavras-chave como: "todas", "institutos", "campus", "escolas", "hospitais", "nucleos" ou "faculdades".' title="Ajuda" ><span class="glyphicon glyphicon-question-sign"></span></a>
            </div>
            <div id="tipografico">
                <label>Tipo do gráfico:</label>
                <select class="custom-select" name="tipografico">
                    <option value="pie">Pizza</option>
                    <option value="column">Coluna</option>
                </select>
            </div>
            <div>
                <input type="button" id="gerarTabela" value="Consultar" class="btn btn-info" />
                <input type="button" id="gerarGrafico" value="Gerar gráfico" class="btn btn-info" />
            </div>
             <input class="form-control"type="hidden" name="modulo" value="labor" />
             <input class="form-control"type="hidden" name="acao"  value="consulta"/>
    </fieldset>
</form>
<!-- nesta div serão gerados os resultados ajax: gráficos, tabelas e planilhas para download -->
<div id="resultado">
</div>
<div id="chart">
</div>
