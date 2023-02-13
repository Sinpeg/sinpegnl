<?php
class PDOConnectionFactory{
	private static $link = null ;
	private static $dbType = "mysql";
	private static $host = "localhost";
	private static $user = "root";
	private static $senha = "";
	private static $db = "sisraa";

	private static function getConnection ( ) {

		$link = null ;
		$dbType = "mysql";
		$host = "localhost";//"200.239.64.109";
		$user = "root";
		$senha = "";
		$db = "sisraa";//"st_sisraaTest";
		if ( self :: $link ) {
			return self :: $link ;
		}

		try {
			self :: $link  = new PDO($dbType . ":host=" .$host . ";dbname=" . $db, $user, $senha, array(PDO::ATTR_PERSISTENT => TRUE));
			self :: $link ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self :: $link ->exec("SET CHARACTER SET utf8");
			return self :: $link ;
		} catch (PDOException $ex) {
			echo "Conexão falhou: " . $ex->getMessage();
			exit();
		}

	}

	public static function __callStatic ( $name, $args ) {
		$callback = array ( self :: getConnection( ), $name ) ;
		return call_user_func_array ( $callback , $args ) ;
	}


	public static function getHost(){
		return self::$host;
	}

	public static function getDbtype(){
		return self::$dbType;
	}

	public static function getUser(){
		return self::$user;
	}

	public static function getSenha(){
		return self::$senha;
	}

	public static function getDb(){
		return self::$db;
	}

	public static function close(){
		if( self::$link != null ){
			self::$link = null;
		}
	}

}
?>
