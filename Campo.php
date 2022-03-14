<?php 

class Campo {
    
    private $tipo;
    private $nome;
    private $required;
    private $options;
    
    public function __construct($data) {
        $this->tipo = isset($data['tipo']) && $data['tipo'] != '' ? strtolower($data['tipo']) : NULL;
        $this->nome = isset($data['nome']) && $data['nome'] != '' ? $data['nome'] : NULL;
        $this->required = isset($data["required"]) ? ( strtolower($data["required"]) == "false" || $data["required"] == "0" ? FALSE : (boolean) $data["required"]) : NULL;
        $this->options = isset($data['options']) && $data['options'] != '' ? $data['options'] : NULL;
    }
    
    public static function getCampoId() {
        return new Campo( array(
            'tipo' => 'int',
            'nome' => 'id',
            'required' => TRUE,
            'options' => NULL
        ));
    }

    public function getTipo () {
        return $this->tipo;
    }

    public function getNome() {
        return $this->nome;
    }
    
    public static function getCampoDefault() {
        return new Campo( array(
            'tipo' => 'default',
            'nome' => '',
            'required' => FALSE,
            'options' => NULL
        ));
    }
    
    //tipos: default, text, int, number, boolean, date, enum
    public function toHtml() {
        switch ($this->tipo) {
            case "text":
                return '<tr><td>'. $this->nome .':</td><td><textarea name="'. $this->nome .'" '. ($this->required ? "required" : "") .'></textarea></td></tr>';
            case "number":
            case "int":
                return '<tr><td>'. $this->nome .':</td><td><input type="number" name="'. $this->nome .'" value="" '. ($this->required ? "required" : "") .' /></td></tr>';
            case "boolean": 
                return '<tr><td>'. $this->nome .':</td><td><input type="checkbox" name="'. $this->nome .'" value="true" /></td></tr>';
            case "date":
                return '<tr><td>'. $this->nome .':</td><td><input type="date" name="'. $this->nome .'" value="'. date("Y-m-d") .'" '. ($this->required ? "required" : "") .' /></td></tr>';
            case "time":
                return '<tr><td>'. $this->nome .':</td><td><input type="text" name="'. $this->nome .'" pattern="[0-9]{1,2}:[0-5][0-9]:[0-5][0-9]$" title="0:00:00 ~ 99:59:59" value="0:00:00" '. ($this->required ? "required" : "") .' /></td></tr>';
                //return '<tr><td>'. $this->nome .':</td><td><input type="time" name="'. $this->nome .'" value="00:00:00" step="1" '. ($this->required ? "required" : "") .' /></td></tr>';
            case "enum":
                $field = '<tr><td>'. $this->nome .':</td><td><select name="'. $this->nome .'" '. ($this->required ? "required" : "") .'><option value=""></option>';
                if ($this->options)
                    foreach ($this->options as $option)
                        $field .= '<option value="'. $option .'">'. $option .'</option>';
                return $field . '</select></td></tr>';
            default:
                return '<tr><td>'. $this->nome .':</td><td><input type="text" name="'. $this->nome .'" value="" '. ($this->required ? "required" : "") .' /></td></tr>';
        }
    }
    
    public function toPhpF() {
        return 'private $' . $this->nome . ";";
    }
    
    public function toPhpC() {
        switch ($this->tipo) {
            case "number":
                return '$this->'. $this->nome .' = isset($data["'. $this->nome .'"]) && $data["'. $this->nome .'"] != "" ? (float) $data["'. $this->nome .'"] : NULL;';
            case "int":
                return '$this->'. $this->nome .' = isset($data["'. $this->nome .'"]) && $data["'. $this->nome .'"] != "" ? (int) $data["'. $this->nome .'"] : NULL;';
            case "boolean": 
                return '$this->'. $this->nome .' = isset($data["'. $this->nome .'"]) ? ( strtolower($data["'. $this->nome .'"]) == "false" || $data["'. $this->nome .'"] == "0" ? FALSE : (boolean)$data["'. $this->nome .'"]) : NULL;';
            /*case "date":
                return '$this->'. $this->nome .' = isset($data["'. $this->nome .'"]) && $data["'. $this->nome .'"] != "" ? $data["'. $this->nome .'"] : NULL;';
            case "time":
                return '$this->'. $this->nome .' = isset($data["'. $this->nome .'"]) && $data["'. $this->nome .'"] != "" ? $data["'. $this->nome .'"] : NULL;';*/
            default:
                return '$this->'. $this->nome .' = isset($data["'. $this->nome .'"]) && $data["'. $this->nome .'"] != "" ? $data["'. $this->nome .'"] : NULL;';
        }
    }
    
    public function toJs() {
        switch ($this->tipo) {
            case "number":
                return 'this.'. $this->nome .' = parseFloat(data["'. $this->nome .'"]);';
            case "int":
                return 'this.'. $this->nome .' = parseInt(data["'. $this->nome .'"]);';
            case "boolean": 
                return 'this.'. $this->nome .' = data["'. $this->nome .'"] ? (data["'. $this->nome .'"] == "false" || data["'. $this->nome .'"] == "0" ? false : true) : false;';
            case "date":
                return 'this.'. $this->nome .' = new Date(data["'. $this->nome .'"]);';
            case "time":
                return 'this.'. $this->nome .' = data["'. $this->nome .'"];';
            default:
                return 'this.'. $this->nome .' = data["'. $this->nome .'"];';
        }
    }
	
