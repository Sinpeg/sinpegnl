<?php
class GrupoDAO extends PDOConnectionFactory{

	// irá receber uma conexão
	public $conex = null;

	   public function GrupoDAO()
    {
	 $this->conex = PDOConnectionFactory::getConnection();
	}

	public function ListaAdmin() {
		try {

			$stmt = $this->conex->query("SELECT * FROM `grupo`");
			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}

	public function Lista() {
		try {

         if ($login=="admin"){
             $stmt = $this->conex->query("SELECT * FROM `grupo` ");
         }
         else {
             $stmt = $this->conex->query("SELECT * FROM `grupo` WHERE `Codigo` IN (20,4,21)");

         }			// retorna o resultado da query
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}

	public function Listagrupo($codgrupo) {
			try {

				$stmt = $this->conex->prepare("SELECT a.`Codgrupo`, b.`NomeUnidade` FROM `unidade` b, `grupounidade` a WHERE a.`CodUnidade` = b.`Codunidade` AND `a`.`Codgrupo` =:codigo");
				$stmt->execute(array(':codigo' => $codgrupo));
				return $stmt;
			} catch (PDOException $ex) {
				echo "Erro: " . $ex->getMessage();
			}
		}


	public function insere( $grupo, $unidadesel) {
		try {

			$fim = count($unidadesel)-1;
			$this->conex->beginTransaction();
			$indice=0;
			while($indice <= $fim){
				$stmt = $this->conex->prepare("INSERT INTO `grupounidade` (`Codunidade`,`Codgrupo`) VALUES ( ?, ?)");
				$stmt->bindValue(1, $unidadesel[$indice]);
				$stmt->bindValue(2, $grupo);
				$stmt->execute();
				++$indice;
			}

			$this->conex->commit();
		} catch (PDOException $ex) {
			$this->conex->rollback();
		}
	}

/*	public function Autoincrement() {
		try {

			$stmt = $this->conex->query("SELECT `Codigo` FROM `grupounidade` ORDER BY `grupounidade`.`Codigo` ASC");
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
			$stmt = $this->conex->prepare("UPDATE `grupounidade` SET `Codgrupo`=?  WHERE `Codunidade`=?");
			$this->conex->beginTransaction();
			$stmt->bindValue(1, $grupo);
			$stmt->bindValue(2, $unidade);
			$stmt->execute();
			$this->conex->commit();
		} catch (PDOException $ex) {
			$this->conex->rollback();
		}
	}

	public function buscacat($codcat) {
		try {

			$stmt = $this->conex->prepare("SELECT * FROM grupo WHERE Codigo=:codigo");
			$stmt->execute(array(':codigo' => $codcat));
			return $stmt;
		} catch (PDOException $ex) {
			echo "Erro: " . $ex->getMessage();
		}
	}

	public function buscaunidade($codunidade) {
		try {

			$stmt = $this->conex->prepare("SELECT * FROM grupounidade WHERE Codunidade=:codigo");
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
			$stmt = $this->conex->prepare($sql);
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

			$stmt = $this->conex->prepare("SELECT c.`Codaplicacao`, a.`Nome` FROM `aplicacoesdogrupo` c, `aplicacao` a WHERE c.`Codgrupo`=:codigo AND c.`Codaplicacao`=a.`Codigo` ORDER BY `Codaplicacao` ASC ");
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

$stmt = $this->conex->prepare($sql);

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
		$this->conex->beginTransaction();
		foreach ($rows as $row) {
			$stmt = $this->conex->prepare("DELETE FROM `grupounidade` WHERE `Codigo`=?");
			$stmt->bindValue(1, $row['Codigo'] );
			$stmt->execute();
    	}

		$this->conex->commit();
	}catch ( PDOException $ex ){
		echo "Erro: ".$ex->getMessage();
	}
}

public function insere_gu_temporaria( $grupo, $unidadesel,$codigousu)
{
        try {

            $fim = count($unidadesel)-1;
            $this->conex->beginTransaction();
            $indice=0;
            while($indice <= $fim){
                $stmt = $this->conex->prepare("INSERT INTO `gu_temporaria` (codunidade,codgrupo,codusuario) VALUES ( ?, ?,?)");
                $stmt->bindValue(1, $unidadesel[$indice]);
                $stmt->bindValue(2, $grupo);
                $stmt->bindValue(3, $codigousu);

                $stmt->execute();
                ++$indice;
            }

            $this->conex->commit();
        } catch (PDOException $ex) {
            $this->conex->rollback();
        }
    }

public function spvincular1( $grupo,$hierarquia, $codusuario,$unidade)
{
            try {

                $stmt = $this->conex->prepare("CALL vincularGruposUnidade(:g,:h,:u,:i)");

                $stmt->bindParam(':g',$grupo);//,$this->conex->PARAM_INT );
                $stmt->bindParam(':h', $hierarquia);//,$this->conex->PARAM_STR,200);
                $stmt->bindParam(':u', $codusuario);//,$this->conex->PARAM_INT);
                $stmt->bindParam('i', $unidade);//,$this->conex->PARAM_INT );


                $success = $stmt->execute();
                if($success){
                    //$result = $stmt->fetchAll($this->conex->FETCH_ASSOC);
                    //echo 'teste';
                }else{
                    echo 'Erro na sp';
                }

            } catch (PDOException $ex) {
                $this->conex->rollback();
                echo "Erro: " . $ex->getMessage();


            }
        }



	public function fechar()
	{
		PDOConnectionFactory::Close();
	}
}
?>
