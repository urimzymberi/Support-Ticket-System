<?php

namespace App\Lib;

use App\Lib\Database;

class Help_topic extends Database
{
    private $id;
    private $dept_id;
    private $isactive;
    private $topic;
    private $created;
    private $updated;

    protected static $db_table = 'help_topic';
    protected static $db_table_fields = array('id', 'dept_id', 'isactive', 'topic', 'created', 'updated');


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

    public function set_isactive($isactive)
    {
        $this->isactive = $isactive;
    }

    public function get_isactive()
    {
        return $this->isactive;
    }

    public function set_topic($topic)
    {
        $this->topic = $topic;
    }

    public function get_topic()
    {
        return $this->topic;
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