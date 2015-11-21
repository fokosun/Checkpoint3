<?php

namespace Florence;

use PDOException;
use Florence\User;
use Florence\Emoji;

class EmojiController
{
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

    public function create(User $user)
    {
        $connection = $this->getConnection();

        $emoji = new Emoji;

        $name = $emoji->getName();
        $char = $emoji->getChar();
        $keywords = $emoji->getKeywords();
        $category = $emoji->getCategory();
        $date_created = $emoji->getDateCreated();
        $date_modified = $emoji->getDateModified();
        $created_by = $emoji->getCreatedBy();

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

        public function update($id)
        {
            //update moji where id = $id
        }

        public function delete($id)
        {
            //delewherte moji where id = $id
        }
    }