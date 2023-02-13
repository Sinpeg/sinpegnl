<?php
class TipoinfraensinoDAO extends PDOConnectionFactory {
 public $conex = null;
 
 // constructor
/*
 public function TipoinfraensinoDAO() {
 $this->conex = PDOConnectionFactory::getConnection();
 }
 */
 
 public function tiponaoinserido($codunidade, $anobase) {
 try {
 $sql = "SELECT t1.`Codigo` , t1.`Nome` FROM `tdm_infra_ensino` t1 WHERE t1.`Codigo` NOT IN (" .
 " SELECT t2.`Tipo` FROM `infraensino` t2 WHERE t1.`Codigo` = t2.`Tipo` AND `CodUnidade` =:codunidade AND `Ano` =:anobase)";
 $stmt = parent::prepare($sql);
 $stmt->execute(array(':codunidade' => $codunidade, ':anobase' => $anobase));
 // retorna o resultado da query
 return $stmt;
 } catch (PDOException $ex) {
 echo "Erro: " . $ex->getMessage();
 }
 }
 public function Lista() {
 try {
 $stmt = parent::query("SELECT * FROM tdm_infra_ensino");
 // retorna o resultado da query
 return $stmt;
 } catch (PDOException $ex) {
 echo "Erro: " . $ex->getMessage();
 }
 }
 public function buscatipoinfraensino($codtpinfraensino) {
 try {
 $stmt = parent::prepare("SELECT * FROM tdm_infra_ensino WHERE Codigo=:codigo");
 $stmt->execute(array(':codigo' => $codtpinfraensino));
 return $stmt;
 } catch (PDOException $ex) {
 echo "Erro: " . $ex->getMessage();
 }
 }
 public function buscatipo1($tipo, $ano, $ano1, $sql_param) {
 try {
 $sql = "SELECT u.NomeUnidade, tie.Nome, i.Quantidade, i.Ano
 FROM infraensino i, unidade u, tdm_infra_ensino tie
 WHERE i.Tipo = tie.Codigo
 AND u.CodUnidade = i.CodUnidade
 AND i.Tipo = tie.Codigo
 AND (
 i.Ano >= :ano
 AND i.Ano <= :ano1
 )";
 $sql .= " " . $sql_param;
 if ($tipo != 0) {
 $sql .= " AND i.`Tipo` = " . $tipo;
 }
 $sql .= " ORDER BY u.`NomeUnidade` ASC";
 $stmt = parent::prepare($sql);
 $stmt->execute(array(":ano" => $ano, ":ano1" => $ano1));
 return $stmt;
 } catch (PDOException $ex) {
 
 }
 }
 public function buscatipo($tipo, $ano, $ano1, $nomeunidade) {
 try {
 $unid = array(
 "TODAS" => '',
 "CAMPI" => "\n and u.`NomeUnidade` like 'CAMPUS%'",
 "ESCOLAS" => "\n and u.`NomeUnidade` like 'ESCOLA%'",
 "HOSPITAIS" => "\n and u.`NomeUnidade` like 'HOSPITAL%'",
 "INSTITUTOS" => "\n and u.`NomeUnidade` like 'INSTITUTO%'",
 "NUCLEOS" => "\n and u.`NomeUnidade` like 'NUCLEO%'",
 "FACULDADES" => "\n and u.`NomeUnidade` like 'FACULDADE%'"
 );
 $index = strtoupper($nomeunidade);
 $busca_unidade = (isset($unid[$index])) ? ($unid[$index]) : ("\n and u.NomeUnidade = '" . addslashes($nomeunidade) . "'");
 $sql = "SELECT u.NomeUnidade, tie.Nome, i.Quantidade, i.Ano
 FROM infraensino i, unidade u, tdm_infra_ensino tie
 WHERE i.Tipo = tie.Codigo
 AND u.CodUnidade = i.CodUnidade
 AND i.Tipo = tie.Codigo
 AND (i.Ano >= :ano AND i.Ano <= :ano1)";
 $sql .= $busca_unidade;
 if ($tipo != 0) {
 $sql .= " AND i.`Tipo` = " . $tipo;
 }
 $sql .= " ORDER BY `NomeUnidade`, ABS(i.`Ano`), ABS(i.`Quantidade`) DESC";
 $stmt = parent::prepare($sql);
 $stmt->execute(array(":ano" => $ano, ":ano1" => $ano1));
 return $stmt;
 } catch (PDOException $ex) {
 
 }
 }
 public function fechar() {
 PDOConnectionFactory::Close();
 }
}
?>