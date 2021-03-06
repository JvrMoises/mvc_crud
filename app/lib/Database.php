<?php
class DataBase {

    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $name = DB_NAME;
    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->name;
        $options =  array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->password, $options);
            $this->dbh->exec('set names utf8');
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    /**
     * Aún no se para que se utliza el método bind, video 11
     */
    public function bind($parameter, $value, $type = null) {
        if(is_null($type)) {
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
                break;
            }
        }
        $this->stmt->bindValue($parameter, $value, $type);
    }

    public function execute() {
        return $this->stmt->execute();
    }

    public function registers() {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function register() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getRowCount() {
        return $this->stmt->rowCount();
    }

}