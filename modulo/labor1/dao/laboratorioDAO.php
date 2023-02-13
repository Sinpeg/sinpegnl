<?php

/* !
 * \author Ana Carla Macedo
 * \since 2011
 * \version 1.0
 */

class LaboratorioDAO extends PDOConnectionFactory {

    public $conex = null;
    
    private $mysqli;
  
	// constructor
	public function __construct() {
	    	 
	    	$this->mysqli = new mysqli(PDOConnectionFactory::getHost(), PDOConnectionFactory::getUser(), PDOConnectionFactory::getSenha(), PDOConnectionFactory::getDb());
	    	        $this->mysqli->set_charset('utf8');
	    	
	    	// Caso algo tenha dado errado, exibe uma mensagem de erro
	    	if (mysqli_connect_errno()) {
	    		printf("Conexão mysqli falhou: %s\n", mysqli_connect_error());die;
	    		exit();
	    	}else{
	    		//echo "conectou";
	    	}
	    	
	}
    
    // realiza uma inserção
    public function insere($laboratorio) {
        try {
            $sql = "INSERT INTO `laboratorio` (`CodUnidade`, `Tipo`, `Area`,`Capacidade`,`Nome`,`Sigla`," .
                    "`Resposta`,`LabEnsino`,`Nestacoes`,`Local`,`SisOperacional`,`CabEstruturado`,`Situacao`,`AnoAtivacao`) " .
                    " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = parent::prepare($sql);
            parent::beginTransaction();
            $stmt->bindValue(1, $laboratorio->getUnidade()->getCodunidade());
            $stmt->bindValue(2, $laboratorio->getTipo()->getCodigo());
            $stmt->bindValue(3, $laboratorio->getArea());
            $stmt->bindValue(4, $laboratorio->getCapacidade());
            $stmt->bindValue(5, $laboratorio->getNome());
            $stmt->bindValue(6, $laboratorio->getSigla());
            $stmt->bindValue(7, $laboratorio->getResposta());
            $stmt->bindValue(8, $laboratorio->getLabensino());
            $stmt->bindValue(9, $laboratorio->getNestacoes());
            $stmt->bindValue(10, $laboratorio->getLocal());
            $stmt->bindValue(11, $laboratorio->getSo());
            $stmt->bindValue(12, $laboratorio->getCabo());
            $stmt->bindValue(13, $laboratorio->getSituacao());
            $stmt->bindValue(14, $laboratorio->getAnoativacao());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    // realiza um Update
    public function altera($laboratorio) {
        try {
            $stmt = parent::prepare("UPDATE `laboratorio` SET `Tipo`=?,`Area`=?,`Capacidade` = ?,`Nome` =?,`Sigla` = ?,`Resposta`=?, `Labensino`=?, `Nestacoes`=?, `Local`=?, `SisOperacional`=?,`CabEstruturado`=?,`Situacao`=?,`AnoDesativacao`=? WHERE CodLaboratorio=?");
            parent::beginTransaction();
            $stmt->bindValue(1, $laboratorio->getTipo()->getCodigo());
            $stmt->bindValue(2, $laboratorio->getArea());
            $stmt->bindValue(3, $laboratorio->getCapacidade());
            $stmt->bindValue(4, $laboratorio->getNome());
            $stmt->bindValue(5, $laboratorio->getSigla());
           // print "upda labresposta" . $laboratorio->getResposta() . "<br/>";
            $stmt->bindValue(6, $laboratorio->getResposta());
            //print "upda labensino" . $laboratorio->getLabensino() . "<br/>";
            $stmt->bindValue(7, $laboratorio->getLabensino());
            $stmt->bindValue(8, $laboratorio->getNestacoes());
            $stmt->bindValue(9, $laboratorio->getLocal());
            $stmt->bindValue(10, $laboratorio->getSo());
            $stmt->bindValue(11, $laboratorio->getCabo());
            $stmt->bindValue(12, $laboratorio->getSituacao());
            $stmt->bindValue(13, $laboratorio->getAnodesativacao());
            $stmt->bindValue(14, $laboratorio->getCodlaboratorio());
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
            print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }   

    public function deleta($codlab) {
        try {
            parent::beginTransaction();
            $stmt = parent::prepare("DELETE FROM `laboratorio` WHERE `CodLaboratorio`=?");
            $stmt->bindValue(1, $codlab);
            $stmt->execute();
            parent::commit();
        } catch (PDOException $ex) {
            $db->rollback();
            print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
        }
    }

    public function Lista() {
        try {
            $stmt = parent::query("SELECT * FROM laboratorio");
            // retorna o resultado da query
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaLaboratorio($codlaboratorio) {
        try {
            $stmt = parent::prepare("SELECT * FROM laboratorio where Codlaboratorio=:codlaboratorio");
            $stmt->execute(array(':codlaboratorio' => $codlaboratorio));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    /* !
     * Esta função foi adicionada para realizar o SELECT de todos os laboratórios que atendam à
     * algumas condições descritas pelas variáveis a seguir:
     * \param $sqlparam string que contém parte da query, cujo conteúdo está relacionado ao tipo
     * da unidade (instituto, campus, núcleo, código, etc).
     * \param $situacao uma string que indica a situação do laboratório "A" ou "D"
     * \param $ano_inicio um inteiro que representa o ano inicial do período de pesquisa
     * \param $ano_fim um inteiro que representa o ano final do período de pesquisa
     *
     */

    public function buscaLabUnid($sqlparam, $curso, $situacao, $ano_inicio, $ano_fim) {
        try {
            $sql_sit = "";
            $sql = "";
            switch ($situacao) {
                case "A":
                    $sql_sit .= " AND (l.`Situacao` = 'A' AND ((l.`AnoAtivacao`>=:anoInicio) AND (l.`AnoAtivacao` <= :anoFim)) AND (l.`AnoDesativacao` IS NULL))";
                    break;
                case "D":
                    $sql_sit .= " AND (l.`Situacao` = 'D' AND ((:anoInicio <= l.`AnoDesativacao`) AND (l.`AnoDesativacao` <= :anoFim)))";
                    break;
            }
            if ($curso != "curso") {
                $sql = "SELECT ul.`NomeUnidade`, c.`Nome` AS Categoria, t.`Nome` AS Subcategoria, l.`Nome` AS Laboratorio, `Sigla`,
 `Area`, `Capacidade`, `AnoAtivacao`, `LabEnsino`, `Nestacoes`, `Local`,
 `SisOperacional`, `CabEstruturado` 
 FROM
 `laboratorio` l,
 `categoria` c, 
 `tdm_tipo_laboratorio` t, 
 `unidade` ul
 WHERE
 ul.`CodUnidade` = l.`CodUnidade`
 AND c.`Codigo` = t.`CodCategoria`
 AND l.`Tipo` = t.`Codigo`
 AND t.`CodCategoria` = c.`Codigo`";
            } else {
                $sql = "SELECT ul.`NomeUnidade`, cur.`NomeCurso`, uc.`NomeUnidade` as `UnidadeCurso`, c.`Nome` AS Categoria, t.`Nome` AS Subcategoria, l.`Nome` AS Laboratorio, `Sigla`,
 `Area`, `Capacidade`, `AnoAtivacao`, `LabEnsino`, `Nestacoes`, `Local`, `SisOperacional`,`CabEstruturado` 
 FROM
 `laboratorio` l LEFT OUTER JOIN `laboratorio_curso` lc
 ON l.`CodLaboratorio` = lc.`CodLaboratorio` 
 LEFT OUTER JOIN `curso` cur
 ON cur.`CodCurso` = lc.`CodCurso` LEFT OUTER JOIN `unidade` uc
 ON cur.`CodUnidade` = uc.`CodUnidade`,
 `categoria` c, 
 `tdm_tipo_laboratorio` t, 
 `unidade` ul
 WHERE
 ul.`CodUnidade` = l.`CodUnidade`
 AND c.`Codigo` = t.`CodCategoria`
 AND l.`Tipo` = t.`Codigo`";
            }
            $sql .= $sql_sit;
            $sql .= $sqlparam;
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':anoInicio' => $ano_inicio, ':anoFim' => $ano_fim));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaLaboratoriosUnidade($CodUnidade) {
        try {
            $stmt = parent::prepare("SELECT * FROM tdm_tipo_laboratorio t,laboratorio l where CodUnidade=:codunidade and t.Codigo = l.Tipo");
            $stmt->execute(array(':codunidade' => $CodUnidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscaLaboratoriosUnidade1($CodUnidade) {
        try {
            $stmt = parent::prepare("SELECT `CodLaboratorio`, t.`Nome` as Tipo, l.`Capacidade`, l.`Nome` FROM tdm_tipo_laboratorio t,laboratorio l where CodUnidade=:codunidade and t.Codigo = l.Tipo");
            $stmt->execute(array(':codunidade' => $CodUnidade));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }

    public function buscalabgraficocurso($ano, $cat, $tipo, $sit, $area, $labprat, $labinf, $so, $cab, $nomeunidade, $nivelcurso) {
        try {
            $sql = ""; // consulta principal
            $where = ""; // where
            $groupby = ""; // agrupamento
            /* configruração da cláusula from */
            $from = " from `laboratorio` l, `unidade` u, `laboratorio_curso` lc, `curso` cur";
            /* fim */

            /* Situação dos Laboratórios */
            if ($sit == "A") {
                $where .= " where (l.`AnoAtivacao`<=" . addslashes($ano);
                $where .= " and (l.`AnoDesativacao` >= " . addslashes($ano);
                $where .= " or l.`AnoDesativacao` is null))";
            } else if ($sit == "D") { // laboratórios desativados
                $where .= " where (l.`AnoAtivacao` <= " . addslashes($ano);
                $where .= " and l.`AnoDesativacao` <= " . addslashes($ano) . ")";
            } else if ($sit == "T") {
                $sql .= ", case l.`Situacao`";
                $sql.= "  when 'A' then 'Ativos'";
                $sql .= " when 'D' then 'Desativados'";
                $sql .= " end as `Situação dos laboratórios`";
                $where .= " where (l.`AnoAtivacao`<=" . addslashes($ano) . ")";
                $groupby .= ",l.`Situacao`";
            }
            $where .= " and l.`CodLaboratorio` = lc.`CodLaboratorio`";
            $where .= " and cur.`CodCurso` = lc.`CodCurso`";
            $where .= " and u.`CodUnidade` = l.`CodUnidade`";
            /* Fim */

            /* Configuração da unidade na consulta */
            $keys_u = array(
                "todas" => "",
                "institutos" => " and u.`NomeUnidade` like '%INSTITUTO%'",
                "faculdades" => " and u.`NomeUnidade` like '%FACULDADE%'",
                "hospitais" => " and u.`NomeUnidade` like '%HOSPITAL%'",
                "campus" => " and u.`NomeUnidade` like '%CAMPUS%'",
                "escolas" => " and u.`NomeUnidade` like '%ESCOLA%'",
                "nucleos" => " and u.`NomeUnidade` like '%NUCLEO%'",
            );

            // unidade configurada
            // neste caso é projeção
            if (array_key_exists(strtolower($nomeunidade), $keys_u)) {
                $sql .= ", u.`NomeUnidade` as `unidade`";
                $where .= $keys_u[strtolower($nomeunidade)];
                $groupby .= ", u.`NomeUnidade`";
            }
            // Unidade específica
            // neste caso é filtro
            else if ($nomeunidade != "") {
                $where .= $keys_u[strtolower($nomeunidade)];
                $where .= " and u.`CodUnidade` = l.`CodUnidade`";
                $where .= " and u.`NomeUnidade` = '" . addslashes($nomeunidade) . "'";
            } else { // unidade em branco gera por laboratórios
                $sql .= ", l.`Nome` as `Laboratório`";
                $groupby.= ", l.`Nome`";
            }

            /* monitora o nível do curso */
            if ($nivelcurso >= 1 && $nivelcurso <= 4) {
                if ($nivelcurso == 4) {
                    $where .= " and (cur.`CodNivel` = 1 or cur.`CodNivel` = 2)"; // filtro
                } else {
                    $where .= " and cur.`CodNivel` = $nivelcurso"; // filtro
                }
            } else if ($nivelcurso == 5) { /* projeção */
                $sql .= ", case cur.`CodNivel`";
                $sql .= " when '1' then 'Graduação'";
                $sql .= " when '2' then 'Pós-Graduação'";
                $sql .= " when '3' then 'Escola de Aplicação' end as `nível do curso`";
                $groupby .= ", cur.`CodNivel`";
            }
            /* categorias e subcategorias */
            if ($cat != "0" || $tipo != 0) {
                // seleciona as tabelas categoria ou tdm_tipo_laboratorio
                $from .= ", `categoria` c, `tdm_tipo_laboratorio` t";
                // faz o join
                $where .= " and l.`Tipo` = t.`Codigo`";
                $where .= " and c.`Codigo` = t.`CodCategoria`";
            }
            // se a categoria for igual a todas: projeção
            if ($cat == "todas") {
                $sql .= ", c.`Nome` as `categorias dos laboratórios`";
                $groupby .= ",c.`Nome`";
            }
            // se a categoria for especifica: filtro
            if ($cat != "todas" && $cat != "0") {
                $where .= " and c.`Codigo` = " . addslashes($cat);
            }
            // se o tipo for igual a todos: projeção
            if ($tipo == "todas") {
                $sql .= ", t.`Nome` as `subcategorias dos laboratórios`";
                $groupby .= ",t.`Nome`";
            }
            // tipo específico: filtro
            if (isset($tipo) && ($tipo != "todas" && $tipo != "0")) {
                $where .= " and l.`Tipo` = " . addslashes($tipo);
            }
            /* Fim da categoria */

            /* Laboratório de aulas práticas */
            // modificado no dia 14/05/2014
            // Neste dia foi decidido que o campo de laboratórios de aulas práticas
            // servem como filtro
            if (isset($labprat)) {
                $where .= " and l.`LabEnsino`  = 'S'\n"; // filtro
            }
            /* Fim dos laboratórios de aulas práticas */

            /* Laboratórios de informática */
            // Caso seja selecionado o laboratório de informática: filtro
            if (isset($labinf)) {
                $where .= " and (l.`SisOperacional` = 'W' OR l.`SisOperacional` = 'L')";
            }
            // se selecionar o sistema operacional: projeção (14/05/2014)
            if (isset($so)) {
                $sql .= ", case l.`SisOperacional`";
                $sql .= " when 'W' then 'Windows'";
                $sql .= " when 'L' then 'Linux' end as `sistema operacional`";
                $where .= " and l.`SisOperacional`!='0'";
                $groupby .= ", l.`SisOperacional`";
            }
            // se cabeamento estruturado: projeção
            if (isset($cab)) {
                $sql .= ", case `CabEstruturado`";
                $sql .= " when 'N' then 'Não'";
                $sql .= " when 'S' then 'Sim'";
                $sql .= " end as `cabeamento estruturado`";
                $groupby .= ", l.`CabEstruturado`";
            }
            /* Fim */
            // se a área não está selecionada
            // agrupa os resultados por número de laboratórios
            if (isset($area)) {
                $sql .= ", sum(l.`Area`) as `soma da área dos laboratórios que possuem curso`";
                $orderby = " order by sum(l.`Area`) asc";
            } else {
                $sql .= ", count(lc.`CodLabCurso`) as `total de cursos por laboratórios`";
                $orderby = " order by count(lc.`CodLabCurso`) asc";
            }
            /* realiza a verificação dos campos selecionados */
            if ($sql[0] == ",") {
                $sql = substr($sql, 1);
            }
            /* realiza a verificação dos agrupamentos */
            if ($groupby[0] == ",") {
                $groupby = " group by " . substr($groupby, 1);
            } else {
                $groupby = " group by $groupby";
            }
            if ($sql == " count(*) as `Número de laboratórios`" || $sql == " sum(l.`Area`) as `Soma da área dos laboratórios`") {
                $orderby = "";
                $groupby = "";
            }
            
            if ($groupby==" group by ") {
                $groupby = "";
            }
            
//            echo "select " . $sql . $from . $where . $groupby . $orderby;
//            exit();
            $stmt = parent::query("select " . $sql . $from . $where . $groupby . $orderby);
            return $stmt;
        } catch (PDOException $ex) {
            
        }
    }

    // esta consulta é para fazer os gráficos dos laboratórios sem a inclusão do curso
    public function buscalabgrafico($ano, $cat, $tipo, $sit, $area, $labprat, $labinf, $so, $cab, $nomeunidade) {
        try {
            $sql = "";
            $from = " from `laboratorio` l "; // string from
            $where = ""; // string where
            $groupby = "";
            $orderby = "";
            // unidades possíveis
            $keys_u = array(
                "todas" => "",
                "institutos" => " and u.`NomeUnidade` like '%INSTITUTO%'",
                "faculdades" => " and u.`NomeUnidade` like '%FACULDADE%'",
                "hospitais" => " and u.`NomeUnidade` like '%HOSPITAL%'",
                "campus" => " and u.`NomeUnidade` like '%CAMPUS%'",
                "escolas" => " and u.`NomeUnidade` like '%ESCOLA%'",
                "nucleos" => " and u.`NomeUnidade` like '%NUCLEO%'",
            );
            ////////////////////////////////////////////////////////////////////
            // Unidade
            ////////////////////////////////////////////////////////////////////
            // laboratórios ativos
            if ($sit == "A") {
                $where .= " where (l.`AnoAtivacao`<=" . addslashes($ano);
                $where .= " and (l.`AnoDesativacao` >= " . addslashes($ano);
                $where .= " or l.`AnoDesativacao` is null))";
            } else if ($sit == "D") { // laboratórios desativados
                $where .= " where (l.`AnoAtivacao` <= " . addslashes($ano);
                $where .= " and l.`AnoDesativacao` <= " . addslashes($ano) . ")";
            } else if ($sit == "T") {
                $sql .= ", case l.`Situacao`";
                $sql.= "  when 'A' then 'Ativos'";
                $sql .= " when 'D' then 'Desativados'";
                $sql .= " end as `situação dos laboratórios`";
                $where .= " where (l.`AnoAtivacao`<=" . addslashes($ano) . ")";
                $groupby .= ",l.`Situacao`";
            }

            if (array_key_exists(strtolower($nomeunidade), $keys_u)) {
                $sql .= ", u.`NomeUnidade` as `unidade`";
                $from .= ", `unidade` u";
                $where .= $keys_u[strtolower($nomeunidade)];
                $where .= " and u.`CodUnidade` = l.`CodUnidade`";
                $groupby .= ", u.`NomeUnidade`";
            } else if ($nomeunidade != "") {
                $from .= ", `unidade` u";
                $where .= $keys_u[strtolower($nomeunidade)];
                $where .= " and u.`CodUnidade` = l.`CodUnidade`";
                $where .= " and u.`NomeUnidade` = '" . addslashes($nomeunidade) . "'";
            }
            ////////////////////////////////////////////////////////////////////
            // todas as categorias ou todos os tipos
            if ($cat != "0" || $tipo != "0") {
                // seleciona as tabelas categoria ou tdm_tipo_laboratorio
                $from .= ", `categoria` c, `tdm_tipo_laboratorio` t";
                // faz o join
                $where .= " and l.`Tipo` = t.`Codigo`";
                $where .= " and c.`Codigo` = t.`CodCategoria`";
            }
            // se a categoria for igual a todas 
            if ($cat == "todas") {
                $sql .= ", c.`Nome` as ` categoria dos laboratórios`";
                $groupby .= ",c.`Nome`";
            }
            // se a categoria for especifica
            if ($cat != "todas" && $cat != "0") {
                $where .= " and c.`Codigo` = " . addslashes($cat);
            }
            // se o tipo for igual a todos
            if ($tipo == "todas") {
                $sql .= ", t.`Nome` as ` subcategoria dos laboratórios`";
                $groupby .= ",t.`Nome`";
            }
            // tipo específico
            if (isset($tipo) && ($tipo != "todas" && $tipo != "0")) {
                $where .= " and l.`Tipo` = " . addslashes($tipo);
            }
            // se laboratórios de aulas práticas
            if (isset($labprat)) {
                // modificado no dia 14/05/2013
                // Neste dia foi decidido que o campo de laboratórios de aulas práticas
                // servem como filtro
                $where .= " and l.`LabEnsino` = 'S'\n";
            }
            // se for laboratório de informática
            if (isset($labinf)) {
                $where .= " and (l.`SisOperacional` = 'W' OR l.`SisOperacional` = 'L')";
            }
            // se selecionar o sistema operacional
            if (isset($so)) {
                $sql .= ", case l.`SisOperacional`";
                $sql .= " when 'W' then 'Windows'";
                $sql .= " when 'L' then 'Linux' end as ` sistema operacional`";
                $where .= " and l.`SisOperacional`!='0'";
                $groupby .= ", l.`SisOperacional`";
            }
            // se cabeamento estruturado
            if (isset($cab)) {
                $sql .= ", case `CabEstruturado`";
                $sql .= " when 'N' then 'Não'";
                $sql .= " when 'S' then 'Sim'";
                $sql .= " end as ` situação do cabeamento estruturado no laboratório`";
                $groupby .= ", l.`CabEstruturado`";
            }
            // se a área não está selecionada
            // agrupa os resultados por número de laboratórios
            if (isset($area)) {
                $sql .= ", sum(l.`Area`) as `Soma da área dos laboratórios`";
                $orderby = " order by sum(l.`Area`) asc";
            } else {
                $sql .= ", count(*) as `Número de laboratórios`";
                $orderby = " order by count(*)";
            }
            /* realiza a verificação dos campos selecionados */
            if ($sql[0] == ",") {
                $sql = substr($sql, 1);
            }
            /* realiza a verificação dos agrupamentos */
            if ($groupby[0] == ",") {
                $groupby = " group by " . substr($groupby, 1);
            }
            if ($sql == " count(*) as `Número de laboratórios`" || $sql == " sum(l.`Area`) as `Soma da área dos laboratórios`") {
                $orderby = "";
                $groupby = "";
            }
//    echo "select " . $sql . $from . $where . $groupby . $orderby.'<br/>';
//    exit();
            $stmt = parent::query("select " . $sql . $from . $where . $groupby . $orderby);
            return $stmt;
        } catch (PDOException $ex) {
            echo $ex->getCode();
        }
    }

    public function fechar() {
        PDOConnectionFactory::Close();
    }

    // para exportar o relatório em PDF
public function buscaLaboratorioPDF($codunidade,$anobase) {
        try {
            $sql = "SELECT u.`NomeUnidade`,
 cat.`Nome` AS Categoria, t.`Nome` AS Subcategoria,
 l.`Nome` AS Laboratorio, c.`NomeCurso` as `Curso`, l.`Sigla` ,
 `Area` , `Capacidade` , `LabEnsino` , `Nestacoes` , `Local` ,
 `SisOperacional` , `CabEstruturado`
 FROM `categoria` cat, `tdm_tipo_laboratorio` t, `unidade` u, `laboratorio` l,
 `laboratorio_curso` lc,
 `curso` c
 WHERE l.`CodUnidade` = u.`CodUnidade`
 AND l.`Tipo` = t.`Codigo`
 AND t.`CodCategoria` = cat.`Codigo`
 AND u.`CodUnidade` = :codigo
 AND lc.`CodLaboratorio` = l.`CodLaboratorio`       		           		       		
 AND lc.`CodCurso` = c.`CodCurso`";
 if ($anobase<=2013)
 $sql .= " and  (c.`CodSigaa` is null ) ";//ano de validade do conjunto de cursos - mudanÃ§a devido ao sigaa
 else if ($anobase>2013 && $anobase<2018)
 $sql .= " and  (c.`CodSigaa` is not null or c.`Formato`='D') ";
 else $sql .= " and  anovalidade=2018 ";
 $sql.=" ORDER BY cat.`Nome` , t.`Nome` ASC";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':codigo' => $codunidade));
            return $stmt;  } 
        catch (PDOException $ex) 
        {
            echo "Erro: " . $ex->getMessage();
        }
    }
   
    // Fim
    // Função para buscar os laboratórios associados a uma unidade arbitrária
    public function buscalabunidade($query_param, $ano, $ano1, $situacao) {
        try {
            $sql = "SELECT u.`NomeUnidade` , l.`Nome` , l.`Area`, l.`AnoAtivacao`
 FROM `unidade` u, `laboratorio` l
 WHERE u.`CodUnidade` = l.`CodUnidade`";
            if ($situacao == "A") { // ativado
                $sql .= " AND l.`Situacao` = 'A'
 AND (l.`AnoAtivacao`>= :ano)
 AND (l.`AnoAtivacao`<= :ano1)
 AND (l.`AnoDesativacao` IS NULL) ";
            } elseif ($situacao == "D") { // desativado
                $sql .= " AND (l.`Situacao` = 'D' AND ((:ano >= l.`AnoDesativacao`) ";
                $sql .= " AND (l.`AnoDesativacao` <= :ano1)))\n";
            }
            $sql .= " " . $query_param;
            $sql .= " ORDER BY u.`NomeUnidade`, l.`Nome`";
            $stmt = parent::prepare($sql);
            $stmt->execute(array(':ano' => $ano, ':ano1' => $ano1));
            return $stmt;
        } catch (PDOException $ex) {
            echo "Erro: " . $ex->getMessage();
        }
    }
       
    //Função para buscar a área do ano 
    public function areaAno($codlab,$anoBase) {
    	try {
    		$stmt = parent::prepare("SELECT * FROM `area_lab` WHERE CodLaboratorio=:lab AND ano=:ano");
    		$stmt->execute(array(':lab' => $codlab,':ano'=>$anoBase));
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
    //Função que verifica se existe alguma área cadastrada para determinado lab e ano
    public function qtdAreaLabAno($codlab,$anoBase) {
    	try {
    		$sql = "SELECT COUNT(*) AS qtd FROM `area_lab` WHERE CodLaboratorio=".$codlab." AND ano=".$anoBase;
    		//rint $sql;
    		$stmt = parent::prepare($sql);
    		$num = $stmt->execute();    		
    		return $num;
    	} catch (PDOException $ex) {
    		print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
     
    
    //Insere área do lab
    public function insereArea($laboratorio,$ano,$justificativa){
    	 $sql = "INSERT INTO `area_lab`(CodLaboratorio,area,ano,justificativa) VALUES (".$laboratorio->getCodlaboratorio().",".$laboratorio->getArea().",".$ano.",'".$justificativa."')";
    	 $query = $this->mysqli->query($sql);
    	 print $sql;
    	 mysqli_close( $this->mysqli);
    }
    
    //Altera a área do lab
    public function alteraArea($laboratorio,$ano,$justificativa){
    	$sql = "UPDATE `area_lab` SET area=".$laboratorio->getArea().",justificativa='".$justificativa."' WHERE CodLaboratorio=".$laboratorio->getCodlaboratorio()." AND ano=".$ano;
    	$query = $this->mysqli->query($sql);
    	//print $sql;
    	mysqli_close( $this->mysqli);
    }
    
    //Buscar id do ultimo lab inserido 
    public function ultimoCodLab(){
    	
    try {
    		$sql = "SELECT CodLaboratorio AS cod FROM laboratorio ORDER BY CodLaboratorio DESC LIMIT 1";
    		//rint $sql;
    		$stmt = parent::prepare($sql);
    		$stmt->execute();    		
    		return $stmt;
    	} catch (PDOException $ex) {
    		print "Erro: Código:" . $ex->getCode() . "Mensagem" . $ex->getMessage();
    	}
    }
    
    //Insere área do novo lab
    public function insereAreaNovo($codLab,$area,$ano,$justificativa){
    	$sql = "INSERT INTO `area_lab`(CodLaboratorio,area,ano,justificativa) VALUES (".$codLab.",".$area.",".$ano.",'".$justificativa."')";
    	$query = $this->mysqli->query($sql);
    	//print $sql;
    	mysqli_close( $this->mysqli);
    }
    
    
    //Verificar a existência de cursos vinculados para o laboratório
    public function buscaVinculoLabCurso($codLab,$anobase) {
    	try {
    		$sql="SELECT COUNT(*) AS qtdCursos FROM curso c left join laboratorio_curso lc on lc.codcurso=c.codcurso";
    		
     		if ($anobase<2018){
    			  $sql.=" WHERE CodLaboratorio=:codLab and anovalidade=2013 and ano=2013";
    			
    		}else{
    			$sql.=" WHERE CodLaboratorio=:codLab and anovalidade=2018 and ano=2018";
    			
    		}
    		$stmt = parent::prepare($sql);
    		$stmt->execute(array(':codLab' => $codLab));
    		return $stmt;
    	} catch (PDOException $ex) {
    		echo "Erro: " . $ex->getMessage();
    	}
    }
    
    // Fim
}

?>
