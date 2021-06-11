<?php namespace SchultenMedia\Postmark\Models;

use Model;

/**
 * Model for logging postmark mails
 *
 * @package schultenmedia\basics
 * @author SchultenMedia Devs
 */
class Postmarklog extends Model
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'sm_postmark_logs';

    /**
     * @var array The attributes that aren't mass assignable.
     */
    protected $guarded = [];

    /**
     * @var array
     */
    public $dates = [];

    /**
     * @var array List of attribute names which are json encoded and decoded from the database.
     */
    protected $jsonable = ['metadata'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}
