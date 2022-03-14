<?php 

/**
 * Classe Template
 */
class Template extends Model {

    protected static $useTable = "templates";
    
    private $nome;
    private $chave;
    private $default_return;
    private $spaces_start_lines;

    function __construct($data = NULL) {
        if ($data != NULL) {
            parent::__construct(isset($data['id']) && $data['id'] != '' ? (int) $data['id'] : NULL);
            $this->nome = isset($data['nome']) && $data['nome'] != '' ? $data['nome'] : NULL;
            $this->chave = isset($data['chave']) && $data['chave'] != '' ? $data['chave'] : NULL;
            $this->default_return = isset($data['default_return']) && $data['default_return'] != '' ? $data['default_return'] : NULL;
            $this->spaces_start_lines = isset($data['spaces_start_lines']) && $data['spaces_start_lines'] != '' ? (int) $data['spaces_start_lines'] : NULL;
        } else
            parent::__construct();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getNome() {
        return $this->nome;
    }
    
    public function getChave() {
        return $this->chave;
    }

    public function getDefaultReturn() {
        return $this->default_return;
    }

    public function getSpacesStartLine() {
        return $this->spaces_start_lines;
    }
    public function toArray() {
        return get_object_vars($this);
    }

}