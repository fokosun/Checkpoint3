<?php
/**
 * Created by Florence Okosun.
 * Project: Checkpoint Three
 * Date: 11/3/2015
 * Time: 3:48 PM
 */

namespace Florence;

use Exception;

class RecordDoesNotExistException extends Exception
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
    * @return Exception Message
    */
    public function getExceptionMessage()
    {
        return $this->message;
    }
}
