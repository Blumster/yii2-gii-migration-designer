<?php

namespace blumster\migration\generators\designer;

use blumster\migration\models\Column;
use blumster\migration\models\ForeignKey;
use blumster\migration\models\Index;
use blumster\migration\models\Table;

use Yii;

use yii\base\Exception;
use yii\gii\CodeFile;
use yii\helpers\ArrayHelper;

class Generator extends \yii\gii\Generator
{
    /**
     * @var string
     */
    public $indexFormat = '{{table}}_index_{{index}}';

    /**
     * @var string
     */
    public $foreignKeyFormat = '{{table}}_FK_{{ref_table}}';

    /**
     * @var string
     */
    public $migrationPath = '@app/migrations';

    /**
     * @var string
     */
    public $baseClass = 'yii\db\Migration';

    /**
     * @var string
     */
    public $db = 'db';

    /**
     * @var bool
     */
    public $usePrefix = true;

    /**
     * @var bool
     */
    public $safe = true;

    /**
     * @var string
     */
    public $migrationName = null;

    /**
     * @var \blumster\migration\models\Table[]
     */
    public $tables = null;

    /**
     * @var \blumster\migration\models\Index[]
     */
    public $indices = null;

    /**
     * @var \blumster\migration\models\ForeignKey[]
     */
    public $foreignKeys = null;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [ [ 'indexFormat', 'foreignKeyFormat', 'migrationPath', 'db', 'baseClass', 'migrationName' ], 'string' ],
            [ [ 'migrationName' ], 'required' ],
            [ [ 'usePrefix', 'safe' ], 'boolean' ],
            [ [ 'indexFormat', 'foreignKeyFormat', 'migrationPath', 'db', 'baseClass', 'usePrefix', 'safe' ], 'safe' ],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'indexFormat' => 'Index Format',
            'foreignKeyFormat' => 'Foreign Key Format',
            'migrationPath' => 'Migration Path',
            'db' => 'Database Connection Id',
            'baseClass' => 'Base Class',
            'usePrefix' => 'Use Table Prefix',
            'safe' => 'Use Safe Functions',
            'migrationName' => 'Migration Name',
        ]);
    }

    public function hints()
    {
        return ArrayHelper::merge(parent::attributeHints(), [
            'indexFormat' => 'The format for naming the indices. <br />Keys: <ul><li>{{table}}: the table the index is created on</li><li>{{index}}: the unique name of the index, made up from the column names</li></ul>',
            'foreignKeyFormat' => 'The format for naming the foreign keys. <br />Keys: <ul><li>{{table}}: the table the foreign key is created on</li><li>{{ref_table}}: the unique name of the foreign key, made up from the referenced table and column name</li></ul>',
            'db' => 'The name of the Database component',
            'baseClass' => 'The is the base class of the new migration. It should be a fully qualified namespaced class name.',
            'usePrefix' => 'This indicates whether the table name returned by the generated ActiveRecord class should consider the <code>tablePrefix</code> setting of the DB connection. For example, if the table name is <code>tbl_post</code> and <code>tablePrefix=tbl_</code>, the ActiveRecord class will return the table name as <code>{{%post}}</code>.',
            'safe' => 'If checked, the migration will use the <code>safeUp(); safeDown();</code> functions, instead of <code>up(); down();</code> ones.',
            'migrationName' => 'Short name for the migration.',
        ]);
    }

    public function stickyAttributes()
    {
        return ArrayHelper::merge(parent::stickyAttributes(), [
            'indexFormat',
            'foreignKeyFormat',
            'migrationPath',
            'db',
            'baseClass'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return [ 'migration.php' ];
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Migration Designer';
    }
    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This tool lets you design your database, then generate the migrations for it.';
    }

    /**
     * Returns the array of the available column types for the dropdown list.
     *
     * @return array the available column types
     */
    public static function columnTypes()
    {
        return [
            'bigInteger'    => 'Bigint',
            'bigPrimaryKey' => 'BigPK',
            'binary'        => 'Binary',
            'boolean'       => 'Boolean',
            'date'          => 'Date',
            'dateTime'      => 'Datetime',
            'decimal'       => 'Decimal',
            'double'        => 'Double',
            'float'         => 'Float',
            'integer'       => 'Integer',
            'money'         => 'Money',
            'primaryKey'    => 'PK',
            'smallInteger'  => 'Smallint',
            'string'        => 'String',
            'text'          => 'Text',
            'time'          => 'Time',
            'timestamp'     => 'Timestamp',
        ];
    }

    /**
     * Returns the default value's type for every schema.
     *
     * @return array the types for the schema
     */
    public static function defaultValueTypes()
    {
        return [
            'bigInteger'    => 'int',
            'bigPrimaryKey' => 'int',
            'binary'        => 'string',
            'boolean'       => 'int',
            'date'          => 'string',
            'dateTime'      => 'string',
            'decimal'       => 'int',
            'double'        => 'int',
            'float'         => 'int',
            'integer'       => 'int',
            'money'         => 'int',
            'primaryKey'    => 'int',
            'smallInteger'  => 'int',
            'string'        => 'string',
            'text'          => 'string',
            'time'          => 'string',
            'timestamp'     => 'string',
        ];
    }

    /**
     * Generates the code based on the current user input and the specified code template files.
     * This is the main method that child classes should implement.
     * Please refer to [[\yii\gii\generators\controller\Generator::generate()]] as an example
     * on how to implement this method.
     * @return CodeFile[] a list of code files to be created.
     */
    public function generate()
    {
        if (isset($_POST['generate'])) {
            $migrationName = 'm' . gmdate('ymd_His') . '_' . $this->migrationName;
        } else {
            $migrationName = 'm{date_time}_' . $this->migrationName;
        }

        $file = new CodeFile(Yii::getAlias($this->migrationPath) . '/' . $migrationName . '.php', $this->render('migration.php', [
            'safe' => $this->safe,
            'migrationName' => $migrationName,
            'baseClass' => $this->baseClass,
            'tables' => $this->tables,
            'indices' => $this->indices,
            'foreignKeys' => $this->foreignKeys
        ]));
        $file->id = 'migration_file';

        return [ $file ];
    }

    /**
     * @param \blumster\migration\models\Index $index
     * @return string
     */
    public function generateIndexName($index)
    {
        $name = '';

        foreach ($index->columns as $col) {
            if ($name != '') {
                $name .= '_';
            }

            $name .= $col;
        }

        return $this->formatIndex($index->table, $name);
    }

    /**
     * @param \blumster\migration\models\ForeignKey $fKey
     * @return string
     */
    public function generateForeignKeyName($fKey)
    {
        return $this->formatForeignKey($fKey->table, $fKey->refTable);
    }

    /**
     * Formats the index's name, based on the current given format.
     *
     * @param string $table the table's name
     * @param string $name the given name of the index
     * @return string the formatted name
     */
    public function formatIndex($table, $name)
    {
        return preg_replace('/{{index}}/', $name, preg_replace('/{{table}}/', $table, $this->indexFormat));
    }

    /**
     * Formats the foreign key's name, based on the current given format.
     *
     * @param string $table the table's name
     * @param string $refTable the referenced table's name
     * @return string the formatted name
     */
    public function formatForeignKey($table, $refTable)
    {
        return preg_replace('/{{ref_table}}/', $refTable, preg_replace('/{{table}}/', $table, $this->foreignKeyFormat));
    }

    /**
     * Converts the element of the array
     * @param array $value the source array
     * @param string $char the element separator character
     * @param bool $list if true, array characters ([, ]) will be omitted
     * @return string the generated string
     */
    public static function processArray($value, $char = '\'', $list = false) {
        if (!is_array($value)) {
            return $char . $value . $char;
        }

        return (!$list ? '[ ' : '') . $char . implode($char . ', ' . $char, $value) . $char . (!$list ? ' ]' : '');
    }

    /**
     * Escapes a string's apostrophes with a \ character.
     *
     * @param string $string the source string
     * @return string the escaped string
     */
    public static function escapeApostrophe($string)
    {
        return str_replace('\'', '\\\'', $string);
    }

    /**
     * Generates schema definition calls based on the column's data.
     *
     * @param \blumster\migration\models\Column $column
     * @return string the generated definition
     * @throws Exception
     */
    public static function generateSchema($column)
    {
        if (is_null($column) || empty($column->name) || empty($column->schema)) {
            throw new Exception('Invalid column!');
        }

        $schema = '$this';
        $baseType = null;
        $defaultValueTypes = static::defaultValueTypes();

        foreach ($column['schema'] as $type => $param) {
            if (is_int($type) && is_string($param)) {
                $type = $param;
                $param = null;
            }

            if (is_null($baseType)) {
                $baseType = $type;
            }

            $char = '';
            if (in_array($type, [ 'check', 'defaultExpression' ])) {
                $char = '\'';
            } elseif ($type == 'defaultValue' && isset($defaultValueTypes[$baseType]) && $defaultValueTypes[$baseType] == 'string') {
                if ($baseType == 'timestamp' && $param == '0') {
                    $char = '';
                } else {
                    $char = '\'';
                }
            }

            $schema .= '->' . $type . '(' . static::processArray($param, $char, true) . ')';
        }

        return $schema;
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        if (!parent::load($data, $formName)) {
            return false;
        }

        /*
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        // */

        if (isset($data['Table'])) {
            $this->tables = static::createMultiple(Table::className());
            Table::loadMultiple($this->tables, $data);

            $loadData = [];

            for ($i = 0; $i < count($this->tables); ++$i) {
                $loadData['Column'] = $data['Column'][$i];

                $this->tables[$i]->columns = static::createMultiple(Column::className(), [], $loadData);
                $this->tables[$i]->isNewRecord = false;
                Column::loadMultiple($this->tables[$i]->columns, $loadData);
            }
        } else {
            $this->tables = [ new Table() ];
        }

        if (isset($data['Index'])) {
            $this->indices = static::createMultiple(Index::className());

            Index::loadMultiple($this->indices, $data);

            foreach ($this->indices as $index) {
                $index->isNewRecord = false;
            }
        } else {
            $this->indices = [ new Index() ];
        }

        if (isset($data['ForeignKey'])) {
            $this->foreignKeys = static::createMultiple(ForeignKey::className());

            ForeignKey::loadMultiple($this->foreignKeys, $data);

            foreach ($this->foreignKeys as $fKey) {
                $fKey->isNewRecord = false;
            }
        } else {
            $this->foreignKeys = [ new ForeignKey() ];
        }
        /*
        echo '<pre>';
        print_r($this);
        echo '</pre>';
        exit;
        // */

        return true;
    }

    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @param array $data
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = [], $data = null)
    {
        /* @var \yii\base\Model $model */
        $model    = new $modelClass;
        $formName = $model->formName();
        // added $data=null to function arguments
        // modified the following line to accept new argument
        $post     = empty($data) ? Yii::$app->request->post($formName) : $data[$formName];
        $models   = [];

        if (! empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }

    public static $migrationDataExample = [
        'test_table1' => [
            'columns' => [
                'id' => 'bigint',
                'name' => [
                    'type' => 'string',
                    'null' => false,
                    'default' => ''
                ]
            ],
            'primaryKey' => 'id',
            'indices' => [
                'name',
                'name' => 'unique',
                'id' => 'normal',
                [
                    'columns' => [
                        'id',
                        'name'
                    ],
                    'type' => 'unique'
                ]

            ]
        ],
        'test_table2' => [
            'columns' => [
                'id' => [
                    'type' => 'bigint',
                    'ai' => true
                ],
                'id2' => 'bigint'
            ],
            'primary_key' => [
                'id', 'id2'
            ]
        ]
    ];

    public static $processedData = [
        'tables' => [
            [
                'name' => 'teszt1',
                'composite_key' => [
                    'id', 'id2'
                ],
                'columns' => [
                    [
                        'name' => 'id',
                        'schema' => [
                            'string' => [ 20 ],
                            'unique',
                            'defaultValue' => 'asd'
                        ]
                    ],
                    [
                        'name' => 'id2',
                        'schema' => [
                            'integer' => [ 10 ],
                            'notNull',
                            'unique',
                            'defaultValue' => 1000,
                            'unsigned'
                        ]
                    ]
                ]
            ],
            [
                'name' => 'teszt2',
                'columns' => [
                    [
                        'name' => 'id',
                        'schema' => [
                            'string' => [ 30 ]
                        ],
                        'primary' => true
                    ]
                ]
            ]
        ],
        'indices' => [
            [ 'name' => 'ind1', 'table' => 'teszt1', 'column' => 'id' ],
            [ 'name' => 'ind2', 'table' => 'teszt2', 'column' => [ 'id', 'bd' ], 'unique' => true ]
        ],
        'foreignKeys' => [
            [ 'name' => 'fk1', 'table' => 'teszt1', 'column' => 'id', 'refTable' => 'teszt2', 'refColumn' => 'id', 'update' => 'CASCADE' ],
            [ 'name' => 'fk2', 'table' => 'teszt1', 'column' => [ 'id', 'bd' ], 'refTable' => 'teszt2', 'refColumn' => [ 'id', 'bd' ], 'delete' => 'CASCADE', 'update' => 'CASCADE' ]
        ]
    ];
}
