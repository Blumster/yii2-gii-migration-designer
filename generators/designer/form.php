<?php

use blumster\migration\DesignerAsset;
use blumster\migration\models\ForeignKey;
use blumster\migration\models\Index;
use blumster\migration\models\Table;

use wbraganca\dynamicform\DynamicFormWidget;

/* @var yii\web\View $this */
/* @var yii\widgets\ActiveForm $form */
/* @var blumster\migration\generators\designer\Generator $generator */

DesignerAsset::register($this);

if (empty($generator->tables)) {
    $generator->tables = [ new Table() ];
}

if (empty($generator->indices)) {
    $generator->indices = [ new Index() ];
}

if (empty($generator->foreignKeys)) {
    $generator->foreignKeys = [ new ForeignKey() ];
}

?>

<?= $form->field($generator, 'migrationName')->textInput() ?>

<div class="row panel-body">
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamic_tables',
        'widgetBody' => '.db-tables',
        'widgetItem' => '.db-table',
        'insertButton' => '.add-db-table',
        'deleteButton' => '.del-db-table',
        'model' => $generator->tables[0],
        'min' => 0,
        'formId' => $form->id,
        'formFields' => [
            'name'
        ]
    ]); ?>
    <div class="table-collapse">
        <h4>Tables</h4>
        <table class="table table-bordered db-tables-table">
            <thead>
                <tr class="active">
                    <td></td>
                    <td><label class="control-label">Data</label></td>
                    <td><label class="control-label">Columns</label></td>
                </tr>
            </thead>

            <tbody class="db-tables">
                <?php foreach ($generator->tables as $i => $table): ?>
                    <?= $this->render('views/_table', [ 'form' => $form, 'table' => $table, 'i' => $i ]) ?>
                <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="active"><button type="button" class="add-db-table btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php DynamicFormWidget::end() ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamic_indices',
        'widgetBody' => '.db-indices',
        'widgetItem' => '.db-index',
        'insertButton' => '.add-db-index',
        'deleteButton' => '.del-db-index',
        'model' => $generator->indices[0],
        'min' => 0,
        'formId' => $form->id,
        'formFields' => [
            'name'
        ]
    ]); ?>

    <h4>Indices</h4>
    <table class="table table-bordered db-indices-table">
        <thead>
            <tr class="active">
                <td></td>
                <td><label class="control-label">Data</label></td>
            </tr>
        </thead>
        <tbody class="db-indices">
        <?php foreach ($generator->indices as $i => $index): ?>
            <?= $this->render('views/_index', [ 'form' => $form, 'index' => $index, 'i' => $i ]) ?>
        <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="active">
                    <button type="button" class="add-db-index btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                </td>
            </tr>
        </tfoot>
    </table>

    <?php DynamicFormWidget::end() ?>

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamic_foreign_keys',
        'widgetBody' => '.db-foreign-keys',
        'widgetItem' => '.db-foreign-key',
        'insertButton' => '.add-db-foreign-key',
        'deleteButton' => '.del-db-foreign-key',
        'model' => $generator->foreignKeys[0],
        'min' => 0,
        'formId' => $form->id,
        'formFields' => [
            'name'
        ]
    ]); ?>

    <h4>Foreign Keys</h4>
    <table class="table table-bordered db-foreign-keys-table">
        <thead>
        <tr class="active">
            <td></td>
            <td><label class="control-label">Data</label></td>
        </tr>
        </thead>
        <tbody class="db-foreign-keys">
        <?php foreach ($generator->foreignKeys as $i => $foreignKey): ?>
            <?= $this->render('views/_foreign_key', [ 'form' => $form, 'foreignKey' => $foreignKey, 'i' => $i ]) ?>
        <?php endforeach ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2" class="active">
                <button type="button" class="add-db-foreign-key btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
            </td>
        </tr>
        </tfoot>
    </table>

    <?php DynamicFormWidget::end() ?>
</div>

<?= $form->field($generator, 'indexFormat')->textInput() ?>

<?= $form->field($generator, 'foreignKeyFormat')->textInput() ?>

<?= $form->field($generator, 'migrationPath')->textInput() ?>

<?= $form->field($generator, 'db')->textInput() ?>

<?= $form->field($generator, 'baseClass')->textInput() ?>

<?= $form->field($generator, 'usePrefix')->checkbox() ?>

<?= $form->field($generator, 'safe')->checkbox() ?>
