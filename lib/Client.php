<?php

namespace App\Lib;

use App\Lib\Database;

class Client extends Database
{
    private $id;
    private $dept_id;
    private $status;
    private $username;
    private $password;
    private $name;
    private $email;
    private $phone;
    private $created;
    private $updated;

    protected static $db_table = 'client';
    protected static $db_table_fields = array('id', 'dept_id', 'status', 'username', 'password', 'name', 'email', 'phone', 'created', 'updated');

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_dept_id($dept_id)
    {
        $this->dept_id = $dept_id;
    }

    public function get_dept_id()
    {
        return $this->dept_id;
    }

    public function set_status($status)
    {
        $this->status = $status;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_username($username)
    {
        $this->username = $username;
    }

    public function get_username()
    {
        return $this->username;
    }

    public function set_password($password)
    {
        $this->password = $password;
    }

    public function get_password()
    {
        return $this->password;
    }

    public function set_name($name)
    {
        $this->name = $name;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_email($email)
    {
        $this->email = $email;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function set_phone($phone)
    {
        $this->phone = $phone;
    }

    public function get_phone()
    {
        return $this->phone;
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