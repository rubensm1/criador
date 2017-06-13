-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 
-- Versão do Servidor: 5.5.24-log
-- Versão do PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de Dados: `criador`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `arquivo`
--

CREATE TABLE IF NOT EXISTS `arquivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(32) NOT NULL,
  `funcao_id` int(11) NOT NULL,
  `conteudo` text,
  PRIMARY KEY (`id`),
  KEY `funcao_id` (`funcao_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Extraindo dados da tabela `arquivo`
--

INSERT INTO `arquivo` (`id`, `nome`, `funcao_id`, `conteudo`) VALUES
(2, 'a@@@.js', 1, 'var A@@@;\r\n\r\nA@@@ = (function() {\r\n\r\n    //A@@@.prototype = new View();\r\n\r\n    function A@@@(data) {\r\n        //extende(this, View);\r\n        if (typeof data != "object")\r\n            return;\r\n        this.id = data["id"];\r\n        this.nome = data["nome"];\r\n    }\r\n\r\n    A@@@.prototype.formatar = View.prototype.formatar;\r\n    \r\n    A@@@.prototype.htmlTable = View.prototype.htmlTable;\r\n    \r\n    return A@@@;\r\n})();'),
(3, 'index.php', 1, '<div class="panel panel-jquery">\r\n    <div class="panel-heading">A@@@</div>\r\n    <div id="div-a@@@-table" class="div-model-table" class="panel-body">\r\n        \r\n    </div>\r\n</div>\r\n<button type="button" onclick="atualizaA@@@Table()">Atualizar</button>\r\n<button id="bt-cadastro" type="button" onclick="$(''#a@@@-form'').dialog(''open'');">Cadastro</button>\r\n<form id="a@@@-form" class="model-form" method="POST" style="display:none;">\r\n    <table class="table table-bordered">\r\n    <tr><td>Nome:</td><td><input type="text" name="nome"/></td></tr>\r\n    </table>\r\n</form>\r\n\r\n<script>\r\n    //var a@@@ = new A@@@();\r\n    function atualizaA@@@Table(a@@@s) {\r\n        if (a@@@s == null || (Array.isArray(a@@@s) && a@@@s.length == 0)) {\r\n            A@@@.lista = a@@@s = view.carregarLista(JSON.parse(ajaxPadrao("a@@@", "a@@@Table", null)), A@@@.name);\r\n        }\r\n        $("#div-a@@@-table").html(new A@@@().htmlTable(a@@@s));\r\n        $(".bt-select-item" ).button({icons: {primary: "ui-icon-check"}, text: false});\r\n    }\r\n    A@@@.lista = view.carregarLista(<?php if (isset($a@@@List)) echo $a@@@List; else echo "null"; ?>, A@@@.name);\r\n    atualizaA@@@Table(A@@@.lista);\r\n\r\n    $("#a@@@-form").dialog({\r\n        title: "Cadastro de A@@@s",\r\n        width: 600,\r\n        height: 180,\r\n        autoOpen: false,\r\n        buttons: [\r\n            {text: "Salvar", width: 100, click: function () {\r\n                echo(ajaxPadrao("a@@@", "salvar", $(this).serializeObject()));\r\n            }},\r\n            {text: "Limpar", width: 100, click: function () {\r\n                $(this)[0].reset();\r\n            }},\r\n            {text: "Fechar", width: 100, click: function () {\r\n                $(this).dialog("close");\r\n            }}\r\n        ]\r\n    });\r\n    $("button").button();\r\n</script>'),
(4, 'A@@@.php', 1, '<?php\r\n\r\n/**\r\n * Classe A@@@\r\n */\r\nclass A@@@ extends Model {\r\n\r\n    protected static $useTable = "a@@@";\r\n    \r\n    private $nome;\r\n\r\n    function A@@@($data = NULL) {\r\n        if ($data != NULL) {\r\n            parent::__construct(isset($data[''id'']) ? (int) $data[''id''] : NULL);\r\n            $this->nome = isset($data[''nome'']) ? utf8_encode($data[''nome'']) : NULL;\r\n        } else\r\n            parent::__construct();\r\n    }\r\n\r\n    public function toArray() {\r\n        return get_object_vars($this);\r\n    }\r\n\r\n}'),
(5, 'A@@@Controller.php', 1, '<?php\r\n\r\n/**\r\n * Controller do A@@@\r\n */\r\nclass A@@@Controller extends Controller {\r\n\r\n    var $name = ''a@@@'';\r\n\r\n    public function index() {\r\n        $this->set("a@@@List", json_encode(A@@@::all()));\r\n    }\r\n\r\n    public function salvar($dados) {\r\n        $a@@@ = new A@@@($dados);\r\n        $a@@@ = $a@@@->persist();\r\n        if ($a@@@)\r\n            return $a@@@->getId();\r\n    }\r\n\r\n    public function carregar($dados) {\r\n        return json_encode(A@@@::load($dados[''id'']));\r\n    }\r\n\r\n    public function excluir($dados) {\r\n        a@@@ = new A@@@($dados);\r\n        return json_encode(a@@@->delete());\r\n    }\r\n\r\n    public function a@@@Table() {\r\n        return json_encode(A@@@::all());\r\n    }\r\n\r\n    public function table() {\r\n        $this->set("a@@@List", A@@@::allMap());\r\n    }\r\n\r\n}'),
(6, 'a@@@.js', 2, 'var A@@@;\r\n\r\nA@@@ = (function() {\r\n\r\n    function A@@@(data) {\r\n        if (typeof data != "object")\r\n            return;\r\n        this.id = data["id"];\r\n        j@@@\r\n    }\r\n\r\n    A@@@.prototype.formatar = View.prototype.formatar;\r\n    \r\n    A@@@.prototype.htmlTable = View.prototype.htmlTable;\r\n    \r\n    return A@@@;\r\n})();'),
(7, 'index.php', 2, '<div class="panel panel-jquery">\r\n    <div class="panel-heading">A@@@</div>\r\n    <div id="div-a@@@-table" class="div-model-table" class="panel-body">\r\n        \r\n    </div>\r\n</div>\r\n<button type="button" onclick="atualizaA@@@Table()">Atualizar</button>\r\n<button id="bt-cadastro" type="button" onclick="$(''#a@@@-form'').dialog(''open'');">Cadastro</button>\r\n<form id="a@@@-form" class="model-form" method="POST" style="display:none;">\r\n    <table class="table table-bordered">\r\n        <tr><td>ID:</td><td><input type="number" name="id" disabled /></td></tr>\r\n        h@@@\r\n    </table>\r\n</form>\r\n\r\n<script>\r\n    //var a@@@ = new A@@@();\r\n    function atualizaA@@@Table(a@@@s) {\r\n        if (a@@@s == null || (Array.isArray(a@@@s) && a@@@s.length == 0)) {\r\n            A@@@.lista = a@@@s = view.carregarLista(JSON.parse(ajaxPadrao("a@@@", "a@@@Table", null)), A@@@.name);\r\n        }\r\n        $("#div-a@@@-table").html(new A@@@().htmlTable(a@@@s));\r\n        $(".bt-select-item" ).button({icons: {primary: "ui-icon-check"}, text: false});\r\n    }\r\n    A@@@.lista = view.carregarLista(<?php if (isset($a@@@List)) echo $a@@@List; else echo "null"; ?>, A@@@.name);\r\n    atualizaA@@@Table(A@@@.lista);\r\n\r\n    $("#a@@@-form").dialog({\r\n        title: "Cadastro de A@@@s",\r\n        width: 600,\r\n        height: 400,\r\n        autoOpen: false,\r\n        buttons: [\r\n            {text: "Salvar", width: 100, type:"submit", form: "a@@@-form", click: function(){}},\r\n            {text: "Excluir", width: 100, click: function () {ajaxPadrao("a@@@", "excluir", {id: parseInt($("#a@@@-form").find("[name=''id'']").val())} ); atualizaA@@@Table();}},\r\n            {text: "Limpar", width: 100, click: function () {$(this)[0].reset();}},\r\n            {text: "Fechar", width: 100, click: function () {$(this).dialog("close");}}\r\n        ]\r\n    });\r\n    $("#a@@@-form").submit ( function(ev){\r\n        ev.preventDefault();\r\n        if ($("#a@@@-form")[0].reportValidity()) {\r\n            var checksFalse = $(this).find(":checkbox[value=false]");\r\n            $(checksFalse).prop("checked",true);\r\n            var obj = $(this).serializeObject();\r\n            $(checksFalse).prop("checked",false);\r\n            var idImput = $(this).find("[name=''id'']");\r\n            if (idImput.val())\r\n                obj["id"] = parseInt(idImput.val());\r\n            idImput.val(parseInt (ajaxPadrao("a@@@", "salvar", obj ) ) );\r\n            atualizaA@@@Table();\r\n        }\r\n    });\r\n    $("button").button();\r\n</script>'),
(8, 'A@@@.php', 2, '<?php\r\n\r\n/**\r\n * Classe A@@@\r\n */\r\nclass A@@@ extends Model {\r\n\r\n    protected static $useTable = "a@@@";\r\n    \r\n    M@@@\r\n    function A@@@($data = NULL) {\r\n        if ($data != NULL) {\r\n            parent::__construct(isset($data[''id'']) ? (int) $data[''id''] : NULL);\r\n            m@@@\r\n        } else\r\n            parent::__construct();\r\n    }\r\n\r\n    public function toArray() {\r\n        return get_object_vars($this);\r\n    }\r\n\r\n}'),
(9, 'A@@@Controller.php', 2, '<?php\r\n\r\n/**\r\n * Controller do A@@@\r\n */\r\nclass A@@@Controller extends Controller {\r\n\r\n    var $name = ''a@@@'';\r\n\r\n    public function index() {\r\n        $this->set("a@@@List", json_encode(A@@@::all()));\r\n    }\r\n\r\n    public function salvar($dados) {\r\n        $a@@@ = new A@@@($dados);\r\n        $a@@@ = $a@@@->persist();\r\n        if ($a@@@)\r\n            return $a@@@->getId();\r\n    }\r\n\r\n    public function carregar($dados) {\r\n        return json_encode(A@@@::load($dados[''id'']));\r\n    }\r\n\r\n    public function excluir($dados) {\r\n        $a@@@ = new A@@@($dados);\r\n        return json_encode($a@@@->delete());\r\n    }\r\n\r\n    public function a@@@Table() {\r\n        return json_encode(A@@@::all());\r\n    }\r\n\r\n    public function table() {\r\n        $this->set("a@@@List", A@@@::allMap());\r\n    }\r\n\r\n}\r\n'),
(10, 'A@@@.php', 3, '<?php\r\n\r\n/**\r\n * Classe A@@@\r\n */\r\nclass A@@@ extends Model {\r\n\r\n    protected static $useTable = "a@@@";\r\n    \r\n    M@@@\r\n    function A@@@($data = NULL) {\r\n        if ($data != NULL) {\r\n            parent::__construct(isset($data[''id'']) ? (int) $data[''id''] : NULL);\r\n            m@@@\r\n        } \r\n        else\r\n            parent::__construct();\r\n    }\r\n\r\n    public function toArray() {\r\n        return get_object_vars($this);\r\n    }\r\n\r\n}');

