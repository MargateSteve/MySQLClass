<?php  date_default_timezone_set('Europe/London');
class DB {
  /* The above code is defining a private static property called  in a PHP class. This
  property is initialized to null. This code is likely part of a design pattern called Singleton,
  where the class ensures that only one instance of itself can be created. */
  private static $_instance = null;

  /* The line `private ,` is declaring a private property `_sql` within the `DB` class in PHP.
  This property is used to store the SQL query that will be executed by the database class methods.
  By declaring it as private, it means that this property can only be accessed within the `DB` class
  itself and not from outside the class or its instances. This property will hold the SQL query
  string that is being built or executed by various methods within the `DB` class. */
  private $_sql,
          /* The line ` = [],` is initializing a private property `_bind` within the `DB` class
          in PHP as an empty array. This property is used to store the values that will be bound to
          the SQL query when executing database operations. By setting it to an empty array, it
          ensures that the `_bind` property starts with no values when a new instance of the `DB`
          class is created. This property will be populated with values that need to be bound to the
          SQL query before execution. */
          $_bind = [];

  /* The above code snippet is setting the fetch mode of a PDO object to `PDO::FETCH_OBJ`. This means
  that when fetching results from a database query using this PDO object, the results will be
  returned as objects with property names that correspond to the column names in the result set. */
  private $_fetchMode = PDO::FETCH_OBJ,

          $_isError,
          $_errorInfo = [],
          $_results,
          $_count,
          $_debugging = false,

          $_last_insert_id;
  
  /**
   * The function establishes a connection to a MySQL database using PDO in PHP.
   */
  public function __construct() {
    $servername = "localhost";
    $db = "mysql_class";
    $username = "mfcsteve_steve";
    $password = "wagon1";
    try {
      $this->_pdo = new PDO('mysql:host=' . $servername . ';dbname=' . $db,   $username,  $password);
      $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die($e->getMessage());
    } 
  }//__construct()

  /**
   * The function `dbConnect` is a static method in a PHP class that ensures only one instance of the
   * class is created and returns that instance.
   * 
   * @return instance The `dbConnect` function is returning an instance of the `DB` class.
   */
  public static function dbConnect() {
    // If an instance has not already been set, set it
    if(!isset(self::$_instance)) {
      self::$_instance = new DB();
    }
    // Return the instance
    return self::$_instance;
  }//dbConnect()

  // Find if a value exists in a colum in a table 
  public function record_exists($table, $column, $value) : bool{

  }

  public function last_insert_id():int{
    return $this->_last_insert_id;
  }//last_insert_id()

  public function insert ($table, $fields=[]):bool{

    foreach ($fields as $key => $value) {
      $newfields['`'.$key.'`'] = $value;
    }

    $keys = implode(',', array_keys($newfields));

    $ph = ''; // placeholder
    foreach($fields as $field) {
      if(!empty($ph)){$ph .= ',';}
      $ph .= '?';
    }

    $this->_sql = "INSERT INTO $table ($keys) VALUES ($ph)";
    $this->bind_set (array_values($fields));

    //$this->show_query();

    if($this->query_run(clear_after:false)) {
      $this->_insert_id = $this->_pdo->lastInsertId();
      //$this->_post_status = true;
      return true;
    } else {
      //$this->_post_status = false;
      return false;
    }

  }

  // Think about User data and times. Times can probably be set in DB on update clauses.
  /**
   * This PHP function is used to update records in a database table based on specified fields and
   * conditions.
   * 
   * @param table The `table` parameter in the `update` function represents the name of the table
   * in the database that you want to update. It is a required parameter and should be a string
   * containing the name of the table you want to perform the update operation on.
   * @param fields The `fields` parameter in the `update` function is an associative array that
   * contains the columns to be updated along with their new values. Each key represents the column
   * name, and the corresponding value is the new value for that column.
   * @param clause The `clause` parameter in the `update` function is used to specify the
   * conditions that determine which records in the database table should be updated. It is an
   * associative array where the keys represent the column names and the values are arrays containing
   * the condition details.
   * @param clause_type The `clause_type` parameter in the `update` function is used to specify
   * how multiple conditions in the `WHERE` clause should be combined. The default value is 'AND',
   * which means that all conditions must be true for the record to be updated. Alternatively, you can
   * set it to 'OR' to allow any conditions to be true
   */
  public function update($table, $fields=[], $clause=[], $clause_type='AND'):null { 
    
    $set = '';
    $setclause = '';

    
    foreach ($fields as $key => $value) {
      $set .= (!$set)?'SET ':', ';
      $set .= <<<set
        $key = ?       
      set;
      $setbind[] = $value;
    }
    
   
    foreach ($clause as $key => $value) {
      $setclause .= (!$setclause)?'WHERE ': $clause_type.' ';
      $setclause .= <<<set
        $value[0] $value[1]  ?     
      set;
      $setbind[] = $value[2];
    }

    $qry = <<<qry
      UPDATE $table
      $set
      $setclause      
    qry;     
    
    $this->sql_set($qry, true);
    $this->bind_set ($setbind, true);
    $this->query_run(clear_after:false);
    //$this->show_query();
    // This should not return but set a $this value if needed
    /*
    if($this->query_run()) {
      $this->_post_status = true;
    } else {
      $this->_post_status = false;
    }
    return $this->_post_status;*/
  } //update()

