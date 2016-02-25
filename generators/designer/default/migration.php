<?php

use \blumster\migration\generators\designer\Generator;

/* @var Generator $generator */
/* @var string $migrationName */
/* @var bool $safe */
/* @var string $baseClass */
/* @var array $processedData */

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
<?php foreach ($processedData['tables'] as $table): ?>
<?php $primaryKey = null; ?>
        $this->createTable('<?= $table['name'] ?>', [
<?php foreach ($table['columns'] as $column): ?>
            '<?= $column['name'] ?>' => <?= Generator::generateSchema($column) ?>,
<?php endforeach ?>
<?php if (!is_null($primaryKey) && isset($column['composite_key']) && is_array($column['composite_key'])): ?>

            'PRIMARY KEY (<?= Generator::processArray($column['composite_key'], '`', true) ?>)'
<?php endif ?>
        ]);

<?php endforeach ?>
<?php foreach ($processedData['indices'] as $index): ?>
        $this->createIndex('<?= $index['name'] ?>', '<?= $index['table'] ?>', <?= Generator::processArray($index['column']) ?><?= isset($index['unique']) && $index['unique'] ? ', true' : '' ?>);
<?php endforeach ?>
<?php if (!empty($processedData['indices']) && !empty($processedData['foreignKeys'])): ?>

<?php endif ?>
<?php foreach ($processedData['foreignKeys'] as $fKey): ?>
        $this->addForeignKey('<?= $fKey['name'] ?>', '<?= $fKey['table'] ?>', <?= Generator::processArray($fKey['column']) ?>, '<?= $fKey['refTable'] ?>', <?= Generator::processArray($fKey['refColumn']) ?><?= isset($fKey['delete']) ? ", '{$fKey['delete']}'" : '' ?><?= isset($fKey['update']) ? ((isset($fKey['delete']) ? '' : ', null') . ", '{$fKey['update']}'") : '' ?>);
<?php endforeach ?>
    }

    public function <?= $safe ? 'safeDown' : 'down' ?>()
    {
<?php foreach ($processedData['tables'] as $table): ?>
        $this->dropTable('<?= $table['name'] ?>');
<?php endforeach ?>
<?php if (!empty($processedData['tables']) && !empty($processedData['indices'])): ?>

<?php endif ?>
<?php foreach ($processedData['indices'] as $index): ?>
        $this->dropIndex('<?= $index['name'] ?>', '<?= $index['table'] ?>');
<?php endforeach ?>
<?php if (!empty($processedData['indices']) && !empty($processedData['foreignKeys'])): ?>

<?php endif ?>
<?php foreach ($processedData['foreignKeys'] as $fKey): ?>
        $this->dropForeignKey('<?= $fKey['name'] ?>', '<?= $fKey['table'] ?>');
<?php endforeach ?>
    }
}
