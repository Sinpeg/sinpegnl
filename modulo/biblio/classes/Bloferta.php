<?php
class Bloferta  {

	private $id;
	private $Localoferta;
	private $Bibliemec;

	
	public function  _construct($id, $Localofert,
			$Bibliemec ) {
		$this->id = $id;
		$this->localoferta = $localoferta;
		$this->Bibliemec = $Bibliemec;
	}

	public function  getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function  getLocaloferta() {
		return $this->Localoferta;
	}

	public function setLocaloferta($localoferta) {
		$this->Localoferta = $localoferta;
	}

	public function  getBibliemec() {
		return $this->Bibliemec;
	}

	public function setBibliemec($Bibliemec) {
		$this->Bibliemec = $Bibliemec;
	}

}
?>