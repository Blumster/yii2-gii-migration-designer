<?php

use \blumster\migration\generators\designer\Generator;

/* @var Generator $generator */
/* @var string $migrationName */
/* @var bool $safe */
/* @var string $baseClass */
/* @var array $tables */
/* @var array $indices */
/* @var array $foreignKeys */

$parts = explode('\\', $baseClass);
$baseClassName = array_pop($parts);

echo "<?php\n";

?>

use yii\db\Schema;
use <?= $baseClass ?>;

class <?= $migrationName ?> extends <?= $baseClassName . "\n" ?>
{
    public function <?= $safe ? 'safeUp' : 'up' ?>()
    {
<?php foreach ($tables as $table): ?>
<?php $primaryKey = null; ?>
        $this->createTable('<?= $table['name'] ?>', [
<?php foreach ($table['columns'] as $column): ?>
<?php if (isset($column['primary']) && $column['primary']) {
    if (is_null($primaryKey)) {
        $primaryKey = $column['name'];
    } elseif (is_string($primaryKey)) {
        $primaryKey = [ $primaryKey, $column['name'] ];
    } else {
        $primaryKey[] = $column['name'];
    }
} ?>
            '<?= $column['name'] ?>' => '<?= $column['type'] /* TODO */?>',
<?php endforeach ?>
<?php if (!is_null($primaryKey)): ?>

            PRIMARY KEY (<?= Generator::processArray($primaryKey, '`', true) ?>)
<?php endif ?>
        ]);

<?php endforeach ?>
<?php foreach ($indices as $index): ?>
        $this->createIndex('<?= $index['name'] ?>', '<?= $index['table'] ?>', <?= Generator::processArray($index['column']) ?><?= isset($index['unique']) && $index['unique'] ? ', true' : '' ?>);
<?php endforeach ?>
<?php if (!empty($indices) && !empty($foreignKeys)): ?>

<?php endif ?>
<?php foreach ($foreignKeys as $fKey): ?>
        $this->addForeignKey('<?= $fKey['name'] ?>', '<?= $fKey['table'] ?>', <?= Generator::processArray($fKey['column']) ?>, '<?= $fKey['refTable'] ?>', <?= Generator::processArray($fKey['refColumn']) ?><?= isset($fKey['delete']) ? ", '{$fKey['delete']}'" : '' ?><?= isset($fKey['update']) ? ((isset($fKey['delete']) ? '' : ', null') . ", '{$fKey['update']}'") : '' ?>);
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
        $this->dropForeignKey('<?= $fKey['name'] ?>', '<?= $fKey['table'] ?>');
<?php endforeach ?>
    }
}
