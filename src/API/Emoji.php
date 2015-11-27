<?php
/**
 * Created by Florence Okosun.
 * Project: Checkpoint Three
 * Date: 11/4/2015
 * Time: 4:07 PM
 */

namespace Florence;

class Emoji extends EmojiController
{
    private $name;
    private $emojiChar;
    private $keywords;
    private $category;
    private $createdAt;
    private $updatedAt;
    private $createdBy;

    /**
    * Create an Emoji instance
    */
    public function __construct($name, $emojiChar, $keywords, $category)
     {
        $this->name     = $name;
        $this->emojiChar = $emojiChar;
        $this->category = $category;
        $this->keywords = $keywords;
     }

    /**
    * Set the date the emoji was created
    */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
    * set the date the emoji was updated on.
    */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
    * Set the id of the user that created the emoji
    */
     public function setCreatedBy($createdBy)
     {
         $this->createdBy = $createdBy;
     }

    /**
    * @param $keywords
    */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
    * return the name of the emoji
    */
    public function getName()
    {
       return $this->name;
    }

    /**
    * return the hex-code representing the emoji
    */
    public function getEmojiChar()
    {
       return $this->emojiChar;
    }

    /**
    * return the category the emoji belongs to
    */
    public function getCategory()
    {
        return $this->category;
    }

    /**
    * get the keywords that describe the emoji
    */
    public function getKeywords()
    {
       return $this->keywords;
    }

    /**
    * get the date at which the emoji was created
    */
    public function getCreatedAt()
    {
       return $this->createdAt;
    }

    /**
    * get the date the emoji was updated on.
    */
    public function getUpdatedAt()
    {
       return $this->updatedAt;
    }

    /**
    * get the id of the user that created the emoji
    */
    public function getCreatedBy()
    {
       return $this->createdBy;
    }
}
