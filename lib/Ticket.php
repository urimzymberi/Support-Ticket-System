<?php

namespace App\Lib;
//include_once 'autoloader.php';
use App\Lib\Database, PDO, Exception;

class Ticket extends Database
{
    private $id;
    private $number;
    private $dept_id;
    private $client_id;
    private $status_id;
    private $topic_id;
    private $staff_id;
    private $priority_id;
    private $subject;
    private $answered;
    private $created;
    private $updated;

    protected static $db_table = 'ticket';
    protected static $db_table_fields = array('id', 'number', 'dept_id', 'client_id', 'status_id', 'topic_id', 'staff_id', 'priority_id', 'subject', 'answered', 'created', 'updated');

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function set_number($number)
    {
        $this->number = $number;
    }

    public function get_number()
    {
        return $this->number;
    }

    public function set_dept_id($dept_id)
    {
        $this->dept_id = $dept_id;
    }

    public function get_dept_id()
    {
        return $this->dept_id;
    }

    public function set_client_id($client_id)
    {
        $this->client_id = $client_id;
    }

    public function get_client_id()
    {
        return $this->client_id;
    }

    public function set_status_id($status_id)
    {
        $this->status_id = $status_id;
    }

    public function get_status_id()
    {
        return $this->status_id;
    }

    public function set_topic_id($topic_id)
    {
        $this->topic_id = $topic_id;
    }

    public function get_topic_id()
    {
        return $this->topic_id;
    }

    public function set_staff_id($staff_id)
    {
        $this->staff_id = $staff_id;
    }

    public function get_staf_id()
    {
        return $this->staff_id;;
    }

    public function set_priority_id($priority_id)
    {
        $this->priority_id = $priority_id;
    }

    public function get_priority_id()
    {
        return $this->priority_id;
    }

    public function set_subject($subject)
    {
        $this->subject = $subject;
    }

    public function get_subject()
    {
        return $this->subject;
    }

    public function set_answered($answered)
    {
        $this->answered = $answered;
    }

    public function get_answered()
    {
        return $this->answered;
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