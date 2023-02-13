<?php
/**
 * Intreface DAO
 *
 * @author: http://phpdao.com
 * @date: 2014-09-30 10:57
 */
interface HomologacaoDAO{

	/**
	 * Get Domain object by primry key
	 *
	 * @param String $id primary key
	 * @Return Homologacao 
	 */
	public function load($id);

	/**
	 * Get all records from table
	 */
	public function queryAll();
	
	/**
	 * Get all records from table ordered by field
	 * @Param $orderColumn column name
	 */
	public function queryAllOrderBy($orderColumn);
	
	/**
 	 * Delete record from table
 	 * @param homologacao primary key
 	 */
	public function delete($idHomologacao);
	
	/**
 	 * Insert record to table
 	 *
 	 * @param Homologacao homologacao
 	 */
	public function insert($homologacao);
	
	/**
 	 * Update record in table
 	 *
 	 * @param Homologacao homologacao
 	 */
	public function update($homologacao);	

	/**
	 * Delete all rows
	 */
	public function clean();

	public function queryByCodAplicacao($value);

	public function queryByCodUnidade($value);

	public function queryByCodSub($value);

	public function queryByAno($value);

	public function queryByDataRegistro($value);

	public function queryByDataDesbloqueio($value);

	public function queryByDataAlteracao($value);

	public function queryBySituacao($value);


	public function deleteByCodAplicacao($value);

	public function deleteByCodUnidade($value);

	public function deleteByCodSub($value);

	public function deleteByAno($value);

	public function deleteByDataRegistro($value);

	public function deleteByDataDesbloqueio($value);

	public function deleteByDataAlteracao($value);

	public function deleteBySituacao($value);


}
?>