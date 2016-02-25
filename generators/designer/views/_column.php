<?php

use blumster\migration\generators\designer\Generator;

/* @var yii\bootstrap\ActiveForm $form */
/* @var blumster\migration\models\Table $table */
/* @var blumster\migration\models\Column $column */
/* @var int $i */
/* @var int $c */

?>

<tr class="column custom-width">
    <td></td>
    <td><?= $form->field($column, 'name')->textInput() ?></td>
    <td><?= $form->field($column, 'type')->dropDownList([ '' => '' ] + Generator::columnTypes()) ?></td>
</tr>