-- --------------------------------------------------------

--
-- Estrutura da tabela `comando`
--

CREATE TABLE IF NOT EXISTS `comando` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordem` int(11) NOT NULL,
  `funcao_id` int(11) NOT NULL,
  `comando` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `funcao_id` (`funcao_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=21 ;

--
-- Extraindo dados da tabela `comando`
--

INSERT INTO `comando` (`id`, `ordem`, `funcao_id`, `comando`) VALUES
(1, 1, 1, 'exec (''mkdir view/a@@@'');'),
(2, 2, 1, 'exec (''touch view/a@@@/a@@@.js'');'),
(3, 3, 1, 'escreverArquivo(''view/a@@@/a@@@.js'', $arquivosMap[''a@@@.js'']->getConteudo());'),
(4, 4, 1, 'exec (''touch view/a@@@/index.php'');'),
(5, 5, 1, 'escreverArquivo(''view/a@@@/index.php'', $arquivosMap[''index.php'']->getConteudo());'),
(6, 6, 1, 'exec (''touch controller/A@@@Controller.php'');'),
(7, 7, 1, 'escreverArquivo(''controller/A@@@Controller.php'', $arquivosMap[''A@@@Controller.php'']->getConteudo());'),
(8, 8, 1, 'exec (''touch model/A@@@.php'');'),
(9, 9, 1, 'escreverArquivo(''model/A@@@.php'', $arquivosMap[''A@@@.php'']->getConteudo());'),
(10, 1, 2, 'exec (''mkdir view/a@@@'');'),
(11, 2, 2, 'exec (''touch view/a@@@/a@@@.js'');'),
(12, 3, 2, 'escreverArquivo(''view/a@@@/a@@@.js'', $arquivosMap[''a@@@.js'']->getConteudo());'),
(13, 4, 2, 'exec (''touch view/a@@@/index.php'');'),
(14, 5, 2, 'escreverArquivo(''view/a@@@/index.php'', $arquivosMap[''index.php'']->getConteudo());'),
(15, 6, 2, 'exec (''touch controller/A@@@Controller.php'');'),
(16, 7, 2, 'escreverArquivo(''controller/A@@@Controller.php'', $arquivosMap[''A@@@Controller.php'']->getConteudo());'),
(17, 8, 2, 'exec (''touch model/A@@@.php'');'),
(18, 9, 2, 'escreverArquivo(''model/A@@@.php'', $arquivosMap[''A@@@.php'']->getConteudo());'),
(19, 1, 3, 'exec (''touch model/A@@@.php'');'),
(20, 2, 3, 'escreverArquivo(''model/A@@@.php'', $arquivosMap[''A@@@.php'']->getConteudo());');

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcao`
--

CREATE TABLE IF NOT EXISTS `funcao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(64) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `funcao`
--

