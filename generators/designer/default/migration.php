<?php

use \blumster\migration\generators\designer\Generator;

/* @var Generator $generator */
/* @var string $migrationName */
/* @var bool $safe */
/* @var string $baseClass */
/* @var \blumster\migration\models\Table[] $tables */
/* @var \blumster\migration\models\Index[] $indices */
/* @var \blumster\migration\models\ForeignKey[] $foreignKeys */

$parts = explode('\\', $baseClass);
$baseClassName = array_pop($parts);

if (count($tables) == 1 && is_null($tables[0]->name)) {
    $tables = [];
}

if (count($indices) == 1 && is_null($indices[0]->table)) {
    $indices = [];
}

if (count($foreignKeys) == 1 && is_null($foreignKeys[0]->table)) {
    $foreignKeys = [];
}

echo "<?php\n";

?>

use yii\db\Schema;
use <?= $baseClass ?>;

class <?= $migrationName ?> extends <?= $baseClassName . "\n" ?>
{
    public function <?= $safe ? 'safeUp' : 'up' ?>()
    {
<?php foreach ($tables as $table): ?>
        $this->createTable('<?= $table->name ?>', [
<?php foreach ($table->columns as $column): ?>
            '<?= $column->name ?>' => <?= Generator::generateSchema($column) ?>,
<?php endforeach ?>
<?php if (!is_null($table->compositeKey) && is_array($table->compositeKey)): ?>

            'PRIMARY KEY (<?= Generator::processArray($table->compositeKey, '`', true) ?>)'
<?php endif ?>
        ]);

<?php endforeach ?>
<?php foreach ($indices as $index): ?>
        $this->createIndex('<?= $generator->generateIndexName($index) ?>', '<?= $index->table ?>', <?= Generator::processArray($index->columns) ?><?= isset($index->unique) && $index->unique ? ', true' : '' ?>);
<?php endforeach ?>
<?php if (!empty($indices) && !empty($foreignKeys)): ?>

<?php endif ?>
<?php foreach ($foreignKeys as $fKey): ?>
        $this->addForeignKey('<?= $generator->generateForeignKeyName($fKey) ?>', '<?= $fKey->table ?>', <?= Generator::processArray($fKey->columns) ?>, '<?= $fKey->refTable ?>', <?= Generator::processArray($fKey->refColumns) ?><?= isset($fKey->delete) ? ", '{$fKey->delete}'" : '' ?><?= isset($fKey->update) ? ((isset($fKey->delete) ? '' : ', null') . ", '{$fKey->update}'") : '' ?>);
<?php endforeach ?>
    }

    public function <?= $safe ? 'safeDown' : 'down' ?>()
    {
<?php foreach ($tables as $table): ?>
        $this->dropTable('<?= $table['name'] ?>');
<?php endforeach ?>
<?php if (!empty($tables) && !empty($indices)): ?>

<?php endif ?>
<?php foreach ($indices as $index): ?>
        $this->dropIndex('<?= $index['name'] ?>', '<?= $index['table'] ?>');
<?php endforeach ?>
<?php if (!empty($indices) && !empty($foreignKeys)): ?>

<?php endif ?>
<?php foreach ($foreignKeys as $fKey): ?>
        $this->dropForeignKey('<?= $fKey->name ?>', '<?= $fKey->table ?>');
<?php endforeach ?>
    }
}
