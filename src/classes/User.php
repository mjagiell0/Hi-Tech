<?php
class User implements Querable{
    private $id;
    private $firstname;
    private $lastname;
    private $email;

    public function __construct() {
        $this->id = null;
        $this->firstname = "";
        $this->lastname = "";
        $this->email = "";
    }

    public function withId($id) {
        $this->id = $id;
        return $this;
    }

    public function withFirstname($firstname) {
        $this->firstname = $firstname;
        return $this;
    }

    public function withLastname($lastname) {
        $this->lastname = $lastname;
        return $this;
    }

    public function withEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getQuery(...$criteria) {
        if (!filter_var($criteria[0], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email format.");
        }
        return "SELECT id, firstname, lastname, email FROM user WHERE email = ?";
    }

    public function fromResult($result) {
        if ($row = $result->fetch_assoc()) {
        return $this
            ->withId($row['id'])
            ->withFirstname($row['firstname'])
            ->withLastname($row['lastname'])
            ->withEmail($row['email']);
        }
        return null;
    }

    public function getTableName() {
        return 'user';
    }

    public function __toString() {
        return "User [id={$this->id}, firstname={$this->firstname}, lastname={$this->lastname}, email={$this->email}]";
    }
}
