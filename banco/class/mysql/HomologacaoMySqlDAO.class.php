<?php
//Exibir Erros
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
//$sessao = $_SESSION["sessao"];
/**
 * Class that operate on table 'homologacao'. Database Mysql.
 *
 * @author: http://phpdao.com
 * @date: 2014-09-30 10:57
 */
class HomologacaoMySqlDAO implements HomologacaoDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @return HomologacaoMySql 
	 */
	public function load($id){
		$sql = 'SELECT * FROM homologacao WHERE idHomologacao = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($id);
		return $this->getRow($sqlQuery);
	}

	/**
	 * Get all records from table
	 */
	public function queryAll(){
		$sql = 'SELECT * FROM homologacao';
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	
	/**
	 * Get all records from table ordered by field
	 *
	 * @param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn){
		$sql = 'SELECT * FROM homologacao ORDER BY '.$orderColumn;
		$sqlQuery = new SqlQuery($sql);
		return $this->getList($sqlQuery);
	}
	
	/**
 	 * Delete record from table
 	 * @param homologacao primary key
 	 */
	public function delete($idHomologacao){
		$sql = 'DELETE FROM homologacao WHERE idHomologacao = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($idHomologacao);
		return $this->executeUpdate($sqlQuery);
	}
	
	/**
 	 * Insert record to table
 	 *
 	 * @param HomologacaoMySql homologacao
 	 */
	public function insert($homologacao){
		$sql = 'INSERT INTO homologacao (CodAplicacao, CodUnidade, CodSub, ano, dataRegistro,  situacao) VALUES (?, ?, ?,  ?, ?, ?)';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($homologacao->codAplicacao);
		$sqlQuery->setNumber($homologacao->codUnidade);
		$sqlQuery->setNumber($homologacao->codSub);
		$sqlQuery->setNumber($homologacao->ano);
		$sqlQuery->set($homologacao->dataRegistro);
		//$sqlQuery->set($homologacao->dataDesbloqueio);
		//$sqlQuery->set($homologacao->dataAlteracao);
		
		$sqlQuery->set($homologacao->situacao);

		$id = $this->executeInsert($sqlQuery);	
		$homologacao->idHomologacao = $id;
		return $id;
	}
	
	/**
 	 * Update record in table
 	 *
 	 * @param HomologacaoMySql homologacao
 	 */
	public function update($homologacao){
		$sql = 'UPDATE homologacao SET CodAplicacao = ?, CodUnidade = ?, CodSub = ?, ano = ?, dataRegistro = ?, dataDesbloqueio = ?, dataAlteracao = ?, situacao = ? WHERE idHomologacao = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($homologacao->codAplicacao);
		$sqlQuery->setNumber($homologacao->codUnidade);
		$sqlQuery->setNumber($homologacao->codSub);
		$sqlQuery->setNumber($homologacao->ano);
		$sqlQuery->set($homologacao->dataRegistro);
		$sqlQuery->set($homologacao->dataDesbloqueio);
		$sqlQuery->set($homologacao->dataAlteracao);
		$sqlQuery->set($homologacao->situacao);

		$sqlQuery->setNumber($homologacao->idHomologacao);
		return $this->executeUpdate($sqlQuery);
	}

	/**
 	 * Delete all rows
 	 */
	public function clean(){
		$sql = 'DELETE FROM homologacao';
		$sqlQuery = new SqlQuery($sql);
		return $this->executeUpdate($sqlQuery);
	}

	public function queryByCodAplicacao($value){
		$sql = 'SELECT * FROM homologacao WHERE CodAplicacao = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	public function queryByCodUnidade($value){
		$sql = 'SELECT * FROM homologacao WHERE CodUnidade = ?';
		$sqlQuery = new SqlQuery($sql);
		
		$sqlQuery->setNumber($value);
		                				
		
		return $this->getList($sqlQuery);
	}

	public function queryByCodSub($value){
		$sql = 'SELECT * FROM homologacao WHERE CodSub = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	public function queryByAno($value){
		$sql = 'SELECT * FROM homologacao WHERE ano = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->getList($sqlQuery);
	}

	public function queryByDataRegistro($value){
		$sql = 'SELECT * FROM homologacao WHERE dataRegistro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	public function queryByDataDesbloqueio($value){
		$sql = 'SELECT * FROM homologacao WHERE dataDesbloqueio = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	public function queryByDataAlteracao($value){
		$sql = 'SELECT * FROM homologacao WHERE dataAlteracao = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}

	public function queryBySituacao($value){
		$sql = 'SELECT * FROM homologacao WHERE situacao = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->getList($sqlQuery);
	}


	public function deleteByCodAplicacao($value){
		$sql = 'DELETE FROM homologacao WHERE CodAplicacao = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByCodUnidade($value){
		$sql = 'DELETE FROM homologacao WHERE CodUnidade = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByCodSub($value){
		$sql = 'DELETE FROM homologacao WHERE CodSub = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByAno($value){
		$sql = 'DELETE FROM homologacao WHERE ano = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->setNumber($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByDataRegistro($value){
		$sql = 'DELETE FROM homologacao WHERE dataRegistro = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByDataDesbloqueio($value){
		$sql = 'DELETE FROM homologacao WHERE dataDesbloqueio = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteByDataAlteracao($value){
		$sql = 'DELETE FROM homologacao WHERE dataAlteracao = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}

	public function deleteBySituacao($value){
		$sql = 'DELETE FROM homologacao WHERE situacao = ?';
		$sqlQuery = new SqlQuery($sql);
		$sqlQuery->set($value);
		return $this->executeUpdate($sqlQuery);
	}


	
	/**
	 * Read row
	 *
	 * @return HomologacaoMySql 
	 */
	protected function readRow($row){
		$homologacao = new Homologacao();
		
		$homologacao->idHomologacao = $row['idHomologacao'];
		$homologacao->codAplicacao = $row['CodAplicacao'];
		$homologacao->codUnidade = $row['CodUnidade'];
		$homologacao->codSub = $row['CodSub'];
		$homologacao->ano = $row['ano'];
		$homologacao->dataRegistro = $row['dataRegistro'];
		$homologacao->dataDesbloqueio = $row['dataDesbloqueio'];
		$homologacao->dataAlteracao = $row['dataAlteracao'];
		$homologacao->situacao = $row['situacao'];

		return $homologacao;
	}
	
	protected function getList($sqlQuery){
		$tab = QueryExecutor::execute($sqlQuery);
		$ret = array();
		for($i=0;$i<count($tab);$i++){
			$ret[$i] = $this->readRow($tab[$i]);
		}
		return $ret;
	}
	
	/**
	 * Get row
	 *
	 * @return HomologacaoMySql 
	 */
	protected function getRow($sqlQuery){
		$tab = QueryExecutor::execute($sqlQuery);
		if(count($tab)==0){
			return null;
		}
		return $this->readRow($tab[0]);		
	}
	
	/**
	 * Execute sql query
	 */
	protected function execute($sqlQuery){
		return QueryExecutor::execute($sqlQuery);
	}
	
		
	/**
	 * Execute sql query
	 */
	protected function executeUpdate($sqlQuery){
		return QueryExecutor::executeUpdate($sqlQuery);
	}

	/**
	 * Query for one row and one column
	 */
	protected function querySingleResult($sqlQuery){
		return QueryExecutor::queryForString($sqlQuery);
	}

	/**
	 * Insert row to table
	 */
	protected function executeInsert($sqlQuery){
		return QueryExecutor::executeInsert($sqlQuery);
	}
}
?>