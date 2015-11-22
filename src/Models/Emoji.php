<?php

namespace Florence;

class Emoji
{
    /**
    * @var string $name
    * The emoji name
    */
    private $name;

    /**
    * @var string $emojiChar
    * emoji hex-code
    */
    private $char_;

    /**
    * @var array $keywords
    * tags to describe the emoji e.g [people, laugh]
    */
    private $keywords;

    /**
    * @var string $category
    * emoji category e.g Nature, Travel & places
    */
    private $category;

    /**
    * @var $date_created
    */
    private $date_created;

    /**
    * @var $date_modified
    */
    private $date_modified;

    /**
    * @var int $created_by
    * The user id of the creator
    */
    private $created_by;

    /**
    * @param $name
    * @param $char_
    * @param $keywords
    * @param $category
    * emoji instance constructor
    */
    public function __construct($name, $emojiChar, $keywords, $category)
    {
        $this->name = $name;
        $this->emojiChar = $emojiChar;
        $this->keywsrds = $keywords;
        $this->category = $category;
    }

    /**
    * @return string
    * get the name of the emoji
    */
    public function getName()
    {
        return $this->name;
    }

    /**
    * @return string
    * get the emoji hex-code
    */
    public function getEmojiChar()
    {
        return $this->emojiChar;
    }

    /**
    * @param $keywords
    * Set the keywords
    */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
    * @param $date_created
    */
    public function setDateCreated($dateCreated)
    {
        $this->date_created = $dateCreated;
    }

    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
    * @param $date_modified
    */
    public function setDateModified($dateModified)
    {
        $this->date_modified = $dateModified;
    }

    public function getDateModified()
    {
        return $this->date_modified;
    }

    /**
    * @param $createdBy
    */
    public function setCreatedBy($createdBy)
    {
        $this->created_by = $createdBy;
    }

    public function getCreatedBy()
    {
       return $this->created_by;
    }

}
