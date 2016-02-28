<?php 
/**
 * Classe Funcao
 */
class Funcao extends Model {

    protected static $useTable = "funcao";
	
    private $nome;

    function Funcao($data = NULL) {
		if ($data != NULL) {
			parent::__construct(isset($data['id']) && $data['id'] != '' ? (int) $data['id'] : NULL);
			$this->nome = isset($data['nome']) && $data['nome'] != '' ? $data['nome'] : NULL;
		} else
			parent::__construct();
    }
	
	public function getId() {
		return $this->id;
	}
	
	public function getNome() {
		return $this->nome;
	}

    public function toArray() {
		return get_object_vars($this);
    }

}