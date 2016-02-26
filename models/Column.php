<?php

namespace blumster\migration\models;

use yii\base\Model;

class Column extends Model
{
    /**
     * @var bool
     */
    public $isNewRecord = true;

    /**
     * @var string
     */
    public $name = null;

    /**
     * @var string
     */
    public $type = null;

    /**
     * @var array
     */
    public $schema = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'name', 'type' ], 'string' ],
            [ [ 'name', 'type' ], 'required' ]
        ];
    }

    public function load($data, $formName = null)
    {
        if (!parent::load($data, $formName)) {
            return false;
        }

        $this->schema = [ $this->type ];

        return true;
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
