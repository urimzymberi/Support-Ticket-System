<?php

namespace App\Lib;
use App\Lib\Database;
use Exception, PDO;

class Entry extends Database
{
    private $id;
    private $ticket_id;
    private $staff_id;
    private $client_id;
    private $type;
    private $poster;
    private $body;
    private $created;
    private $updated;

    protected static $db_table = 'entry';
    protected static $db_table_fields = array('id', 'ticket_id', 'staff_id', 'client_id', 'type', 'poster', 'body', 'created', 'updated');

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_ticket_id($ticket_id)
    {
        $this->ticket_id = $ticket_id;
    }

    public function get_ticket_id()
    {
        return $this->ticket_id;
    }

    public function set_staff_id($staff_id)
    {
        $this->staff_id = $staff_id;
    }

    public function get_staff_id()
    {
        return $this->staff_id;
    }

    public function set_client_id($client_id)
    {
        $this->client_id = $client_id;
    }

    public function get_client_id()
    {
        return $this->client_id;
    }

    public function set_type($type)
    {
        $this->type = $type;
    }

    public function get_type()
    {
        return $this->type;
    }

    public function set_poster($poster)
    {
        $this->poster = $poster;
    }

    public function get_poster()
    {
        return $this->poster;
    }

    public function set_body($body)
    {
        $this->body = $body;
    }

    public function get_body()
    {
        return $this->body;
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

    public function get_update()
    {
        return $this->updated;
    }

}

?>