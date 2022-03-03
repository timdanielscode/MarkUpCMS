<?php
/**
 * @author Tim Daniëls
 * @version 1.0
 */
namespace database;

class DB {

    protected $_pdo, $_error;
    private static $_instance = null;
    public $stmt, $error = false;

    public $query = "";
    public $data;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $db
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
     * 
     * Use for creating an instance of DB class
     * 
     * @return void
     */    
    public static function try() {

        $file = '../config/database/config.ini';
        if(file_exists($file)) {
            
            if(!isset(self::$_instance)) {
                $ini = parse_ini_file($file);
                self::$_instance = new DB($ini['host'], $ini['user'], $ini['password'], $ini['db']);
            }
        } 

        return self::$_instance;
    }

    /**
     * 
     * @param string $sql expecting a sql query
     * @param array $data part as placeholders
     * 
     * @return object DB
     */      
    public function execute_query($sql, $data = null) {

        $this->stmt = $this->_pdo->prepare($sql);
      
        if($data) {
            if(!$this->stmt->execute($data)) {
                echo $this->error("Could not execute the query!");
                return $this->error = true;            
            } 
        } else {
            if(!$this->stmt->execute()) {
                echo $this->error("Could not execute the query!");
                return $this->error = true;            
            }             
        }        
        $this->data = null;
        return $this;
    }

    /**
     * 
     * 
     * 
     *  QUERY BUILDER UNDERNEATH
     * 
     * 
     * 
     * 
     */

    /**
     * @return object DB
     */     
    public function fetch_query() {

        return $this->stmt->fetchAll();
    }

    /**
     * @return object DB
     */     
    public function fetch_query_first() {
        return $this->stmt->fetch();
    }

    /**
     * @return object DB
     */     
    public function error($error) {

        $this->_error = $error;
        return $this->_error;
    }

   /** 
     * @param string $colls
     * @return object DB
     */    
    public function select($colls) {
    
        $args = func_get_args();
        $columns = implode(',', $args);
        $this->query = "SELECT $columns";

        return $this;
    }

    /** 
     * @param string $table
     * @return object DB
     */ 
    public function all($table) {

        $this->query = "SELECT * FROM $table";
        return $this;
    }

    /** 
     * @param string $table
     * @return object DB
     */ 
    public function from($table) {

        $this->query .= " FROM $table";
        return $this;
    }

    /** 
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return object DB
     */ 
    public function where($column, $operator, $value) {

        if($this->data !== null) {
            array_push($this->data, $value);
        } else {
            $this->data = array($value);
        }
        $this->query .= " WHERE $column $operator ?";

        return $this;
    }

    /** 
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return object DB
     */     
    public function and($column, $operator, $value) {

        $this->data[] = $value;
        $this->query .= " AND $column $operator ?";

        return $this;
    }

    /** 
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return object DB
     */     
    public function or($column, $operator, $value) {

        $this->data[] = $value;
        $this->query .= " OR $column $operator ?";

        return $this;
    }

    /** 
     * @param string $num
     * @return object DB
     */     
    public function limit($num) {

        $this->query .= " LIMIT $num";
        return $this;
    }

    /** 
     * @param string $table
     * @param array $data
     * @return object DB
     */    
    public function insert($table, $data) {

        $this->data = [];
        $columns = [];
        $placeholders = [];
        foreach($data as $key => $val) {
            array_push($columns, $key);
            array_push($placeholders, '?');
            array_push($this->data, $val);
        }
        $columns = implode(',',$columns);
        $placeholders = implode(',',$placeholders);
        $this->query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->execute_query($this->query, $this->data);

        return $this;
    }

    /** 
     * @param string $table
     * @return object DB
     */     
    public function update($table) {

        $this->query = "UPDATE $table";
        return $this;
    }

    /** 
     * @param string $table
     * @return object DB
     */     
    public function delete($table) {

        $this->query = "DELETE FROM $table";
        return $this;        
    }

    /** 
     * @param array $data
     * @return object DB
     */     
    public function set($data) {

        $values = [];
        $sets = [];

        foreach($data as $key => $value) {

            array_push($sets, $key."=? ");
            array_push($values, $value);
        }
        
        $sets = implode(",", $sets);
        $this->data = $values;
        $this->query .= " SET $sets";

        return $this;
    }

    /** 
     * @param string $column
     * @return object DB
     */     
    public function order($column) {

        $this->query .= " ORDER BY $column";
        return $this;
    }

    /** 
     * @param mixed int|string $num
     * @return object DB
     */     
    public function desc($num) {

        $this->query .= " DESC LIMIT $num";
        return $this;
    }

    /** 
     * @param string $table
     * @return object DB
     */    
    public function join($table) {

        $this->query .= " INNER JOIN $table";
        return $this;
    }

    /** 
     * @param string $col1
     * @param string $operator
     * @param string $col2
     * @return object DB
     */    
    public function on($col1, $operator, $col2) {

        $this->query .= " ON $col1 $operator $col2";
        return $this;
    }

    /** 
     * @param string $alias
     * @return object DB
     */   
    public function as($alias) {

        $this->query .= " AS $alias";
        return $this;
    }

    /** 
     * @param string $sql
     * @return object DB
     */     
    public function raw($sql) {

        $this->query = "$sql";
        return $this;
    }

    /** 
     * @return object DB
     */ 
    public function first() {

        if($this->data) {
            return $this->execute_query($this->query, $this->data)->fetch_query_first();
        } else {
            return $this->execute_query($this->query)->fetch_query_first();
        }
    }

    /** 
     * @param string $operand
     * @return object DB
     */        
    public function fetch($operand = null) {

        if($this->data) {
            return $this->execute_query($this->query, $this->data)->fetch_query();
        } else {
            return $this->execute_query($this->query)->fetch_query();
        }
    }

    /** 
     * @return object DB
     */      
    public function getQuery() {

        return $this->query;
    }

    /** 
     * @return object DB
     */    
    public function run() {

        return $this->execute_query($this->query, $this->data);
    }

}