  /**
   * This PHP function is used to delete records from a database table based on specified conditions.
   * 
   * @param table The `table` parameter in the `delete` function represents the name of the table from
   * which you want to delete records. It is a required parameter and should be a string containing the
   * name of the table in your database from which you want to delete data.
   * @param clause The `clause` parameter in the `delete` function is an array that contains the
   * conditions for the deletion operation. Each element in the array represents a condition to be
   * included in the WHERE clause of the DELETE SQL statement.
   * @param clause_type The `clause_type` parameter in the `delete` function specifies how multiple
   * conditions in the WHERE clause should be combined. It can have two possible values: 'AND' or 'OR'.
   * 
   * @return bool The `delete` function is returning a boolean value. It returns `true` if the deletion
   * operation was successful and affected at least one row, and `false` if no rows were affected or if
   * an error occurred during the deletion process.
   */
  public function delete($table, $clause=[], $clause_type='AND'):bool { 
    
    $set = '';
    $setclause = '';

    foreach ($clause as $key => $value) {
      $setclause .= (!$setclause)?'WHERE ': $clause_type.' ';
      $setclause .= <<<set
        $value[0] $value[1]  ?     
      set;
      $setbind[] = $value[2];
    }

    $qry = <<<qry
      DELETE FROM $table
      $setclause      
    qry;     
    
    $this->sql_set($qry, true);
    $this->bind_set ($setbind, true);
    $this->query_run(clear_after:false);
    
    return ($this->_count) ? true: false;
  } //delete()

  /**
   * This PHP function generates and executes a SELECT query based on the provided table, columns, and
   * optional clause.
   * 
   * @param table The `table` parameter in the `select` function represents the name of the table from
   * which you want to select data. It is a required parameter and should be a string value containing
   * the name of the table in your database.
   * @param clause The `` parameter in the `select` function is used to specify conditions for
   * the SQL query. It is an array that contains three elements:
   * @param cols The `` parameter in the `select` function is used to specify the columns that you
   * want to select from the database table. If no columns are specified, all columns (`*`) will be
   * selected by default. You can pass a string containing the column names you want to select as the
   * `$
   */
  public function select($table, $clause=[], $cols=null){
    $this->_sql = 'SELECT ';
    $this->_sql .= ($cols) ? $cols:'*';
    $this->_sql .= ' FROM '.$table;

    if(!empty($clause)){
      $this->_sql .= ' WHERE '.$clause[0] .' '.$clause[1].'  ?';
      $this->bind_set([$clause[2]]);
    }
    $this->query_show();
    $this->query_run();

  }


  public function exists($table, $clause=[]){
    $this->select($table, $clause);
    return ($this->row_count())?true:false;
  }

  public function row_count() {
    return $this->_count;
  }

  /**
   * The function `sql_set` appends or replaces the SQL query string based on the value of the `clear`
   * parameter.
   * 
   * @param string sql The `sql` parameter is a string that represents the SQL query that you want to
   * set or append to the existing SQL query in the `_sql` property.
   * @param bool clear The `clear` parameter in the `sql_set` function is a boolean parameter that
   * determines whether the existing SQL query should be cleared before setting a new SQL query. If
   * `clear` is set to `true`, the existing SQL query will be replaced entirely with the new SQL query
   * passed as the first
   */
  public function sql_set(string $sql, bool $clear=false){
    if($clear) {
      $this->_sql = $sql;
    } else {
      $this->_sql .= $sql;
    }
  }//sql_set()

  /**
   * The `sql_clear` function in PHP clears the SQL query stored in the `_sql` property.
   */
  public function sql_clear() {
    $this->_sql = '';
  }//sql_clear()

