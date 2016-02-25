<?php

use wbraganca\dynamicform\DynamicFormWidget;

use yii\bootstrap\Html;

/* @var yii\bootstrap\ActiveForm $form */
/* @var blumster\migration\models\Table $table */
/* @var int $i */

?>
<tr class="table custom-width">
    <td>
        <button type="button" class="del-table btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
    </td>
    <td>
        <?php
            echo $form->field($table, "[{$i}]name")->begin();
            echo Html::activeLabel($table, "[{$i}]name");
            echo Html::activeTextInput($table, "[{$i}]name", [ 'maxlength' => true, 'class' => 'form-control' ]);
            echo Html::error($table,"[{$i}]name", ['class' => 'help-block']);
            echo $form->field($table, "[{$i}]name")->end();
        ?>
    </td>
    <td>
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamic_columns',
            'widgetBody' => '.columns', // required: css class selector
            'widgetItem' => '.column', // required: css class
            'insertButton' => '.add-column', // css class
            'deleteButton' => '.del-column', // css class
            'model' => $table->columns[0],
            'min' => 1,
            'formId' => 'migration-designer-form',
            'formFields' => [
                'name',
                'type'
            ],
        ]); ?>

        <table class="table table-bordered">
            <thead>
            <tr class="active">
                <td></td>
                <td><label class="control-label">Name</label></td>
                <td><label class="control-label">Type</label></td>
            </tr>
            </thead>
            <tbody class="columns">
                <?php foreach ($table->columns as $c => $column): ?>
                    <?= $this->render('_column', [ 'form' => $form, 'table' => $table, 'i' => $i, 'column' => $column, 'c' => $c ]); ?>
                <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="active"><button type="button" class="add-column btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button></td>
                </tr>
            </tfoot>
        </table>

        <?php DynamicFormWidget::end() ?>
    </td>
</tr>
