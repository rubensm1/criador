<?php 
    //Configuração do banco
    include_once 'DataBaseConfig.php';
    // Classe de Concexão com o banco 
    include_once 'Connexao.php';
    
    if (isset ($_GET['install']) ) {
        try { 
            Connexao::createDataBase();
        }
        catch (Exception $e) {
            echo '<table class="xdebug-error" dir="ltr" border="1" cellspacing="0" cellpadding="1">';
            echo $e->xdebug_message;
            echo "</table>";
            exit;
        }
        header("Location:index.php");
    }
    
    if ( !isset($_POST['ajax']) || strtolower($_POST['ajax']) == "false" || !$_POST['ajax']) {
?>
<!DOCTYPE HTML>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Criador</title>
        <style type="text/css">
            input[type='checkbox'] {
                width: 20px;
                height: 20px;
                margin: 0px;
                margin-top: 1px;
                vertical-align: bottom;
            }
            .table-bordered td, .table-bordered th {
                border: solid 1px #BBB;
            }
        </style>
    </head>
<?php    
    }

    // Abstract Model 
    include_once 'Model.php';

    include_once 'Funcao.php';
    include_once 'Comando.php';
    include_once 'Arquivo.php';
    include_once 'Campo.php';
    
    Model::init();

    function escreverArquivo($nomeArquivo, $conteudo) {
        $file = fopen($nomeArquivo, 'w');
        if (!$file) 
            throw new Exception ("Falha ao abrir o arquivo $nomeArquivo");
        if (fwrite($file, $conteudo) === FALSE) 
            throw new Exception("Falha ao escrever no arquivo $nomeArquivo");
        if (!fclose($file))
            throw new Exception ("Falha ao fechar o arquivo $nomeArquivo");
    }

    if (isset ($_GET['c'])) {
        switch ($_GET['c']) {
            case "funcao":
                if (isset ($_POST['nome'])) {
                    $funcao = new Funcao ($_POST);
                    $funcao = $funcao->persist();
                    var_dump($funcao);
                }
                else
                    echo "<br />Falha na Inserção, faltam atributos<br />";
                break;
            case "f":
                echo Funcao::htmlTable();
                break;
            case "comando":
                if ( isset ($_POST['ordem']) && isset ($_POST['funcao_id'])  && isset ($_POST['comando'])) {
                    $comando = new Comando ($_POST);
                    $comando = $comando->persist();
                    var_dump($comando);
                }
                else
                    echo "<br />Falha na Inserção, faltam atributos<br />";
                break;
            case "c":
                echo Comando::htmlTable();
                break;
            case "arquivo":
                if (isset ($_POST['nome']) && isset ($_POST['conteudo']) && isset ($_POST['funcao_id'])) {
                    $arquivo = new Arquivo ($_POST);
                    $arquivo = $arquivo->persist();
                    var_dump($arquivo);
                }
                else
                    echo "<br />Falha na Inserção, faltam atributos<br />";
                break;
            
            case "a":
                echo Arquivo::htmlTable();
                break;
            case "criar":
                if (isset ($_POST['classe']) && isset ($_POST['funcao_id'])  ) {
                    $comandos = Comando::loadList(array('funcao_id'=>$_POST['funcao_id']), 'ordem');
                    $arquivos = Arquivo::loadList(array('funcao_id'=>$_POST['funcao_id']));
                    $campos = array();
                    if (isset ($_POST['campos']))
                        foreach (json_decode($_POST['campos']) as $cp)
                            $campos[] = new Campo((array)$cp);
                    foreach ($arquivos as $arquivo){
                        $arquivo->aplicarNomeClasse($_POST['classe']); 
                        $arquivo->aplicarCampos($campos);
                        //echo "<textarea>".$arquivo->getConteudo()."</textarea><br />";
                        $arquivosMap[$arquivo->getNome()] = $arquivo;
                    }
                    foreach ($comandos as $comando){
                        $comando->aplicarNomeClasse($_POST['classe']); 
                        //echo "<pre>".$comando->getComando()."</pre>";
                        //eval ($comando->getComando());
                    }
                }
                else 
                    echo "<br />Falha na Criação, Classe não definida<br />";
                break;
            case "sql":
                if (isset ($_POST['classe']) ) {
                    $table = 'CREATE TABLE '.ucfirst(strtolower($_POST['classe']))." (\n";
                    $table .= "    id INT PRIMARY KEY AUTO_INCREMENT,\n";
                    if (isset ($_POST['campos']))
                        foreach (json_decode($_POST['campos']) as $cp)
                            $table .= "    " . (new Campo((array)$cp))->toSql() . ",\n";
                    $table[strlen($table) -2] = "\n";
                    $table[strlen($table) -1] = ")";
                    echo "<pre>".$table."</pre>";
                }
                else 
                    echo "<br />Falha na geração, Classe não definida<br />";
                break;
            case "getSQL":
                if (isset ($_POST['table']) && $_POST['table'] != '' ) {
                    echo json_encode(Connexao::getEstruturaTableDatabase(
                        $_POST['table'],
                        (isset ($_POST['database']) && $_POST['database'] != '' ? $_POST['database'] : DataBaseConfig::BANCO ),
                        (isset ($_POST['host']) && $_POST['host'] != '' ? $_POST['host'] : DataBaseConfig::HOST ),
                        (isset ($_POST['port']) && $_POST['port'] != '' ? $_POST['port'] : DataBaseConfig::PORT ),
                        (isset ($_POST['user']) && $_POST['user'] != '' ? $_POST['user'] : DataBaseConfig::USER ),
                        (isset ($_POST['pass']) ? $_POST['pass'] : DataBaseConfig::PASS )
                    ));
                }
                else 
                    echo "<br />Falha na geração, Classe não definida<br />";
                break;
            case "getCampos":
                $campos = array();
                if (isset ($_POST['campos']))
                    foreach (json_decode($_POST['campos']) as $cp) {
                        if ( strtolower($cp->nome) == 'id')
                            $campos[] = (new Campo((array)$cp))->toForm(true);
                        else
                            $campos[] = (new Campo((array)$cp))->toForm();
                    }
                echo json_encode($campos);
                break;
            case "main":
                    ?>
                    <body>
                        <table style="text-align: center; margin: auto; ">
                            <tr>
                                <td>
                                    <form action="index.php?c=submain&p=funcao" method="POST" target="paineis"><input type="submit" value="Função" /></form>
                                </td>
                                <td>
                                    <form action="index.php?c=submain&p=comando" method="POST" target="paineis"><input type="submit" value="Comando" /></form>
                                </td>
                                <td>
                                    <form action="index.php?c=submain&p=arquivo" method="POST" target="paineis"><input type="submit" value="Arquivo" /></form>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <form action="index.php?c=submain&p=criar" method="POST" target="paineis"><input style="width: 100%;" type="submit" value="Criar"></form>
                                </td>
                            </tr>
                        </table>
                    </body>
                    <?php
                    break;
                case "submain":
                    ?>
                    <body>
                        <?php if (isset($_GET['p'])) { 
                            switch ($_GET['p']) {
                                case "funcao":
                                case "Funcao":
                                    $funcao = new Funcao();
                                    if (isset ($_POST['id']))
                                        $funcao = Funcao::load( (int) $_POST['id'] );
                        ?>
                                    <div id="funcao-form">
                                        <b>Função:</b>
                                        <form action="index.php?c=funcao" method="POST" target="conteudos"> 
                                            ID:  <input name="id" type="number" <?php if ($funcao->getId()) echo 'value="'.$funcao->getId().'" readonly' ; else echo "disabled"; ?>  /><!--input class="id-check" type="checkbox" /-->
                                            <br />
                                            Nome: <input name="nome" value="<?php echo $funcao->getNome(); ?>" required /> 
                                            <br />
                                            <input type="submit" value="Submit" />
                                        </form>
                                        <br />
                                        <form action="index.php?c=f" method="POST" target="conteudos">
                                            <input type="submit" value="Pesquisa" />
                                        </form>
                                    </div>
                        <?php 
                                    break;
                                case "comando":
                                case "Comando":
                                    $comando = new Comando();
                                    if (isset ($_POST['id']))
                                        $comando = Comando::load( (int) $_POST['id'] );
                        ?>
                                    <div id="comando-form">
                                        <b>Comando:</b>
                                        <form action="index.php?c=comando" method="POST" target="conteudos"> 
                                            ID:  <input name="id" type="number" <?php if ($comando->getId()) echo 'value="'.$comando->getId().'" readonly' ; else echo "disabled"; ?> /><!--input class="id-check" type="checkbox" /-->
                                            <br />
                                            Ordem: <input name="ordem" type="number" value="<?php echo $comando->getOrdem(); ?>" required />
                                            <br />
                                            Função ID: <input name="funcao_id" type="number" value="<?php echo $comando->getFuncaoId(); ?>" required />
                                            <br />
                                            <input type="submit" value="Submit" />
                                            <br />
                                            Comando:
                                            <br />
                                            <textarea name="comando" style="width: 100%; height: 150px;"><?php echo $comando->getComando(); ?></textarea>
                                        </form>
                                        <br />
                                        <form action="index.php?c=c" method="POST" target="conteudos">
                                            <input type="submit" value="Pesquisa" />
                                        </form>
                                    </div>
                        <?php 
                                    break;
                                case "arquivo":
                                case "Arquivo":
                                    $arquivo = new Arquivo();
                                    if (isset ($_POST['id']))
                                        $arquivo = Arquivo::load( (int) $_POST['id'] );
                        ?>
                                    <div id="arquivo-form">
                                        <b>Arquivo:</b>
                                        <form action="index.php?c=arquivo" method="POST" target="conteudos"> 
                                            ID:  <input name="id" type="number" <?php if ($arquivo->getId()) echo 'value="'.$arquivo->getId().'" readonly' ; else echo "disabled"; ?> /> <!--input class="id-check" type="checkbox" /-->
                                            <br />
                                            Nome: <input name="nome" value="<?php echo $arquivo->getNome(); ?>" /> 
                                            <br />
                                            Função ID:<input name="funcao_id" type="number" value="<?php  echo $arquivo->getFuncaoId(); ?>" required />
                                            <br />
                                            <input type="submit" value="Submit" />
                                            <br />
                                            Conteudo: 
                                            <br />
                                            <textarea name="conteudo" style="width: 100%; height: 150px;"><?php echo $arquivo->getConteudo(); ?></textarea>
                                        </form>
                                        <br />
                                        <form action="index.php?c=a" method="POST" target="conteudos">
                                            <input type="submit" value="Pesquisa" />
                                        </form>
                                    </div>
                        <?php 
                                    break;
                                case "criar":
                        ?>
                                    <div>
                                        <b>Criar:</b>
                                        <form id="form-criar" action="index.php?c=criar" method="POST" target="conteudos"> 
                                            Função: 
                                            <select name="funcao_id">
                                                <?php
                                                    foreach (Funcao::all() as $funcao)
                                                        echo "<option value=\"".$funcao->getId()."\">".$funcao->getNome()."</option>";
                                                ?>
                                            </select>
                                            <br />
                                            Classe: <input name="classe" required /> 
                                            <br />
                                            <input id="campos-hidden" type="hidden" name="campos" />
                                        </form>
                                        <br />
                                    </div>
                                    <div>
                                        <button onclick="var tr = document.createElement('tr');tr.innerHTML = campoTr; tr.className = 'campo-tr'; document.getElementById('form-edit-campos').getElementsByTagName('tbody')[0].appendChild(tr);">Novo Campo</button>
                                        <input type="submit" form="form-edit-campos" onclick="btSub = 'UP'" value="Upar" />
                                        <input type="submit" form="form-edit-campos" onclick="btSub = 'SQL'" value="SQL" />
                                        <button onclick="if ( document.getElementById('load-table').style.display == 'none' ) { document.getElementById('load-table').style.display = 'block'; document.getElementById('form-edit-campos').style.display = 'none';} else { document.getElementById('load-table').style.display = 'none'; document.getElementById('form-edit-campos').style.display = 'block'}">Load</button>
                                        <form id="form-edit-campos">
                                            <table class="table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Tipo</th><th>Nome</th><th>Required</th><th>Options</th><th>X</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo Campo::getCampoId()->toForm(true); ?>
                                                </tbody>
                                            </table>
                                        </form>
                                        <form id="load-table" style="display: none;">
                                            <table class="table-bordered">
                                                <tbody>
                                                    <tr><td>Table:</td><td><input name="table" type="text" required /></td></tr>
                                                    <tr><td>Database:</td><td><input name="database" type="text" required value="<?php echo DataBaseConfig::BANCO ; ?>" /></td></tr>
                                                    <tr><td>Host:</td><td><input name="host" type="text" required value="<?php echo DataBaseConfig::HOST ; ?>" /></td></tr>
                                                    <tr><td>Port:</td><td><input name="port" type="text" required value="<?php echo DataBaseConfig::PORT ; ?>" /></td></tr>
                                                    <tr><td>User:</td><td><input name="user" type="text" required value="<?php echo DataBaseConfig::USER ; ?>" /></td></tr>
                                                    <tr><td>Password:</td><td><input name="pass" type="password" value="<?php echo DataBaseConfig::PASS ; ?>" /></td></tr>
                                                </tbody>
                                            </table>
                                            <input id="campos-hidden-load" type="hidden" name="campos" />
                                            <input type="submit" value="Load" />
                                        </form>
                                    </div>
                                    <script>
                                        var btSub = null;
                                        var Campo;
                                        var Desc;
                                        Campo = (function (){
                                            function Campo(tipo, nome, required, options) {
                                                this.tipo = tipo;
                                                this.nome = nome;
                                                this.required = required;
                                                this.options = options;
                                            }
                                            Campo.extract = function (tr) {
                                                var tipo = tr.getElementsByClassName("campo-tipo")[0].value;
												var options = null;
												if (tipo == 'enum') {
													options = [];
													tdOp = tr.getElementsByClassName("campo-options");
													for (var i = 0; i < tdOp.length; i++)
														options.push(tdOp[i].value);
												}
												return new Campo(
                                                    tipo,
                                                    tr.getElementsByClassName("campo-nome")[0].value,
                                                    tr.getElementsByClassName("campo-required")[0].checked,
													options
                                                );
                                            };
                                            Campo.extracts = function (trs) {
                                                var campos = [];
                                                for (var i = 0; i < trs.length; i++)
                                                    campos.push(Campo.extract(trs[i]));
                                                return campos;
                                            };
                                            Campo.convert = function (descs) {
                                                var campos = [];
                                                for (var i = 0; i < descs.length; i++){
                                                    var tipo;
                                                    switch (descs[i].type.toUpperCase()) {
                                                        case 'TINYTEXT': 
                                                        case 'TEXT': 
                                                        case 'MEDIUMTEXT': 
                                                        case 'LONGTEXT':
                                                            tipo = "text";
                                                            break;
                                                        case 'DECIMAL':
                                                        case 'FLOAT':
                                                        case 'DOUBLE':
                                                        case 'REAL':
                                                        case 'NUMBER':
                                                            tipo = "number";
                                                            break;
                                                        case 'SMALLINT':
                                                        case 'MEDIUMINT':
                                                        case 'INT':
                                                        case 'BIGINT':
                                                        case 'SERIAL':
                                                            tipo = "int";
                                                            break;
                                                        case 'TINYINT':
                                                        case 'BIT':
                                                        case 'BOOLEAN':
                                                            tipo = "boolean";
                                                            break;
                                                        case 'DATE':
                                                        case 'YEAR':
                                                            tipo = "date";
                                                            break;
                                                        case 'TIME':
                                                            tipo = "time";
                                                            break;
                                                        //case 'DATETIME':
                                                        //case 'TIMESTAMP':
                                                        //    tipo = "datetime";
                                                        case 'ENUM':
                                                        case 'SET':
                                                            tipo = "enum";
                                                            break;
                                                        //case 'CHAR':
                                                        //case 'VARCHAR':
                                                        //case 'BINARY':
                                                        //case 'VARBINARY':
                                                        default:
                                                            tipo = "default";
                                                        
                                                        //TINYBLOB
                                                        //MEDIUMBLOB
                                                        //BLOB
                                                        //LONGBLOB
                                                        //GEOMETRY
                                                        //POINT
                                                        //LINESTRING
                                                        //POLYGON
                                                        //MULTIPOINT
                                                        //MULTILINESTRING
                                                        //MULTIPOLYGON
                                                        //GEOMETRYCOLLECTION
                                                        
                                                    }
                                                    campos.push(new Campo(tipo, descs[i].field, !descs[i].null, descs[i].typeProps));
                                                }
                                                return campos;
                                            };
                                            return Campo;
                                        })();
                                        
                                        Desc = (function (){
                                            function Desc(data) {
                                                this.field = data["Field"];
                                                var indexProps = data["Type"].indexOf('(');
                                                if (indexProps < 0) {
                                                    this.type = data["Type"];
                                                    this.typeProps = null;
                                                }
                                                else {
                                                    this.type = data["Type"].slice(0,indexProps);
                                                    this.typeProps = data["Type"].indexOf(',') < 0 ? [ eval(data["Type"].slice(indexProps)) ] : ( eval("Array" + data["Type"].slice(indexProps)) );
                                                }
                                                this.null = data["Null"] == "YES" ? true : (data["Null"] == "NO" ? false : null);
                                                this.key = data["Key"];
                                                this.default = data["Default"];
                                                this.extra = data["Extra"];
                                            }
                                            Desc.extract = function (datas) {
                                                var descs = [];
                                                for (var i = 0; i < datas.length; i++)
                                                    descs.push(new Desc (datas[i]) );
                                                return descs;
                                            };
                                            return Desc;
                                        })();
                                        
										function campoEnumCarregaOptions(td, values) {
											td.innerHTML = campoEnumTr;
											if (!values)
												return;
											var tbody = td.getElementsByTagName('tbody')[0];
											tbody.getElementsByClassName("campo-options")[0].value = values[0];
											for (var i = 1; i < values.length; i++) {
												var tr = document.createElement('tr');
												tr.innerHTML = '<?php echo Campo::getCampoDefault()->toEnumTrForm(); ?>'; 
												tr.className = 'campo-enum-tr'; 
												tr.getElementsByClassName("campo-options")[0].value = values[i];
												tbody.appendChild(tr);
											}
										}
										function campoEnumTrAcaoBotaoAdd(event, botao) {
											event.preventDefault(); 
											var tr = document.createElement('tr');
											tr.innerHTML = '<?php echo Campo::getCampoDefault()->toEnumTrForm(); ?>'; 
											tr.className = 'campo-enum-tr'; 
											botao.parentElement.parentElement.parentElement.appendChild(tr);
										}
										function campoEnumTrAcaoBotaoRemove(event, botao) {
											event.preventDefault(); 
											botao.parentElement.parentElement.remove()
										}
										function campoTipoChange(event) {
											if(event.target.value == "enum")
												event.target.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.innerHTML = campoEnumTr;
											else
												event.target.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.innerHTML = '';
										}
                                        var campoTr = '<?php echo Campo::getCampoDefault()->toForm(); ?>';
										var campoEnumTr = '<?php echo Campo::getCampoDefault()->toEnumForm(); ?>';
                                        
                                        document.getElementById("form-edit-campos").onsubmit = function(ev){
                                            ev.preventDefault();
                                            if (this.reportValidity()) {
                                                var campos = Campo.extracts(this.getElementsByClassName('campo-tr'));
                                                switch (btSub) {
                                                    case "UP":
                                                        console.log ( JSON.stringify( campos ) ) ;
                                                        document.getElementById("campos-hidden").value = JSON.stringify(campos);
                                                        var formCriar = document.getElementById("form-criar");
                                                        formCriar.setAttribute("action","index.php?c=criar");
                                                        if (formCriar.reportValidity())
                                                            formCriar.submit();
                                                        break;
                                                    case "SQL":
                                                        document.getElementById("campos-hidden").value = JSON.stringify(campos);
                                                        var formCriar = document.getElementById("form-criar");
                                                        formCriar.setAttribute("action","index.php?c=sql");
                                                        if (formCriar.reportValidity())
                                                            formCriar.submit();
                                                        break;
                                                }
                                            }
                                        };
                                        document.getElementById("load-table").onsubmit = function(ev){
                                            ev.preventDefault();
                                            if (this.reportValidity()) {
                                                var xhr = new XMLHttpRequest();
                                                //var formCriar = document.getElementById("form-criar");
                                                //if (!formCriar.reportValidity())
                                                    //return;
                                                var data = new FormData(this);
                                                data.append('ajax',true);
                                                xhr.open('POST', 'index.php?c=getSQL', false);
                                                //xhr.onload = function () {};
                                                xhr.send(data);
                                                var campos = Campo.convert(Desc.extract(JSON.parse(xhr.responseText)));
                                                document.getElementById("campos-hidden-load").value = JSON.stringify(campos);
                                                data = new FormData(this);
                                                data.append('ajax',true);
                                                xhr.open('POST', 'index.php?c=getCampos', false);
                                                xhr.send(data);
                                                
                                                var trHTMLs = JSON.parse(xhr.responseText);
                                                
                                                var tbody = document.getElementById('form-edit-campos').getElementsByTagName('tbody')[0];
                                                tbody.innerHTML = "";
                                                for (var i = 0; i < trHTMLs.length; i++) {
                                                    var tr = document.createElement('tr');
                                                    tr.innerHTML = trHTMLs[i];
                                                    tr.className = 'campo-tr';
													if(campos[i].tipo == "enum"){
														campoEnumCarregaOptions(tr.childNodes[3],campos[i].options);
													}
                                                    tbody.appendChild(tr);
                                                }
                                                document.getElementById('load-table').style.display = 'none'; 
                                                document.getElementById('form-edit-campos').style.display = 'block';
                                            }
                                        };
                                    </script>
                        <?php 
                                    break;
                            }
                        }
                        ?>
                    </body>
                    <?php
                break;
        }
    }
    else {
        ?>
        <frameset title="Criador" cols="20%,80%" >
            <frameset rows="80,*">
                <frame name="principal" src="index.php?c=main">
                <frame name="paineis" src="index.php?c=submain" >
            </frameset>
            <frame name="conteudos" src="index.php?c=submain">
        </frameset>
        <?php
    }
 
    if ( !isset($_POST['ajax']) || strtolower($_POST['ajax']) == "false" || !$_POST['ajax']) 
        echo "</html>";
    ?>
