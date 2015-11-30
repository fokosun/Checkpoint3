<?php
/**
* Emoji API
* This script provides a RESTful API interface for Emojis
* Author: Florence Okosun
*/

namespace Florence;

use Slim\Slim;
use Exception;
use PDOException;
use Florence\Emoji;
use Florence\Connection;

abstract class EmojiController
{
    /**
    * @var $className
    * @var $table
    * @return $table
    */
    public static function getTableName()
    {
        $className = explode('\\', get_called_class());
        $table = strtolower(end($className) .'s');

        return $table;
    }

    /**
    * fetches all records from the database
    * @return emojis
    */
    public static function getAll(Slim $app)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $connection = new Connection();

        try
        {
            $sql = "SELECT " . "*" . " FROM ". self::getTableName();
            $stmt = $connection->query($sql);
        } catch (PDOException $e) {
            return $e->getMessage();
        }

        $result = $stmt->fetchAll($connection::FETCH_CLASS);
        $result = json_encode($result);

        $response->body($result);
        return $response;
    }

    /**
    * insert instance data into the table
    */
    public static function create(Slim $app)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $connection = new Connection();

        $name       = $app->request->params('name');
        $emojiChar  = $app->request->params('emojiChar');
        $keywords   = $app->request->params('keywords');
        $category   = $app->request->params('category');
        $createdAt  = date('Y-m-d H:i:s');
        $updatedAt  = date('Y-m-d H:i:s');
        $createdBy  = $app->request->params('createdBy');

        try
        {
            $sql = "INSERT INTO " . self::getTableName() . "(name, emojiChar, keywords, category,
                createdAt, updatedAt, createdBy) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $connection->prepare($sql);

            $stmt->bindParam(1, $name);
            $stmt->bindParam(2, $emojiChar);
            $stmt->bindParam(3, $keywords);
            $stmt->bindParam(4, $category);
            $stmt->bindParam(5, $createdAt);
            $stmt->bindParam(6, $updatedAt);
            $stmt->bindParam(7, $createdBy);

            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $response->body(json_encode([
                  'status'  => 200,
                  'message' => 'Record created'
                ]));
            } else {
                $response->body(json_encode([
                  'status'  => 400,
                  'message' => 'Error processing request'
                ]));
            }

        } catch (PDOException $e) {
            return $e->getMessage();
        }

        return $response;
    }

    /**
    * @return response mixed
    */
    public static function find(Slim $app, $id) {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $connection = new Connection();

        try {
            $sql = "SELECT " . "*" . " FROM " . self::getTableName() . " WHERE id = " . $id;
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            $count = $stmt->rowCount();

            } catch(PDOException $e) {
                $response->body(json_encode(['message' => $e->getExceptionMessage()]));
                return $response;
            }

            if($count < 1) {
                $response->body(json_encode(['status' => 404, 'message' => 'Emoji not found']));
                return $response;
            }

            $result = $stmt->fetchAll($connection::FETCH_CLASS);
            $result = json_encode($result);

            $response->body($result);
        return $response;
    }

    /**
    * @return response mixed
    */
    public static function findBy(Slim $app, $field, $criteria) {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $connection = new Connection();

        try {
            $sql = "SELECT * FROM emojis WHERE $field = '$criteria'";
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            $count = $stmt->rowCount();

            } catch(PDOException $e) {
                $response->body(json_encode(['message' => $e->getExceptionMessage()]));
                return $response;
            }

            if($count < 1) {
                $response->body(json_encode(['status' => 404, 'message' => 'Emoji not found']));
                return $response;
            }

            $result = $stmt->fetchAll($connection::FETCH_CLASS);
            $result = json_encode($result);

            $response->body($result);
        return $response;
    }

    /**
    * @return response mixed
    */
    public static function update(Slim $app, $id)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $connection = new Connection();

        $name       = $app->request->params('name');
        $emojiChar  = $app->request->params('emojiChar');
        $category   = $app->request->params('category');
        $keywords   = $app->request->params('keywords');
        $updatedAt  = date('Y-m-d H:i:s');
        $createdBy  = 'admin';

        try {
            $sql= "UPDATE emojis SET name='$name', emojichar='$emojiChar',
            keywords='$keywords', category='$category', createdby='$createdBy'
            WHERE id='$id'";

            $stmt = $connection->prepare($sql);


            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $response->body(json_encode([
                  'status'  => 200,
                  'message' => 'Record updated'
                ]));
                return $response;
            } else {
                $response->body(json_encode(['status' => 400, 'message' => 'Bad request']));
                return $response;
            }

        } catch (PDOException $e) {
            $response->body(json_encode(['status' => 400,'message'=> $e->getMessage()]));
            return $response;
        }

        return $response;
    }

    public static function delete(Slim $app, $id)
    {
        $response = $app->response();
        $response->headers->set('Content-Type', 'application/json');

        $connection = new Connection();

        try {
            $sql = "DELETE" . " FROM " . self::getTableName()." WHERE id = ". $id;
            $stmt = $connection->prepare($sql);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $response->body(json_encode([
                  'status'  => 200,
                  'message' => 'Record Deleted successfully'
                ]));
            } else {
                $response->body(json_encode([
                  'status'  => 500,
                  'message' => 'Error processing request'
                ]));
            }

        } catch (PDOException $e) {
            return $e->getMessage();
        }

        return $response;
    }
}
