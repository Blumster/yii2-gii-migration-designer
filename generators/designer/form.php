<?php

use blumster\migration\DesignerAsset;
use blumster\migration\models\Table;

use wbraganca\dynamicform\DynamicFormWidget;
use yii\bootstrap\Html;

/* @var yii\web\View $this */
/* @var yii\widgets\ActiveForm $form */
/* @var blumster\migration\generators\designer\Generator $generator */
/* @var blumster\migration\models\Table[] $modelsTable */

DesignerAsset::register($this);

if (!isset($modelsTable)) {
    $modelsTable = [ new Table() ];
}

?>

<?= $form->field($generator, 'migrationName')->textInput() ?>
<div class="row panel-body">
    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamic_tables',
        'widgetBody' => '.tables', // required: css class selector
        'widgetItem' => '.table', // required: css class
        'insertButton' => '.add-table', // css class
        'deleteButton' => '.del-table', // css class
        'model' => $modelsTable[0],
        'min' => 0,
        'formId' => 'migration-designer-form',
        'formFields' => [
            'name',
        ],
    ]); ?>

    <h4>Tables</h4>
    <table class="table table-bordered">
        <thead>
            <tr class="active">
                <td></td>
                <td><label class="control-label">Data</label></td>
                <td><label class="control-label">Columns</label></td>
            </tr>
        </thead>

        <tbody class="tables">
            <?php foreach ($modelsTable as $i => $table): ?>
                <?= $this->render('views/_table', [ 'form' => $form, 'table' => $table, 'i' => $i ]); ?>
            <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="active"><button type="button" class="add-table btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button></td>
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

<?php /* $form->field($generator, 'asd')->dropDownList(Generator::columnTypes()) */ ?>