    public function toTsF() {
        switch ($this->tipo) {
            case "text":
            case "enum":
                return $this->nome .':string;';
            case "number":
            case "int":
                return $this->nome .':number;';
            case "boolean": 
                return $this->nome .':boolean;';
            case "date":
            case "time":
                return $this->nome .':Date;';
            default:
                return $this->nome .';';
        }
    }
	
    public function toTsC1() {
        switch ($this->tipo) {
            case "text":
            case "enum":
                return $this->nome .':string,';
            case "number":
            case "int":
                return $this->nome .':number,';
            case "boolean": 
                return $this->nome .':boolean,';
            case "date":
            case "time":
                return $this->nome .':Date,';
            default:
                return $this->nome .';';
        }
    }
    public function toTsC2() {
        return 'this.'. $this->nome .' = '. $this->nome .';';
    }
    public function toTsC3() {
        switch ($this->tipo) {
            case "number":
                return 'this.'. $this->nome .' = parseFloat(id["'. $this->nome .'"]);';
            case "int":
                return 'this.'. $this->nome .' = parseInt(id["'. $this->nome .'"]);';
            case "boolean": 
                return 'this.'. $this->nome .' = id["'. $this->nome .'"] ? (id["'. $this->nome .'"] == "false" || id["'. $this->nome .'"] == "0" ? false : true) : false;';
            case "date":
            case "time":
                return 'this.'. $this->nome .' = id["'. $this->nome .'"] ? new Date(id["'. $this->nome .'"] + " GMT") : null;';
            default:
                return 'this.'. $this->nome .' = id["'. $this->nome .'"];';
        }
    }
	
    public function toSql() {
        switch ($this->tipo) {
            case "text":
                return $this->nome .' TEXT' . ($this->required ? ' NOT NULL' : '');
            case "number":
                return $this->nome .' FLOAT' . ($this->required ? ' NOT NULL' : '');
            case "int":
                return $this->nome .' INT' . ($this->required ? ' NOT NULL' : '');
            case "boolean": 
                return $this->nome .' BOOLEAN' . ($this->required ? ' NOT NULL' : '');
            case "date":
                return $this->nome .' DATE' . ($this->required ? ' NOT NULL' : '');
            case "time":
                return $this->nome .' TIME' . ($this->required ? ' NOT NULL' : '');
            case "enum":
                $field = $this->nome . ' ENUM(';
                if ($this->options) {
                    foreach ($this->options as $option)
                        $field .= "'" . $option . "',";
                    $field[strlen($field) -1] = ')';
                }
                else
                    $field .= ')';
                return $field . ($this->required ? ' NOT NULL' : '');
            default:
                return $this->nome .' VARCHAR(64)' . ($this->required ? ' NOT NULL' : '');
        }
    }

    public function toGeneric() {
        
    }
    
    public function toForm($disabled = FALSE) {
        $tr =   "<td>" .
                    "<select class=\"campo-tipo\" onchange=\"campoTipoChange(event);\" ". ($disabled ? " disabled" : "") .">" . 
                        "<option title=\"Para texto comum\" value=\"default\" ". ($this->tipo == 'default' ? " selected" : "") .">default</option>" . 
                        "<option title=\"Para texto longo\" value=\"text\"". ($this->tipo == 'text' ? " selected" : "") .">text</option>" . 
                        "<option value=\"int\"". ($this->tipo == 'int' ? " selected" : "") .">int</option>" . 
                        "<option value=\"number\"". ($this->tipo == 'number' ? " selected" : "") .">number</option>" . 
                        "<option value=\"boolean\"". ($this->tipo == 'boolean' ? " selected" : "") .">boolean</option>" . 
                        "<option value=\"date\"". ($this->tipo == 'date' ? " selected" : "") .">date</option>" . 
                        "<option value=\"time\"". ($this->tipo == 'time' ? " selected" : "") .">time</option>" . 
                        "<option value=\"enum\"". ($this->tipo == 'enum' ? " selected" : "") .">enum</option>" . 
                    "</select>" .
                "</td>" .
                "<td><input type=\"text\" class=\"campo-nome\" value=\"". $this->nome ."\" required". ($disabled ? " disabled" : "") ." /></td>" .
                "<td style=\"text-align: center; vertical-align: middle;\"><input type=\"checkbox\" class=\"campo-required\"" . ($this->required ? " checked" : "") . "". ($disabled ? " disabled" : "") ." /></td>" .
                "<td></td>" .
                "<td><button". ($disabled ? " disabled" : "") ." onclick=\"this.parentElement.parentElement.remove()\">X</button></td>";
        return $tr;
    }
	
	public function toEnumForm($disabled = FALSE) {
        $table =   "<table>" .
					"<tbody>" .
						"<tr>" .
							"<td><input type=\"text\" class=\"campo-options\" value=\"\"". ($disabled ? " disabled" : "") ." /></td>" .
							"<td><button". ($disabled ? " disabled" : "") ." onclick=\"campoEnumTrAcaoBotaoAdd(event, this);\">+</button></td>" .
						"</tr>" . 
					"</tbody>" .
				"</table>";
        return $table;
    }
	public function toEnumTrForm($disabled = FALSE) {
        $tr =   "<td><input type=\"text\" class=\"campo-options\" value=\"\" required". ($disabled ? " disabled" : "") ." /></td>" .
				"<td><button". ($disabled ? " disabled" : "") ." onclick=\"campoEnumTrAcaoBotaoRemove(event,this);\">X</button></td>";
        return $tr;
    }
}