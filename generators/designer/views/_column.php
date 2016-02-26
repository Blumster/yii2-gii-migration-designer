<?php

use blumster\migration\generators\designer\Generator;

use yii\bootstrap\Html;

/* @var yii\bootstrap\ActiveForm $form */
/* @var blumster\migration\models\Table $table */
/* @var blumster\migration\models\Column $column */
/* @var int $i */
/* @var int $c */

?>

<tr class="db-column columns-custom-width">
    <td>
        <button type="button" class="del-db-column btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
    </td>
    <td>
        <?php

        echo $form->field($column, "[{$i}][{$c}]name")->begin();
        echo Html::activeLabel($column, "[{$i}][{$c}]name");
        echo Html::activeTextInput($column, "[{$i}][{$c}]name", [ 'maxlength' => true, 'class' => 'form-control' ]);
        echo Html::error($column,"[{$i}][{$c}]name", [ 'class' => 'help-block' ]);
        echo $form->field($column, "[{$i}][{$c}]name")->end();

        ?>
    </td>
    <td>
        <?php

        echo $form->field($column, "[{$i}][{$c}]type")->begin();
        echo Html::activeLabel($column, "[{$i}][{$c}]type");
        echo Html::activeDropDownList($column, "[{$i}][{$c}]type", [ '' => '' ] + Generator::columnTypes(), [ 'maxlength' => true, 'class' => 'form-control' ]);
        echo Html::error($column,"[{$i}][{$c}]type", [ 'class' => 'help-block' ]);
        echo $form->field($column, "[{$i}][{$c}]type")->end();

        ?>
    </td>
</tr>
