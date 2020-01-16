<?php
/**
 * @copyright Copyright (c) 2017 Sven Macolic
 * @license MIT.
 */

namespace reminderr\pdohelper;

use PDO;

class Connect {
         /**
         * @var object MySQL
         */
         private static $mysql;  
         /**
         * @var string $db
         */
         public $db;
         /**
         * @var array $conn
         */
         public static $conn;
          /**
         * @var array $host
         */
         public $host;
         /**
         * Constructor
         *
         * @param array $connectionObject
         */
         function __construct($dbconfig) {
                  set_exception_handler([$this, 'exceptionHandler']);
                  self::$conn = (object)$dbconfig;
                  $this->db = self::$conn->database; 
                  if(property_exists(self::$conn, 'dbport') && strlen(self::$conn->dbport)):
                        $this->host = self::$conn->host.':'.self::$conn->dbport;
                  else:
                        $this->host = self::$conn->host;
                  endif;
         }
         /**
         * Set PDO driver
         *
         * @return PDO object
         */
         public function PDOConnect() {
                 try {
                     switch(strtolower(self::$conn->engine)) {
                        case 'mysql': return new PDO(strtolower(self::$conn->engine).':host='.$this->host.';dbname='.self::$conn->database.';charset=latin1', self::$conn->user, self::$conn->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);
                        case 'sqlsrv': return new PDO(strtolower(self::$conn->engine).':Server='.self::$conn->host.';Database='.self::$conn->database.';ConnectionPooling=0', self::$conn->user, self::$conn->password);
                     }                     
                 } catch (Exception $e) {}
         }
         /**
         * Set MySQLi driver
         *
         * @return MySQLi object
         */
         public function MySQLIConnect() {
                 try {
                     return new mysqli(self::$conn->host, self::$conn->user, self::$conn->password, self::$conn->database);
                 } catch (Exception $e) {}
         }
         /**
         * Set MySQL driver
         *
         * @return MYSQL object
         */
         public function MySQLConnect() {
                 try {
                     self::$mysql = mysql_connect(self::$conn->host, self::$conn->user, self::$conn->password);
                     mysql_select_db(self::$conn->database, self::$mysql);
                 } catch (Exception $e) {}
         }
         /**
         * Set POSTGRES driver
         *
         * @return POSTGRE object
         */
         public function PostgreSQLConnect() {
                 try {
                     return pg_connect('host='.self::$conn->host.' dbname='.self::$conn->database.' user='.self::$conn->user.' password='.self::$conn->password);
                 } catch (Exception $e) {}
         }
         /**
         * Exception handler
         *
         * @param object Exception
         * @return string
         */
         public static function exceptionHandler($e) {
                print 'Exception Caught: '.$e->getMessage();
         }
}

?>
