<?php 

/**
 * Classe Comando
 */
class Comando extends Model {

    protected static $useTable = "comando";
    
    private $ordem;
    private $funcao_id;
    private $comando;

    function Comando($data = NULL) {
        if ($data != NULL) {
            parent::__construct(isset($data['id']) && $data['id'] != '' ? (int) $data['id'] : NULL);
            $this->ordem = isset($data['ordem']) && $data['ordem'] != '' ? (int) $data['ordem'] : NULL;
            $this->funcao_id = isset($data['funcao_id']) && $data['funcao_id'] != '' ? (int) $data['funcao_id'] : NULL;
            $this->comando = isset($data['comando']) && $data['comando'] != '' ? utf8_encode($data['comando']) : NULL;
        } else
            parent::__construct();
    }
    
    public function getOrdem() {
        return $this->ordem;
    }
    
    public function getFuncaoId() {
        return $this->funcao_id;
    }
    
    public function getComando() {
        return $this->comando;
    }
    
    public function aplicarNomeClasse($nomeClasse) {
        if (PHP_OS == "WINNT")
            $this->comando = str_replace("/","\\", $this->comando);
        $this->comando = str_replace(array("A@@@","a@@@"),array( ucfirst(strtolower($nomeClasse)),strtolower($nomeClasse) ), $this->comando);
    }
    
    public function toArray() {
        return get_object_vars($this);
    }

}