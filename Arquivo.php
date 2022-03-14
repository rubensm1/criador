<?php 
/**
 * Classe Arquivo
 */
class Arquivo extends Model {

    protected static $useTable = "arquivo";
    
    private $nome;
    private $funcao_id;
    private $conteudo;

    function __construct($data = NULL) {
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

    private function getCampoProcessors ($chave) {
        $res = CampoProcessor::select(null, ['template_chave' => $chave], 'id');
        $processors = [];
        foreach ($res as $r) {
            $cp = new CampoProcessor((array)$r);
            $processors[$cp->getTipo()] = $cp;
        }
        return $processors;
    }
    
    public function aplicarCampos($campos = array()) {
        /*$campos = array();
        $campos[] = new Campo("string","informacao");
        $campos[] = new Campo("int","ordem");
        $campos[] = new Campo("text","descricao", FALSE);
        $campos[] = new Campo("enum","familia",FALSE,array("Tipo 1", "Tipo 2", "Tipo 3"));*/
        
        $subs = array("","","","","","","","");
        foreach ($campos as $c){
			// PHP Fields Model
            $subs[0] .= $c->toPhpF() . "\n    ";
			// PHP Fields Construtor
            $subs[1] .= $c->toPhpC() . "\n            ";
			// Javascript Construtor
            $subs[2] .= $c->toJs() . "\n        ";
			// Typescript Fields 
            $subs[3] .= $c->toTsF() . "\n    ";
			// Typescript Construtor
            $subs[4] .= $c->toTsC1() . "\n        ";
			// Typescript Construtor
            $subs[5] .= $c->toTsC2() . "\n        ";
			// Typescript Construtor
            $subs[6] .= $c->toTsC3() . "\n        ";
			// HTML Fields
            $subs[7] .= $c->toHtml() . "\n        ";
            // Generic Fields
            $subs[8] .= $c->toGeneric() . "\n        ";
        }
            
        $this->conteudo = str_replace(array("M@@@","m@@@","j@@@","T@@@","t1@@@","t2@@@","t3@@@","h@@@","gg@@@"),$subs,$this->conteudo);

        /*preg_match_all("/###[a-zA-Z0-9_]+###/",
            $this->conteudo,
            $out, PREG_PATTERN_ORDER);

        $query = "SELECT * FROM templates WHERE ";
        $first = true;
        foreach ($out[0] as $o) {
            $query .= ($first ? '' : ' OR ') . "chave = '". str_replace("###", "", $o) ."'";
            $first = false;
        }
        if (!$first) {
            $replacers = [];
            $result = Template::nativeQuery($query);
            foreach ($result as $r) {
                $template = new Template((array)$r);
                $processors = getCampoProcessors($template->getChave());
                $replacers[$template->getChave()] = (object) [
                    'template' => $template,
                    'processors' => $processors
                ];
                $area = "";
                foreach ($campos as $c) {
                    $area .= str_replace('%nome%', $c->getNome(), isset($processors[$c->getTipo()]) ? $processors[$c->getTipo()]->getReturn() : $template->getDefaultReturn() ) . "\n";
                    for ($i = 0; $i < $template->getSpacesStartLine(); $i++) {
                        $area .= "    "; 
                    }
                }
            }
        }*/
    }

    public function toArray() {
        return get_object_vars($this);
    }

}