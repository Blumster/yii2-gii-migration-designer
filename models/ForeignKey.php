<?php

namespace blumster\migration\models;

use yii\base\Model;

class ForeignKey extends Model
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
     * @var array
     */
    public $columns = null;

    /**
     * @var string
     */
    public $refTable = null;

    /**
     * @var array
     */
    public $refColumns = null;

    /**
     * @var string
     */
    public $delete = null;

    /**
     * @var string
     */
    public $update = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'table', 'columns', 'refTable', 'refColumns' ], 'required' ],
            [ [ 'table', 'refTable', 'delete', 'update' ], 'string' ],
            [ [ 'columns', 'refColumns' ], 'each', 'rule' => 'string' ],
            [ [ 'delete', 'update' ], 'in', 'range' => [ 'RESTRICT', 'CASCADE', 'SET NULL', 'NO ACTION', 'SET DEFAULT' ], 'skipOnEmpty' => true, ]
        ];
    }

    public function load($data, $formName = null)
    {
        if (!parent::load($data, $formName)) {
            return false;
        }

        $this->columns = explode(';', $this->columns);
        $this->columns = explode(';', $this->columns);

        if (in_array($this->delete, [ 'RESTRAINT', 'NO ACTION' ])) {
            $this->delete = null;
        }

        if (in_array($this->update, [ 'RESTRAINT', 'NO ACTION' ])) {
            $this->update = null;
        }

        return true;
    }

    public static function constraintOptions()
    {
        return [
            'RESTRICT',
            'CASCADE',
            'SET NULL',
            'NO ACTION',
            'SET DEFAULT'
        ];
    }

    public static function stickyAttributes()
    {
        return [];
    }

    public static function hints()
    {
        return [
            'table' => '',
            'delete' => '',
            'update' => ''
        ];
    }

    public static function autoCompleteData()
    {
        return [];
    }
}
