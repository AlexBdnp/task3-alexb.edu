<?php

abstract class Model
{
    public $pdo;
    protected $table;
    protected $idName;
    
    abstract function getPage($page);

    /**
     * Create Model.
     * 
     * @param $idName assign name of ID column
     * 
     * @param string $tableName Table to work with. If $tableName is not passed, it will be extracted from class name.
     * For example, MovieModel gives name of table `movie`.
     * DirectorModel will interact with `director` table.
     */
    public function __construct($idName = "id", string $tableName = NULL)
    {
        $this->pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        // assign name of table which will be used in SQL requests
        if (is_null($tableName)) {
            $className = get_class($this);
            $length = strlen($className);
            
            if ($length > 5 && substr($className, -5) == 'Model') {
                $this->table = strtolower(substr($className, 0, $length-5));
            } else {
                throw new Exception("Error: check DB models.", 1);
            }
        } else {
            $this->table = strtolower($tableName);
        }
        
        // check if such table exists
        $table_exists = false;
        $statement = $this->pdo->prepare("SHOW TABLES");
        $statement->execute();
        $tables = $statement->fetchAll(PDO::FETCH_NUM);
        
        foreach($tables as $table) {
            if ($table[0] == $this->table) {
                $table_exists = true;
                break;
            }
        }

        if (! $table_exists) {
            throw new Exception("Table `$this->table` does not exist", 1);            
        }

        // assign name of ID column. If not passed, then assign "id"
        $this->idName = $idName;
    }

    public function get(int $id)
    {
        $sql = "SELECT * FROM `$this->table`
                WHERE $this->idName = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['id' => $id]);
        $rows = $statement->fetch(PDO::FETCH_ASSOC);

        return $rows;
    }

    public function all()
    { 
        $sql = "SELECT * FROM `$this->table`";
        $statement = $this->pdo->prepare($sql);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    public function insert(array $data)
    {
        $columns = "";
        $pdo_cols = "";

        foreach ($data as $key => $value) {
            $columns .= "$key, ";
            $pdo_cols .= ":$key, ";
        }

        $columns = substr($columns, 0, -2);
        $pdo_cols = substr($pdo_cols, 0, -2);


        $sql = "INSERT INTO `$this->table` ($columns) VALUES ($pdo_cols)";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);

        $sql = "UPDATE `director`
                SET name = :name
                WHERE directorId = :id";

        $statement = $this->pdo->prepare($sql);
        $statement->execute();
    }

    public function update(int $id, $data)
    {
        $set = "";

        foreach ($data as $key => $value) {
            $set .= "`$key` = :$key, ";
        }

        $set = substr($set, 0, -2);
        $data = array_merge(['id' => $id], $data);
        $sql = "UPDATE `$this->table` SET $set WHERE $this->idName = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($data);    
    }

    public function delete(int $id)
    {
        $sql = "DELETE FROM `$this->table` WHERE $this->idName = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['id' => $id]);        
    }
    
    public function count() : int
    {
        $sql = "SELECT COUNT(*) AS amount FROM " . $this->table; 
        $rows = $this->pdo->query($sql, PDO::FETCH_ASSOC)->fetchAll();
        return $rows[0]['amount'];
    }

    public function pages() : int
    {
        return $this->count() / 10 + ($this->count() % 10 > 0 ? 1 : 0);
    }
}
