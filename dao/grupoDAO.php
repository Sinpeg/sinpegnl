<?php
class GrupoDAO extends PDOConnectionFactory{

	// irá receber uma conexão
	public $conex = null;

  private $mysqli;
    // constructor
    public function __construct() {
    	 
    	$this->mysqli = new mysqli(PDOConnectionFactory::getHost(), PDOConnectionFactory::getUser(), PDOConnectionFactory::getSenha(), PDOConnectionFactory::getDb());
    	    	        $this->mysqli->set_charset('utf8');
    	
    	// Caso algo tenha dado errado, exibe uma mensagem de erro
    	if (mysqli_connect_errno()) {
    		printf("Connect failed: %s\n", mysqli_connect_error());
    		exit();
    	}else{
    		//echo "conectou";
    	}
    	
    	 
    }
	public function ListaAdmin() {
		try {

			$stmt = parent::query("SELECT * FROM `grupo`");
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}

	public function Lista($login) {
		try {

         if ($login=="admin"){
             $stmt = parent::query("SELECT * FROM `grupo` ");
         }
         else {
             $stmt = parent::query("SELECT * FROM `grupo` WHERE `Codigo` IN (20,21,22,23,26)");

         }			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}

	public function Listagrupo($codgrupo) {
			try {

				$stmt = parent::prepare("SELECT a.`Codgrupo`, b.`NomeUnidade` FROM `unidade` b, `grupounidade` a WHERE a.`CodUnidade` = b.`Codunidade` AND `a`.`Codgrupo` =:codigo");
				$stmt->execute(array(':codigo' => $codgrupo));
				return $stmt;
			} catch (PDOException $ex) {
				echo "Erro: " . $ex->getMessage();
			}
		}


	public function insere( $grupo, $unidadesel) {
		try {

			$fim = count($unidadesel)-1;
			parent::beginTransaction();
			$indice=0;
			while($indice <= $fim){
				$stmt = parent::prepare("INSERT INTO `grupounidade` (`Codunidade`,`Codgrupo`) VALUES ( ?, ?)");
				$stmt->bindValue(1, $unidadesel[$indice]);
				$stmt->bindValue(2, $grupo);
				$stmt->execute();
				++$indice;
			}

			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
		}
	}

/*	public function Autoincrement() {
		try {

			$stmt = parent::query("SELECT `Codigo` FROM `grupounidade` ORDER BY `grupounidade`.`Codigo` ASC");
			$anterior = 0;
			$valor = 0;
			$maior = 1;
			foreach($stmt as $s){
				$anterior = $maior;
				if($maior < $s["Codigo"]) {
					$anterior = $maior;
					$maior = $s["Codigo"];
				}
				if($maior-1 > $anterior) $valor = $maior-1;
			}
			if($valor == 0) $valor = $maior+1;
			return $valor;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	*/
	public function altera($grupo, $unidade) {
		try {
			$stmt = parent::prepare("UPDATE `grupounidade` SET `Codgrupo`=?  WHERE `Codunidade`=?");
			parent::beginTransaction();
			$stmt->bindValue(1, $grupo);
			$stmt->bindValue(2, $unidade);
			$stmt->execute();
			parent::commit();
		} catch (PDOException $ex) {
			parent::rollback();
		}
	}

	public function buscacat($codcat) {
		try {

			$stmt = parent::prepare("SELECT * FROM grupo WHERE Codigo=:codigo");
			$stmt->execute(array(':codigo' => $codcat));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}

	public function buscaunidade($codunidade) {
		try {

			$stmt = parent::prepare("SELECT * FROM grupounidade WHERE Codunidade=:codigo");
			$stmt->execute(array(':codigo' => $codunidade));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}
	//busca grupo unidade carla - falar como marcos
	public function buscaGrupoUnidade($codgrupo,$hierarquia) {
		try {
	        $hier=$hierarquia."%";
	        $sql="SELECT * FROM grupounidade gu, unidade u".
" WHERE Codgrupo =:codigo" .
" AND hierarquia_organizacional like :h1 ".
" AND hierarquia_organizacional!= :h2 " .
" AND gu.`Codunidade` = u.`Codunidade`";
			$stmt = parent::prepare($sql);
			$stmt->execute(array(':codigo' => $codgrupo,
			':h1' => $hier,
			':h2' => $hierarquia));

			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro:1 " . $ex->getMessage();
		}
	}

	public function buscaaplicagrupo($grupo) {
		try {
			$stmt = parent::prepare("SELECT c.`Codaplicacao`, a.`Nome` FROM `aplicacoesdogrupo` c, `aplicacao` a 
			WHERE c.`Codgrupo`=:codigo AND c.`Codaplicacao`=a.`Codigo` ORDER BY `Codaplicacao` ASC ");
			
			$stmt->execute(array(':codigo' => $grupo));

			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}

	public function buscaunidgrupo($grupo,$hierarquia) {//CARLA
		try {
			$pa1=$hierarquia;
	$sql="SELECT u.`CodUnidade` , u.`NomeUnidade`"
		." FROM `grupounidade` c, `unidade` u "
." WHERE c.`Codgrupo` =:g "
				."	AND c.`Codunidade` = u.`CodUnidade` ";


	if (!empty($hierarquia))	{//se nao for admin

$sql.=	"AND `hierarquia_organizacional` <> :p1 "
		.	"AND `hierarquia_organizacional` LIKE :p2 ";
	}

$sql.=	"ORDER BY c.`Codunidade` ASC ";

$stmt = parent::prepare($sql);

		if (!empty($hierarquia))	{//se nao for admin
			$pa2=$pa1."%";
			$stmt->execute(array(':g'=>$grupo,':p1'=>$pa1,':p2'=>$pa2));
		}else $stmt->execute(array(':g'=>$grupo));

		return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}

public function deletaGrupoUnidade($rows){
	try{
		parent::beginTransaction();
		foreach ($rows as $row) {
			$stmt = parent::prepare("DELETE FROM `grupounidade` WHERE `Codigo`=?");
			$stmt->bindValue(1, $row['Codigo'] );
			$stmt->execute();
    	}

		parent::commit();
	}catch ( PDOException $ex ){
		echo "Erro: ".$ex->getMessage();
	}
}

public function delete_gu_temporaria($grupo,$codusuario){
	try{
		parent::beginTransaction();

			$stmt = parent::prepare("DELETE FROM gu_temporaria WHERE codusuario=? AND codgrupo=?;");
			$stmt->bindValue(1, $codusuario );
			$stmt->bindValue(2, $grupo );
			$stmt->execute();


		parent::commit();
	}catch ( PDOException $ex ){
		echo "Erro: ".$ex->getMessage();
	}
}



public function insere_gu_temporaria( $grupo, $unidadesel,$codigousu)
{
        try {

            $fim = count($unidadesel)-1;
            parent::beginTransaction();
            $indice=0;
            while($indice <= $fim){
                $stmt = parent::prepare("INSERT INTO `gu_temporaria` (codunidade,codgrupo,codusuario) VALUES ( ?, ?,?)");
                $stmt->bindValue(1, $unidadesel[$indice]);
                $stmt->bindValue(2, $grupo);
                $stmt->bindValue(3, $codigousu);

                $stmt->execute();
                ++$indice;
            }

            parent::commit();
        } catch (PDOException $ex) {
            parent::rollback();
        }
    }

public function spvincular1( $grupo,$hierarquia, $codusuario,$unidade)
{
            try {

                $stmt = parent::prepare("CALL vincularGruposUnidade(:g,:h,:u,:i)");

                $stmt->bindParam(':g',$grupo);//,parent::PARAM_INT );
                $stmt->bindParam(':h', $hierarquia);//,parent::PARAM_STR,200);
                $stmt->bindParam(':u', $codusuario);//,parent::PARAM_INT);
                $stmt->bindParam(':i', $unidade);//,parent::PARAM_INT );

                
                $success = $stmt->execute();
                
                if($success){
                    //$result = $stmt->fetchAll(parent::FETCH_ASSOC);
                    //echo 'teste';
                }else{
                    echo 'Erro na sp';
                }
                
            	
          //  $this->mysqli->query("CALL vincularGruposUnidade('".$grupo."','".$hierarquia."','".$codusuario."','".$unidade."')");	
            	
         //   mysqli_close($this->mysqli);
            	
            	

            } catch (PDOException $ex) {
                parent::rollback();
                echo "Erro: " . $ex->getMessage();
                

            }
        }



	public function fechar()
	{
		PDOConnectionFactory::Close();
	}
}
?>
