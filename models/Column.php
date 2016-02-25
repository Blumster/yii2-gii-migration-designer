<?php

namespace blumster\migration\models;

use yii\base\Model;

class Column extends Model
{
    public $isNewRecord = false;

    /**
     * @var string
     */
    public $name = null;

    /**
     * @var string
     */
    public $type = null;

    public static function stickyAttributes()
    {
        return [];
    }

    public static function hints()
    {
        return [];
    }

    public static function autoCompleteData()
    {
        return [];
    }
}
