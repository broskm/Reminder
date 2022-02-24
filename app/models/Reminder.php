<?php
class Reminder
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function findUserReminders($user_id)
    {
        $this->db->query("SELECT * FROM reminders where user_id=:user_id ORDER BY creation_date DESC");
        $this->db->bind(":user_id", $user_id);
        $result = $this->db->resultSet();
        return $result;
    }

    public function addRow($data)
    {
        $this->db->query("INSERT INTO reminders (date) VALUES
         (:date)");

        $this->db->bind(':user_id', $data);
        // $this->db->bind(':title', $title);
        // $this->db->bind(':date', $data);
        // $this->db->bind(':remind_before', $remind_before);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function create($data)
    {   
        $this->db->query('INSERT INTO reminders (user_id, title, date,remind_before,creation_date) VALUES(:user_id, :title, :date,:remind_before,:creation_date)');

        //Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':remind_before', $data['remind_before']);
        $this->db->bind(':creation_date', $data['creation_date']);

        //Execute function
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function findReminderByID($reminder_id)
    {
        $this->db->query("SELECT * FROM reminders where reminder_id=:reminder_id");
        $this->db->bind(":reminder_id", $reminder_id);
        $result = $this->db->single();
        return $result;
    }
    public function findUserByID($user_id)
    {
        $this->db->query("SELECT * FROM users where user_id=:user_id");
        $this->db->bind(":user_id", $user_id);
        $result = $this->db->single();
        return $result;
    }

    public function update($data)
    {
        $this->db->query('UPDATE reminders
        SET title = :title, date = :date, remind_before = :remind_before
        WHERE (reminder_id=:reminder_id) AND (user_id=:user_id);');

        //Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':remind_before', $data['remind_before']);
        $this->db->bind(':reminder_id', $data['reminder_id']);
        $this->db->bind(':user_id', $data['user_id']);


        //Execute function
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function delete($data)
    {
        $this->db->query('DELETE FROM `reminders`
        WHERE reminder_id=:reminder_id;');
        //Bind values
        $this->db->bind(':reminder_id', $data['reminder_id']);
        //Execute function
        if ($this->db->execute()) {
            return true;
        }
        return false;
        
    }

    public function allReminders()
    {
        $this->db->query("SELECT * FROM reminders");
        //Execute function
        return $this->db->resultSetAssoc();


    }
}
