<?php 
    /*
     * PDO Databse class
     * Connect to database
     * Create prepared statements
     * Bind values
     * return rows and results
     */

    class Database {
        private $host = DB_HOST;
        private $user = DB_USER;
        private $pass = DB_PASS;
        private $dbName = DB_NAME;

        private $dbh; // database handler - use this for preparing statements
        private $stmt;
        private $error;

        public function __construct() {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbName;    
            $options = array(
                PDO::ATTR_PERSISTENT => true, // increase performance by checking if there's already an established connection with the database
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // more elegant error handling
            );

            try {
                $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                echo $this->error;
            }
        }

        // Prepare statement with query
        public function query($sql) {
            $this->stmt = $this->dbh->prepare($sql);
        }

        // Bind values 
        public function bind($param, $value, $type = null) {
            // Bind will get called for every param that is passed to the prepared statement
            if (is_null($type)) { // if it's not past in then run the switch
                switch (true) {
                    case is_int($value): 
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value): 
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value): 
                        $type = PDO::PARAM_NULL;
                        break;
                    default: 
                        $type = PDO::PARAM_STR;
                }
            }

            $this->stmt->bindValue($param, $value, $type);
        }

        // Execute the prepared statement
        public function execute() {
            return $this->stmt->execute();
        }

        // Get results set as array of objects
        public function resultSet() {
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }

        // Get a single record as object
        public function single() {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }

        public function rowCount() {
            return $this->stmt->rowCount();
        }
    }