INSERT INTO `funcao` (`id`, `nome`) VALUES
(1, 'CRUD PadrÃ£o'),
(2, 'CRUD Melhorado'),
(3, 'Model Somente');

-- --------------------------------------------------------

--
-- Estrutura da tabela `teste`
--

CREATE TABLE IF NOT EXISTS `teste` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `informacao` varchar(64) NOT NULL,
  `ordem` int(11) NOT NULL,
  `descricao` text,
  `familia` enum('Tipo 1','Tipo 2','Tipo 3') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `teste`
--

INSERT INTO `teste` (`id`, `informacao`, `ordem`, `descricao`, `familia`) VALUES
(1, 'Qualquer', 1, 'sei lÃ¡', ''),
(2, 'Qualquer outra', 1, '', 'Tipo 2'),
(3, 'Qualquer outra', 1, '', ''),
(4, '', 1, '', ''),
(5, 'Aeeee', 1, '', 'Tipo 3');

--
-- Restrições para as tabelas dumpadas
--

--
-- Restrições para a tabela `arquivo`
--
ALTER TABLE `arquivo`
  ADD CONSTRAINT `arquivo_ibfk_1` FOREIGN KEY (`funcao_id`) REFERENCES `funcao` (`id`);

--
-- Restrições para a tabela `comando`
--
ALTER TABLE `comando`
  ADD CONSTRAINT `comando_ibfk_1` FOREIGN KEY (`funcao_id`) REFERENCES `funcao` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
