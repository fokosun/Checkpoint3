<?php

namespace Florence;

use PDOException;
use Florence\Emoji;

class EmojiController
{

    public function __construct()
    {
        $this->emoji = new Emoji();
    }

    /**
    * get db connection
    */
    public function getConnection($connection = null)
    {
        if(is_null($connection))
        {
            return new DBConnection();
        }
    }

    public function create() //POST
    {
        $connection = $this->getConnection();

        $name = $this->emoji->getName();
        $char = $this->emoji->getChar();
        $keywords = $this->emoji->getKeywords();
        $category = $this->emoji->getCategory();
        $date_created = $this->emoji->getDateCreated();
        $date_modified = $this->emoji->getDateModified();
        $created_by = $this->emoji->getCreatedBy();

        $create = "INSERT INTO emojis(name,char,keywords,category,date_created,date_modified,created_by)
        VALUES (. " . $name . "," . $char . "," . $keywords . "," . $category . ","
            . $date_created . "," . $updated_at . ")";

        $stmt = $connection->prepare($create);
        try
        {
            $stmt->execute();
            $count = $stmt->rowCount();
            if($count < 1) {
                throw new RecordExistAlreadyException('Record exist already.');
            }
        } catch (RecordExistAlreadyException $e) {

        return $e->getExceptionMessage();
        } catch(PDOException $e) {

                return $e->getExceptionMessage();
        }
    }

    public function getAll()
    {
        // spits all emojis out
        // GET
    }

    public function getById($id)
    {
        //get emoji by id
        // GET
    }

    public function updateById($id)
    {
        //update emoji by id
        // PUT
    }

    public function delete($id)
    {
        //destroy the mother fucker
        // DELETE
    }
}
