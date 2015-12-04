<?php
/**
 * Created by Florence Okosun.
 * Project: Checkpoint Three
 * Date: 11/4/2015
 * Time: 4:07 PM
 */

namespace Florence;

use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent
{
    /**
    * @var array
    */
    protected $fillable = ['username','password', 'token', 'token_expire'];
}
