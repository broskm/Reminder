<?php
class User {
    private $db;
    public function __construct() {
        $this->db = new Database;
    }

    public function register($data) {
        $myuid = uniqid('id');
        $this->db->query('INSERT INTO users (user_id,username, email, password) VALUES(:user_id,:username, :email, :password)');

        //Bind values
        $this->db->bind(':user_id', $myuid );
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        //Execute function
        if ($this->db->execute()) {
            return true;
        }
        return false;
    }

    public function login($username, $password) {
        $this->db->query('SELECT * FROM users WHERE username = :username');

        //Bind value
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        $hashedPassword = $row["password"];

        if (password_verify($password, $hashedPassword)) {
            return $row;
        }
        return false;
    }

    //Find user by email. Email is passed in by the Controller.
    public function findUserByEmail($email) {
        //Prepared statement
        $this->db->query('SELECT * FROM users WHERE email = :email');

        //Email param will be binded with the email variable
        $this->db->bind(':email', $email);

        //Check if email is already registered
        if($this->db->rowCount() > 0) {
            return true;
        }
        return false;
        
    }
    public function findUserByUsername($username) {
        $this->db->query('SELECT * FROM users WHERE username = :username');

        $this->db->bind(':username', $username);

        if($this->db->rowCount() > 0) {
            return true;
        }
        return false;
        
    }
}