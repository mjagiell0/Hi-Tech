<?php

class Category extends Entity
{
    private $id;
    private $name;
    private $imagePath;
    private $sectionId;
    private $sectionName;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSectionId()
    {
        return $this->sectionId;
    }

    public function getImagePath()
    {
        return $this->imagePath;
    }

    public function getSectionName()
    {
        return $this->sectionName;
    }

    public function withId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function withName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function withSectionId($section_id)
    {
        $this->sectionId = $section_id;
        return $this;
    }

    public function withImagePath($image_path)
    {
        $this->imagePath = $image_path;
        return $this;
    }

    public function withSectionName($section_name)
    {
        $this->sectionName = $section_name;
        return $this;
    }

    protected function getCreateQuery(...$criteria): string
    {
        if (!is_string($criteria[0]) || !is_int($criteria[1]) || count($criteria) != 2) {
            throw new InvalidArgumentException("Insufficient criteria for CREATE operation.");
        }
        return "INSERT INTO Category (name, section_id) VALUES (?, ?)";
    }

    protected function getReadQuery(...$criteria): string
    {
        if (count($criteria) != 1
            || intval($criteria[0]) == 0) {
            throw new InvalidArgumentException("Insufficient criteria for READ operation.");
        }
        return "SELECT c.id, c.name, c.section_id, ci.path image_name, s.name section_name
                FROM Category c
                JOIN section s ON c.section_id = s.id
                LEFT JOIN category_image ci ON ci.category_id = c.id
                WHERE c.section_id = ?";
    }

    protected function getUpdateQuery(...$criteria)
    {
        // TODO: Implement getUpdateQuery() method.
    }

    protected function getDeleteQuery(...$criteria)
    {
        // TODO: Implement getDeleteQuery() method.
    }

    public function fromRow($row)
    {
        return (new Category())
            ->withId($row[ConstUtils::FIELD_LABEL_ID])
            ->withName($row[ConstUtils::FIELD_LABEL_NAME])
            ->withSectionId($row[ConstUtils::FIELD_LABEL_SECTION_ID])
            ->withImagePath(is_null($row[ConstUtils::FIELD_LABEL_IMAGE_NAME]) ? 'default.png' : $row[ConstUtils::FIELD_LABEL_IMAGE_NAME])
            ->withSectionName($row[ConstUtils::FIELD_LABEL_SECTION_NAME]);
    }

    public function getTableName()
    {
        return "category";
    }

    public function prepareToDisplay()
    {
        $this->name = htmlspecialchars($this->getName());
        $this->imagePath = htmlspecialchars($this->getImagePath());
        $this->sectionName = htmlspecialchars($this->getSectionName());
    }
}