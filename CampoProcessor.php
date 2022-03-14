<?php 

/**
 * Classe CampoProcessor
 */
class CampoProcessor extends Model {

    protected static $useTable = "campo_processors";
    
    private $tipo;
    private $template_chave;
    private $return;

    function __construct($data = NULL) {
        if ($data != NULL) {
            parent::__construct(isset($data['id']) && $data['id'] != '' ? (int) $data['id'] : NULL);
            $this->tipo = isset($data['tipo']) && $data['tipo'] != '' ? $data['tipo'] : NULL;
            $this->template_chave = isset($data['template_chave']) && $data['template_chave'] != '' ? $data['template_chave'] : NULL;
            $this->return = isset($data['return']) && $data['return'] != '' ? $data['return'] : NULL;
        } else
            parent::__construct();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getTipo() {
        return $this->tipo;
    }
    
    public function getTemplateChave() {
        return $this->template_chave;
    }

    public function getReturn() {
        return $this->template_chave;
    }

    public function toArray() {
        return get_object_vars($this);
    }

}