<?php

class MainPageOpinion extends Entity
{
    private $id;
    private $stars;
    private $comment;
    private $firstname;
    private $lastname;
    private $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function getStars()
    {
        return $this->stars;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function withId($id) {
        $this->id = $id;
        return $this;
    }

    public function withStars($stars)
    {
        $this->stars = $stars;
        return $this;
    }

    public function withComment($comment) {
        $this->comment = $comment;
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

    public function withCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    protected function getCreateQuery(...$criteria)
    {
        // TODO: Implement getCreateQuery() method.
    }

    protected function getReadQuery(...$criteria)
    {
        return 'SELECT o.id, o.stars, o.comment, u.firstname, u.lastname, o.created_at FROM opinion o JOIN user u ON o.owner_id = u.id WHERE o.stars >= 4';
    }

    protected function getUpdateQuery(...$criteria)
    {
        // TODO: Implement getUpdateQuery() method.
    }

    protected function getDeleteQuery(...$criteria)
    {
        // TODO: Implement getDeleteQuery() method.
    }

    public function fromResult($result)
    {
        $objects = [];

        while ($row = $result->fetch_assoc()) {
            $objects[] = (new MainPageOpinion())
                ->withId($row[ConstUtils::FIELD_LABEL_ID])
                ->withFirstname($row[ConstUtils::FIELD_LABEL_FIRSTNAME])
                ->withLastname($row[ConstUtils::FIELD_LABEL_LASTNAME])
                ->withCreatedAt($row[ConstUtils::FIELD_LABEL_CREATED_AT])
                ->withComment($row[ConstUtils::FIELD_LABEL_COMMENT]);

        }

        return empty($objects) ? null : $objects;
    }

    public function getTableName()
    {
        return 'opinion';
    }
}