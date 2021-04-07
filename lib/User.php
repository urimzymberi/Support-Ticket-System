<?php
namespace App\Lib;

use App\Lib\Database;

class User extends Database
{
    private $id;
    private $dept_id;
    private $username;
    private $firstname;
    private $lastname;
    private $password;
    private $email;
    private $phone;
    private $isactive;
    private $isadmin;
    private $isvisible;
    private $created;
    private $updated;

    protected static $db_table = 'staff';
    protected static $db_table_fields = array('id', 'dept_id', 'username', 'firstname', 'lastname', 'password', 'email', 'phone', 'isactive', 'isadmin', 'isvisible', 'created', 'updated');

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

    public function set_username($username)
    {
        $this->username = $username;
    }

    public function get_username()
    {
        return $this->username;
    }

    public function set_firstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function get_firstname()
    {
        return $this->firstname;
    }

    public function set_lastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function get_lastname()
    {
        return $this->lastname;
    }

    public function set_password($password)
    {
        $this->password = $password;
    }

    public function get_password()
    {
        return $this->password;
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

    public function set_isactive($isactive)
    {
        $this->isactive = $isactive;
    }

    public function get_isactive()
    {
        return $this->isactive;
    }

    public function set_isadmin($isadmin)
    {
        $this->isadmin = $isadmin;
    }

    public function get_isadmin()
    {
        return $this->isadmin;
    }

    public function set_isvisible($isvisible)
    {
        $this->isvisible = $isvisible;
    }

    public function get_isvisible()
    {
        return $this->isvisible;
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
