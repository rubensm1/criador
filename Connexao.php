<?php

/*
 * Classe de Conexão - Reponsavel por todos os acessos ao banco.
 */

/*
 * pegando as configurações do banco
 */

class Connexao {

    private $conexao;
    private $resultado;

    function __construct($database = DataBaseConfig::BANCO, $host = DataBaseConfig::HOST, $port = DataBaseConfig::PORT, $user = DataBaseConfig::USER, $pass = DataBaseConfig::PASS) {
        try {
            if ($pass == '')
                $this->conexao = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database, $user);
            else
                $this->conexao = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database, $user, $pass);
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //$this->conexao->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        catch (Exception $e) {
            echo "<h1 style='text-align: center;'>Erro de Conexão com o banco de dados!</h1>
            <br />
            <h2 style='text-align: center;'>Talvez o banco de dados não está instalado, Tente adicionar ao hiperlink: '/index.php?install'</h2>";
        }
    }

    public static function getEstruturaTableDatabase($table, $database = DataBaseConfig::BANCO, $host = DataBaseConfig::HOST, $port = DataBaseConfig::PORT, $user = DataBaseConfig::USER, $pass = DataBaseConfig::PASS) {
        $conn = new Connexao ($database, $host, $port, $user, $pass);
        $conn->query("DESC $table;");
        $conn->execute();
        $ret = $conn->fetchObjAll();
        $conn->close();
        return $ret;
    }
    
    /**
     * <b>Methodo</b>
     * Cria o banco de dados
     */
    public static function createDataBase($database = DataBaseConfig::BANCO, $host = DataBaseConfig::HOST, $port = DataBaseConfig::PORT, $user = DataBaseConfig::USER, $pass = DataBaseConfig::PASS) {
    
        $link = mysql_connect($host . ":" . $port, $user, $pass);
        if (!$link || !mysql_ping($link)) 
            throw new Exception("Falha na conexão com o banco de dados. <br/> Certifique-se de que o usuário e senha estão corretos. <br/> Verifique as permissões do usuário do banco.");
        
        self::mysql_install_db($database, 'criador.sql');
    }
    
    /**
     * Cria o banco de dados
     * @param String $dbname Nome do banco de dados
     * @param String $dbsqlfile Caminho para o arquivo <b>".sql"</b> com a estrutura do banco
     */
    private static function mysql_install_db($dbname, $dbsqlfile) {
    
        //if (!mysql_select_db($dbname)) { /*Verifica se o banco ja existe*/
        if (!mysql_query("DROP DATABASE IF EXISTS $dbname")) /* Exclui o banco caso já exista */
            throw new Exception("Falha ao remover o banco  de dados[$dbname].");

        if (!mysql_query("CREATE DATABASE $dbname"))  /* Cria o banco */
            throw new Exception("Não foi possivel criar o banco  de dados[$dbname]. <br/> Verifique as permissões do usuário do banco.");

        if (!mysql_select_db($dbname)) /* Seleciona o banco */
            throw new Exception( "Não foi possivel selecionar o banco de dados [$dbname]");

        return self::mysql_import_file($dbsqlfile);
    }
        
    /**
     * Faz a importação da estrutura do banco de dados
     * @param String $filename Nome do arquivo de importação
     * @param *String $errmsg Variavel que recebe a mensagem de erro 
     */
    private static function mysql_import_file($filename) {
        /* Le o arquivo */
        $lines = file($filename);

        if (!$lines) 
            throw new Exception ("Não foi possivel encontrar o arquivo $filename");
            
        $scriptfile = false;

        /* Retira os comentários e cria uma unica linha */
        foreach ($lines as $line) {
            $line = trim($line);
            if (strlen($line) > 0 && !preg_match('/^--/', $line)) {
                if ($line [strlen($line) -1] == ";")
                    $scriptfile.= $line . "$@@@";
                else 
                    $scriptfile.= $line . " ";
            }
        }

        if (!$scriptfile) 
            throw new Exception("Arquivo invalido $filename");

        /* divide a linha em comandos menores */
        $queries = explode('$@@@', $scriptfile);
        
        /* Executa todos os comandos sqls */
        foreach ($queries as $query) {
            $query = trim($query);
            if ($query == "") 
                continue;
            if (!mysql_query($query . ';')) 
                throw new Exception("query " . $query . " falhou");
        }
    }
    
    /**
     * Prepara a query SQL
     * @param String $sql Query Sql a ser preparada
     */
    public function query($sql) {
        $this->resultado = $this->conexao->prepare($sql);
    }

    /**
     * Executa a query com os dados
     * @param Array $dados Dados necessarios para execução da query.<br/>
     * Pode ser Nulo ou vazio.
     */
    public function execute($dados = NULL) {
        $this->resultado->execute($dados);
    }

    /**
     * Pega uma linha do resultado da consulta sql e retorna como objeto.
     * @return Object obj O objeto de uma linha da consulta.
     */
    public function fetchObj() {
        return $this->resultado->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Retorna uma linha do resultado da consulta.
     * @return Array uma linha do resultado da consulta
     */
    public function fetch() {
        return $this->resultado->fetch();
    }

    /**
     * Retorna todo o resultado da consulta em um Array
     * @return Array Todas a linhas resultantes da consulta.
     */
    public function fetchAll() {
        return $this->resultado->fetchAll();
    }

    /**
     * Retorna todo o resultado da consulta em um Array de Objetos
     * @return Array Todas a linhas resultantes da consulta em forma de Objetos.
     */
    public function fetchObjAll() {
        return $this->resultado->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Prepara a query sql, executa e retorna o dado
     * @param String $sql Consulta SQL.
     * @param Array $dados dados necessários para execução da query.
     * @return Array uma linha do resultado da consulta.
     */
    public function executeQuery($sql, $dados = array()) {
        $this->query($sql);
        $this->execute($dados);
        return $this->fetch();
    }

    /**
     * Prepara a query sql, executa e retorna os dados
     * @param String $sql Consulta SQL.
     * @param Array $dados dados necessários para execução da query.
     * @return Array Todas a linhas resultantes da consulta.
     */
    public function executeQueryAll($sql, $dados = array()) {
        $this->query($sql);
        $this->execute($dados);
        return $this->fetchAll();
    }
    
    /**
     * Prepara a query sql, executa e retorna os dados
     * @param String $sql Consulta SQL.
     * @param Array $dados dados necessários para execução da query.
     * @return Array Todas a linhas resultantes da consulta.
     */
    public function executeQueryObjectAll($sql, $dados = array()) {
        $this->query($sql);
        $this->execute($dados);
        return $this->fetchObjAll();
    }

    public function getColunasTable($table) {
        $this->query("DESC $table;");
        $this->execute();
        return $this->fetchAll();
    }

    /**
     * Fecha a conexão.
     */
    public function close() {
        $this->conexao = NULL;
    }

}
