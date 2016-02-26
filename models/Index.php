<?php

namespace blumster\migration\models;

use yii\base\Model;

class Index extends Model
{
    /**
     * @var bool
     */
    public $isNewRecord = true;

    /**
     * @var string
     */
    public $table = null;

    /**
     * @var array|string
     */
    public $columns = null;

    /**
     * @var bool
     */
    public $unique = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'table', 'columns', 'unique' ], 'required' ],
            [ [ 'table' ], 'string' ],
            [ [ 'columns' ], 'each', 'rule' => 'string' ],
            [ [ 'unique' ], 'boolean' ]
        ];
    }

    public static function stickyAttributes()
    {
        return [];
    }

    public function attributeHints()
    {
        return [
            'table' => 'The target table of the index',
            'unique' => 'Determines, if the index should be unique.',
            'columns' => 'The name of the columns this index should contain. Separate with semicolon (;), if you want multiple.'
        ];
    }

    public function hints()
    {
        return static::attributeHints();
    }

    public static function autoCompleteData()
    {
        return [];
    }

    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            return false;
        }

        $this->columns = explode(';', $this->columns);
        return true;
    }
}
