<?php
/**
 * Created by Florence Okosun.
 * Project: Checkpoint Three
 * Date: 11/4/2015
 * Time: 4:07 PM
 */

namespace Florence;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Emoji extends Eloquent
{
    /**
    *  The attributes that are mass assignable.
    * @var array
    * */
    protected $fillable = ['name','emojichar','keywords','category','created_by'];
}
