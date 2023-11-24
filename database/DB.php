<?php
/**
 * Database
 * 
 * @author Tim Daniëls
 */
namespace database;

class DB {

    private $_pdo, $_stmt, $_query = "", $_data = [], $_columns = [], $_placeholders = [], $_setValues = [];
    public static $error;
    private static $_instance = null;

    /**
     * Creating connection
     * 
     * @param string $host host
     * @param string $user username
     * @param string $password password
     * @param string $db database name
     * @return void
     */
    public function __construct($host, $user, $password, $db) {

        try {
            $this->_pdo = new \PDO("mysql:host=$host;dbname=$db", $user, $password);
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Creating instance
     * 
     * @return object DB
     */    
    public static function try() {

        if(file_exists('../config/database/config.ini') ) {
            
            $ini = parse_ini_file('../config/database/config.ini');
            return self::$_instance = new DB($ini['host'], $ini['user'], $ini['password'], $ini['db']);
        } 
    }

    public static function getError() {

        return self::$error;
    }

    /**
     * Executes sql statement
     * 
     * @param string $sql query
     * @param array $data sql 
     */      
    private function execute_query() {

        $this->_stmt = $this->_pdo->prepare($this->_query);

        if(!empty($this->_data) && $this->_data !== null) {

            $this->_stmt->execute($this->_data);
            $this->_data = [];
        } else {
            $this->_stmt->execute();
        }

        return $this;
    }

    /**
     * Fetching sql statement in array
     */     
    private function fetch_query() {

        return $this->_stmt->fetchAll();
    }

    /**
     * Fetching sql statement in array but first item
     */     
    private function fetch_query_first() {

        return $this->_stmt->fetch();
    }

    /** 
     * Adding SELECT columns to query
     * 
     * @param string $columns name(s)
     * @return object DB
     */    
    public function select($colls) {
    
        $columns = implode(',', func_get_args());
        $this->_query = "SELECT $columns";

        return $this;
    }

    /** 
     * Adding SELECT * FROM table to query
     * 
     * @param string $table name
     * @return object DB
     */ 
    public function all($table) {

        $this->_query = "SELECT * FROM $table";
        return $this;
    }

    /** 
     * Adding FROM table to query
     * 
     * @param string $table name
     * @return object DB
     */ 
    public function from($table) {

        $this->_query .= " FROM $table";
        return $this;
    }

    /** 
     * Adding WHERE column operator to query
     * 
     * @param string $column name
     * @param string $operator value
     * @param string $value column
     * @return object DB
     */ 
    public function where($column, $operator, $value) {

        $this->_data[] = $value;
        $this->_query .= " WHERE $column $operator ?";

        return $this;
    }

    /** 
     * Adding WHERE column operator to query
     * 
     * @param string $column name
     * @param string $operator value
     * @param string $value column
     * @return object DB
     */ 
    public function whereNot($column, $operator, $value) {

        $this->_data[] = $value;
        $this->_query .= " WHERE NOT $column $operator ?";

        return $this;
    }    

    /** 
     * Adding WHERE column NOT IN values to query
     * 
     * @param string $column name
     * @param array $values column values
     * @return object DB
     */ 
    public function whereNotIn($column, $values) {

        $this->_query .= " WHERE $column NOT IN ($values)";

        return $this;
    }

    /** 
     * Adding AND column operator to query
     * 
     * @param string $column name
     * @param string $operator value
     * @param string $value column
     * @return object DB
     */     
    public function and($column, $operator, $value) {

        $this->_data[] = $value;
        $this->_query .= " AND $column $operator ?";

        return $this;
    }

    /** 
     * Adding OR column operator to query
     * 
     * @param string $column name
     * @param string $operator value
     * @param string $value column
     * @return object DB
     */     
    public function or($column, $operator, $value) {

        $this->_data[] = $value;
        $this->_query .= " OR $column $operator ?";

        return $this;
    }

    /** 
     * Adding LIMIT num operator to query
     * 
     * @param mixed int|string $num 
     * @return object DB
     */     
    public function limit($num) {

        $this->_query .= " LIMIT $num";
        return $this;
    }

    /** 
     * Adding INSERT INTO table (columns) VALUES (placeholder) to query
     * 
     * @param string $table name
     * @param array $data column names, column values
     * @return object DB
     */    
    public function insert($table, $data) {

        foreach($data as $key => $value) {

            $this->_columns[] = $key;
            $this->_placeholders[] = '?';
            $this->_data[] = $value;
        }

        $columns = implode(',',$this->_columns);
        $placeholders = implode(',',$this->_placeholders);

        $this->_query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->execute_query();
    }

    /** 
     * Adding UPDATE table to query
     * 
     * @param string $table name
     * @return object DB
     */     
    public function update($table) {

        $this->_query = "UPDATE $table";
        return $this;
    }

    /** 
     * Adding DELETE FROM table to query
     * 
     * @param string $table name
     * @return object DB
     */     
    public function delete($table) {

        $this->_query = "DELETE FROM $table";
        return $this;        
    }

    /** 
     * Adding SET set values to query
     * 
     * @param array $data column names, column values
     * @return object DB
     */     
    public function set($data) {

        foreach($data as $key => $value) {

            $this->_setValues[] = $key . '=?';
            $this->_data[] = $value;
        }
        
        $setValues = implode(",", $this->_setValues);
        $this->_query .= " SET $setValues";

        return $this;
    }

    /** 
     * Adding ORDER BY column to query
     * 
     * @param string $column name
     * @return object DB
     */     
    public function order($column) {

        $this->_query .= " ORDER BY $column";
        return $this;
    }

    /** 
     * Adding DESC to query
     * 
     * @return object DB
     */     
    public function desc($colunn = null) {

        if(!empty($column) && $column !== null) {

            $this->_query .= " $column DESC";
        } else {
            $this->_query .= " DESC";
        }

        return $this;
    }

    /** 
     * Adding ASC to query
     * 
     * @return object DB
     */     
    public function asc($colunn = null) {

        if(!empty($column) && $column !== null) {

            $this->_query .= " $column ASC";
        } else {
            $this->_query .= " ASC";
        }

        return $this;
    }

    /** 
     * Adding INNER JOIN table to query
     * 
     * @param string $table name
     * @return object DB
     */    
    public function join($table) {

        $this->_query .= " INNER JOIN $table";
        return $this;
    }

    /** 
     * Adding LEFT JOIN table to query
     * 
     * @param string $table name
     * @return object DB
     */    
    public function joinLeft($table) {

        $this->_query .= " LEFT JOIN $table";
        return $this;
    }

    /** 
     * Adding ON col1 $operator col2 to query
     * 
     * @param string $col1 column name
     * @param string $operator value
     * @param string $col2 column name
     * @return object DB
     */    
    public function on($col1, $operator, $col2) {

        $this->_query .= " ON $col1 $operator $col2";
        return $this;
    }

    /** 
     * Adding SELECT id FROM table ORDER BY id DESC LIMIt 1 to query
     * 
     * @param string $table name
     * @return object DB
     */ 
    public function getLastId($table) {

        $this->_query = "SELECT id FROM $table ORDER BY id DESC LIMIT 1";
        return $this;
    }

    /** 
     * Fetching/executing raw sql
     * 
     * @param string $sql query
     * @return object DB
     */     
    public function raw($sql) {

        $this->_query = "$sql";
        return $this;
    }

    /** 
     * Executing query to fetch first row
     * 
     * @return object DB
     */ 
    public function first() {

        return $this->execute_query($this->_query)->fetch_query_first();
    }

    /** 
     * Executing query to fetch rows
     * 
     * @param string $operand value
     * @return object DB
     */        
    public function fetch($operand = null) {

        return $this->execute_query($this->_query)->fetch_query();
    }

    /** 
     * Getting the actual sql code
     * 
     * @return object DB
     */      
    public function getQuery() {

        return $this->_query;
    }

    /** 
     * Executing query
     * 
     * @return object DB
     */    
    public function run() {
        
        return $this->execute_query($this->_query);
    }
}