  /**
   * The bind_set function in PHP takes an array of values and optionally clears the existing values
   * before adding the new ones to the internal bind array.
   * 
   * @param array array The `array` parameter in the `bind_set` function is expected to be an array of
   * values that you want to add to the internal `_bind` property of the object.
   * @param bool clear The `clear` parameter in the `bind_set` function is a boolean flag that
   * determines whether the existing `_bind` array should be cleared before adding new values from the
   * input array. If `clear` is set to `true`, the `empty_bind` method is called to clear the `_bind
   */
  public function bind_set(array $array, bool $clear=false) {
    if($clear) {$this->bind_clear();}
    foreach ($array as $value) {
      array_push($this->_bind, $value);
    }
  }//bind_set()

  /**
   * The function `error_clear` in PHP clears the `_bind` property by setting it to an empty array.
   */
  public function error_clear() {
    $this->_bind = [];
  }

  /**
   * The function `bind_clear` in PHP clears the `_bind` array within the class.
   */
  public function bind_clear() {
    $this->_bind = [];
  }

  /**
   * The function `sql_show` returns the SQL query stored in the `_sql` property.
   * 
   * @return string The function `sql_show` is returning the value of the `_sql` property of the object.
   */
  public function sql_show(){
    return $this->_sql;
  }//sql_show()

  /**
   * The function `bind_show()` returns the value of the `_bind` property.
   * 
   * @return array The function `bind_show()` is returning the value of the property `_bind` from the current
   * object.
   */
  public function bind_show() {
    return $this->_bind;
  }//bind_show()

  /**
   * The function `query_show` in PHP displays the SQL query and its bindings in a formatted manner.
   */
  public function query_show() {
    echo '<pre><h5>Query<h5>'.$this->sql_show ().'</pre>';
    echo '<pre><h5>Bind</h5>';
    print_r($this->bind_show());
    echo '</pre>';
  }//query_show()

  /**
   * The function `query_run` in PHP prepares and executes a SQL query, binds parameters, fetches
   * results, handles errors, and clears data after execution.
   * 
   * @param bool clear_after The `clear_after` parameter in the `query_run` function is a boolean parameter
   * with a default value of `true`. It determines whether to clear the SQL query, bind parameters, and
   * error information after the query execution is completed.
   * 
   * @return object The `query_run` function is returning the current instance of the class after executing
   * the query and performing some cleanup operations if `` is set to true.
   */
  public function query_run ($clear_after=true) {

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
        $this->_isError = true;
        $this->_errorInfo = $qry->errorInfo();
      }

    } // prepare

    
    if ($this->_debugging) {
      echo '<hr>';
      echo '<pre>';
      print_r($this);
      echo '</pre>';
    }

    if($clear_after){
      $this->sql_clear();
      $this->bind_clear();
      $this->error_clear();
    }
    
    return $this;
  }//query_run()


  /**
   * The `row` function in PHP returns a specific row of results based on the provided key.
   * 
   * @param key The `key` parameter in the `row` function is used to specify the index of the row you
   * want to retrieve from the `_results` array. If the row at the specified index exists in the
   * `_results` array, it will be returned. If the row does not exist at the specified
   * 
   * @return bool The `row` function is returning the element at the specified key in the `_results` array
   * if it exists, otherwise it returns `false`.
   */
  public function row($key=0){
    if(isset($this->_results[$key])) {
      return $this->_results[$key];
    }
    return false;
  }//row()
  
  /**
   * The `results` function in PHP returns the `_results` property of the class.
   * 
   * @return object The function `results()` is returning the value of the property `_results` from the
   * current object.
   */
  public function results() {
    return $this->_results;
  }//results()

  
  ## Debugging
  /**
   * The function `show_row` in PHP displays the contents of the current row in a formatted manner.
   */
  public function show_row() {
    echo '<pre><h5>Row</h5>';
    print_r($this->row());
    echo '</pre>';
  }//show_row()
    
  /**
   * The function "show_results" in PHP displays the results of a method in a formatted manner.
   */
  public function show_results() {
    echo '<pre><h5>Results</h5>';
    print_r($this->results());
    echo '</pre>';
  }//show_results()

  /**
   * The function `show_db_object` in PHP is used to display the details of the current object in a
   * formatted manner.
   */
  public function show_db_object() {
    echo '<pre><h5>DB Object</h5>';
    print_r($this);
    echo '</pre>';
  }//show_db_object()
}