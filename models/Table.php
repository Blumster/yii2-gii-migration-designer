<?php

namespace blumster\migration\models;

use yii\base\Model;

class Table extends Model
{
    /**
     * @var bool
     */
    public $isNewRecord = false;

    /**
     * @var string
     */
    public $name = null;

    /**
     * @var Column[]|array|null
     */
    public $columns = null;

    public function __construct($config = [])
    {
        parent::__construct($config);

        if (is_null($this->columns)) {
            $this->columns = [ new Column() ];
        }
    }

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
