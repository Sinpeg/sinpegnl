<?php
/**
 * Object represents connection to database
 *
 * @author: http://phpdao.com
 * @date: 27.11.2007
 */
class Connection{
	private $connection;

	public function __construct(){
		$this->connection = ConnectionFactory::getConnection();
	}

	public function close(){
		ConnectionFactory::close($this->connection);
	}

	/**
	 * Wykonanie zapytania sql na biezacym polaczeniu
	 *
	 * @param sql zapytanie sql
	 * @return wynik zapytania
	 */
	public function executeQuery($sql){
		//return query($sql, $this->connection);
		return $this->connection->query($sql);
		
		 
	}
}
?>