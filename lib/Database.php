<?php

namespace App\Lib;

use PDO, Exception, ReflectionClass;

abstract class Database
{
    private $servername;
    private $dbname;
    private $charset;
    private $username;
    private $password;

    public function connectDB()
    {
        $this->servername = "localhost";
        $this->dbname = "s-ticket";
        $this->charset = "utf8mb4";
        $this->username = "root";
        $this->password = "";
        try {
            $dsn = "mysql:host=" . $this->servername . ";dbname=" . $this->dbname . ";charset=" . $this->charset;
            $pdo = new PDO($dsn, $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (Exception $e) {
            echo "Failed to connect to database: " . $e->getMessage();
        }
    }

    public function prepare($sql)
    {
        return $this->connectDB()->prepare($sql);
    }

    // public function get_class_name()
    // {
    //     $class_name = new ReflectionClass($this);
    //     return $class_name = ucfirst($class_name->getShortName());
    // }

    public function properties_value()
    {
        $properties = array();
        $class = new ReflectionClass($this);
        $methods = $class->getMethods();
        foreach ($methods as $method) {
            if (substr($method->name, 0, 3) === "get") {
                foreach (static::$db_table_fields as $field) {
                    if (substr($method->name, 4, strlen($method->name) - 1) === $field)
                        $properties[substr($method->name, 4)] = $this->{$method->name}();
                }
            }
        }
        return $properties;
    }

    // protected function properties()
    // {
    //     $properties = array();
    //     foreach (static::$db_table_fields as $field) {
    //         if (property_exists($this, $field)) {
    //             $properties[$field] = $this->$field;
    //         }
    //     }
    //     return $properties;
    // }

    public function get_all()
    {
        $sql = "SELECT * FROM " . static::$db_table;
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $rseult = $stmt->fetchAll();
        return $rseult;
    }


    public function get_by_id($id)
    {
        $sql = "SELECT * FROM " . static::$db_table;
        $sql .= " WHERE id=" . $id;
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }

    public function get_max_id()
    {
        $sql = "SELECT MAX(id) AS 'max_ticket_id' FROM " . static::$db_table;
        $stmt = $this->connectDB()->prepare($sql);
        $stmt->execute();
        return $stmt->fetch();;
    }

    public function get_by_field($field, $value)
    {
        $sql = "SELECT * FROM " . static::$db_table;
        $sql .= " WHERE $field='" . $value . "'";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }

    public function get_all_by_field($field, $value)
    {
        $sql = "SELECT * FROM " . static::$db_table;
        $sql .= " WHERE $field='" . $value . "'";
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function get_by_query($sql)
    {
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }

    public function get_by_query_count($sql)
    {
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $result = $stmt->rowCount();
        return $result;
    }

    public function get_all_by_query($sql)
    {
        $stmt = $this->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function fill_properties($array_properties_value)
    {
        $class = new ReflectionClass($this);
        $methods = $class->getMethods();
        foreach ($methods as $method) {
            if (substr($method->name, 0, 3) === "set") {
                foreach ($array_properties_value as $key => $value) {
                    if (substr($method->name, 4, strlen($method->name) - 1) === $key)
                        $this->{$method->name}($value);
                }
            }
        }
    }

    public function create()
    {
        try {
            $p = $this->properties_value();
            $sql = "INSERT INTO " . static::$db_table . "(" . implode(",", array_keys($p)) . ")";
            $sql .= "VALUES('" . implode("','", array_values($p)) . "')";
            $stmt = $this->prepare($sql);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function update()
    {
        try {
            $properties = $this->properties_value();
            $properties_pair = array();
            foreach ($properties as $key => $value) {
                $properties_pair[] = "{$key}='{$value}'";
            }
            $sql = "UPDATE " . static::$db_table . " SET ";
            $sql .= implode(", ", $properties_pair);
            $sql .= " WHERE id = " . $properties['id'];
            $stmt = $this->connectDB()->prepare($sql);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE from " . static::$db_table . " WHERE id = $id";
            $stmt = $this->connectDB()->prepare($sql);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}


?>
