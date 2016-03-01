<?php 
/**
 * Classe Arquivo
 */
class Arquivo extends Model {

    protected static $useTable = "arquivo";
    
    private $nome;
    private $funcao_id;
    private $conteudo;

    function Arquivo($data = NULL) {
        if ($data != NULL) {
            parent::__construct(isset($data['id']) && $data['id'] != '' ? (int) $data['id'] : NULL);
            $this->nome = isset($data['nome']) && $data['nome'] != '' ? utf8_encode($data['nome']) : NULL;
            $this->funcao_id = isset($data['funcao_id']) && $data['funcao_id'] != '' ? (int) $data['funcao_id'] : NULL;
            $this->conteudo = isset($data['conteudo']) && $data['conteudo'] != '' ? utf8_encode($data['conteudo']) : NULL;
        } else
            parent::__construct();
    }
    
    public function getNome() {
        return $this->nome;
    }
    
    public function getFuncaoId() {
        return $this->funcao_id;
    }
    
    public function getConteudo() {
        return $this->conteudo;
    }
    
    public function aplicarNomeClasse($nomeClasse) {
        $this->nome = str_replace(array("A@@@","a@@@"),array(ucfirst(strtolower($nomeClasse)),strtolower($nomeClasse)),$this->nome);
        $this->conteudo = str_replace(array("A@@@","a@@@"),array(ucfirst(strtolower($nomeClasse)),strtolower($nomeClasse)),$this->conteudo);
    }
    
    public function aplicarCampos($campos = array()) {
        /*$campos = array();
        $campos[] = new Campo("string","informacao");
        $campos[] = new Campo("int","ordem");
        $campos[] = new Campo("text","descricao", FALSE);
        $campos[] = new Campo("enum","familia",FALSE,array("Tipo 1", "Tipo 2", "Tipo 3"));*/
        
        $subs = array("","","","");
        foreach ($campos as $c){
            $subs[0] .= $c->toPhpF() . "\n    ";
            $subs[1] .= $c->toPhpC() . "\n            ";
            $subs[2] .= $c->toJs() . "\n        ";
            $subs[3] .= $c->toHtml() . "\n        ";
        }
            
        $this->conteudo = str_replace(array("M@@@","m@@@","j@@@","h@@@"),$subs,$this->conteudo);
    }

    public function toArray() {
        return get_object_vars($this);
    }

}