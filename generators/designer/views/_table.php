<?php

use wbraganca\dynamicform\DynamicFormWidget;

use yii\bootstrap\Html;

/* @var yii\bootstrap\ActiveForm $form */
/* @var blumster\migration\models\Table $table */
/* @var int $i */

?>

<tr class="db-table">
    <td>
        <button type="button" class="del-db-table btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
    </td>
    <td>
        <?php

        echo $form->field($table, "[{$i}]name")->begin();
        echo Html::activeLabel($table, "[{$i}]name");
        echo Html::activeTextInput($table, "[{$i}]name", [ 'maxlength' => true, 'class' => 'form-control' ]);
        echo Html::error($table,"[{$i}]name", [ 'class' => 'help-block' ]);
        echo $form->field($table, "[{$i}]name")->end();

        ?>
    </td>
    <td>
        <?php DynamicFormWidget::begin([
            'widgetContainer' => 'dynamic_columns',
            'widgetBody' => '.db-columns',
            'widgetItem' => '.db-column',
            'insertButton' => '.add-db-column',
            'deleteButton' => '.del-db-column',
            'model' => $table->columns[0],
            'min' => 1,
            'formId' => $form->id,
            'formFields' => [
                'name',
                'type'
            ]
        ]); ?>

        <table class="table table-bordered db-columns-table">
            <tbody class="db-columns">
            <?php foreach ($table->columns as $c => $column): ?>
                <?= $this->render('_column', [ 'form' => $form, 'table' => $table, 'i' => $i, 'column' => $column, 'c' => $c ]) ?>
            <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="active"><button type="button" class="add-db-column btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button></td>
                </tr>
            </tfoot>
        </table>

        <?php DynamicFormWidget::end() ?>
    </td>
</tr>
