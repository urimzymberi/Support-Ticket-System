<?php

namespace App\Lib;

use App\Lib\Database;
use Exception, PDO;

class Event extends Database
{
    private $id;
    private $ticket_id;
    private $dept_id;
    private $staff_id;
    private $client_id;
    private $topic_id;
    private $state;
    private $assigned;

    protected static $db_table = 'event';
    protected static $db_table_fields = array('id', 'ticket_id', 'dept_id', 'staff_id', 'client_id', 'topic_id', 'state', 'assigned');

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

    public function set_dept_id($dept_id)
    {
        $this->dept_id = $dept_id;
    }

    public function get_dept_id()
    {
        return $this->dept_id;
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

    public function set_topic_id($topic_id)
    {
        $this->topic_id = $topic_id;
    }

    public function get_topic_id()
    {
        return $this->topic_id;
    }

    public function set_state($state)
    {
        $this->state = $state;
    }

    public function get_state()
    {
        return $this->state;
    }

    public function set_assigned($assigned)
    {
        $this->assigned = $assigned;
    }

    public function get_assigned()
    {
        return $this->assigned;
    }
}

?>