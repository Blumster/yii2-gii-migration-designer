<?php

namespace blumster\migration\generators\designer;

use Yii;

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
     * @var string|null
     */
    public $migrationName = null;

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
            'tables' => static::$afterProcessData['tables'],
            'indices' => static::$afterProcessData['indices'],
            'foreignKeys' => static::$afterProcessData['foreignKeys']
        ]));
        $file->id = 'migration_file';

        return [ $file ];
    }

    public static function processArray($value, $char = '\'', $list = false) {
        if (!is_array($value)) {
            return $char . $value . $char;
        }

        return (!$list ? '[ ' : '') . $char . implode($char . ', ' . $char, $value) . $char . (!$list ? ' ]' : '');
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

    public static $afterProcessData = [
        'tables' => [
            [
                'name' => 'teszt1',
                'columns' => [
                    [ 'name' => 'id', 'type' => '', 'primary' => true ],
                    [ 'name' => 'id2', 'type' => '', 'primary' => true ]
                ]
            ],
            [
                'name' => 'teszt2',
                'columns' => [
                    [ 'name' => 'id', 'type' => '' ]
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
