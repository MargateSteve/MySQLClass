<?php  date_default_timezone_set('Europe/London');
class DB {
  private static $_instance = null;

  private $_sql,
          $_bind = [],
          $_fetchMode = PDO::FETCH_OBJ,
          $_isError,
          $_errorInfo = [];
  
  public function __construct() {
    $servername = "localhost";
    $db = "framework";
    $username = "mfcsteve_steve";
    $password = "wagon1";
    try {
      $this->_pdo = new PDO('mysql:host=' . $servername . ';dbname=' . $db,   $username,  $password);
    } catch(PDOException $e) {
        die($e->getMessage());
    } 
  } //__construct ()

  public static function dbConnect() {
    // If an instance has not already been set, set it
    if(!isset(self::$_instance)) {
      self::$_instance = new DB();
    }
    // Return the instance
    return self::$_instance;
  } // dbConnect()

  public function set_sql(string $sql, bool $clear = false){

    if($clear) {
      $this->_sql = $sql;
    } else {
      $this->_sql .= $sql;
    }

  }

  public function show_sql(){
    return $this->_sql;
  }

  public function run_query () {
    /*if ($this->_debugging) {
      echo '<b>SQL : </b>'.$this->_sql;
      echo '<hr>';
      echo '<b>Bound Values</b>';
      echo '<pre>';
      $this->get_bind();
      echo '</pre>';
    }*/

		// Set error to false by default
    $this->_isError = false;
    if ($qry = $this->_pdo->prepare($this->_sql)) {
      $x = 1;
      //print_r($this->_bind);
      //echo 'bnd count: '.count($this->_bind);
      if(count($this->_bind)>0) {
        foreach($this->_bind as $param) {
          $qry->bindValue($x, $param);
          $x++;
        }
      }
      if($qry->execute()) {
        $this->_results = $qry->fetchAll($this->_fetchMode);
        $this->_count = $qry->rowCount();
      } else {
        // The query failed so set error to true
        $this->_errorInfo = $qry->errorInfo();
      }

    } // prepare
        
    
   
    /*
    if ($this->_debugging) {
      echo '<hr>';
      echo '<b>Count : </b>'.$this->count();
      echo '<hr>';
      echo '<b>Results</b>';
      echo '<pre>';
      print_r($this->results());
      echo '</pre>';
      if($this->error()) {
        echo '<hr>';
        echo '<b>Errors</b>';
        echo '<pre>';
        print_r($this->error());
        echo '</pre>';
        echo '<hr>';
      }
    }
    */    
    $this->_sql = '';

    $this->_bind = [];
    $this->_error = [];
    
    
    return $this;
  }//run_query()
}