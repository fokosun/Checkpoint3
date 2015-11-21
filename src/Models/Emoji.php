<?php

namespace Florence;

class Emoji
{
    protected $name;
    protected $char;
    protected $keywords;
    protected $category;
    protected $date_created;
    protected $date_modified;
    protected $created_by;

    public function __construct($name, $char, $keywords, $category, $date_created, $date_modified, $created_by)
    {
        $this->name = $name;
        $this->char = $char;
        $this->keywsrds = $keywords;
        $this->category = $category;
        $this->date_created = $date_created;
        $this->date_modified = $date_modified;
        $this->created_by = $created_by;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getChar()
    {
        return $this->char;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getDateCreated()
    {
        return $this->date_created;
    }

    public function getDateModified()
    {
        return $this->date_modified;
    }

    public function getCreatedBy()
    {
        return $this->created_by;
    }

}
