<?php

namespace App\Lib;

use App\Lib\Database;

class Department extends Database
{
    private $id;
    private $email;
    private $manager_id;
    private $name;
    private $ispublic;
    private $updated;
    private $created;

    protected static $db_table = "department";
    protected static $db_table_fields = array('id', 'email', 'manager_id', 'name', 'ispublic', 'updated', 'created');

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_email($email)
    {
        $this->email = $email;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function set_manager_id($manager_id)
    {
        $this->manager_id = $manager_id;
    }

    public function get_manager_id()
    {
        return $this->manager_id;
    }

    public function set_name($name)
    {
        $this->name = $name;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_ispublic($ispublic)
    {
        $this->ispublic = $ispublic;
    }

    public function get_ispublic()
    {
        return $this->ispublic;
    }

    public function set_created($created)
    {
        $this->created = $created;
    }

    public function get_created()
    {
        return $this->created;
    }

    public function set_updated($updated)
    {
        $this->updated = $updated;
    }

    public function get_updated()
    {
        return $this->updated;
    }
}


?>