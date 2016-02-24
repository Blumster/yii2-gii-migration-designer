<?php

use blumster\migration\DesignerAsset;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator blumster\migration\generators\designer\Generator */

DesignerAsset::register($this);

?>

<?= $form->field($generator, 'migrationName')->textInput() ?>

<?= $form->field($generator, 'indexFormat')->textInput() ?>

<?= $form->field($generator, 'foreignKeyFormat')->textInput() ?>

<?= $form->field($generator, 'migrationPath')->textInput() ?>

<?= $form->field($generator, 'db')->textInput() ?>

<?= $form->field($generator, 'baseClass')->textInput() ?>

<?= $form->field($generator, 'usePrefix')->checkbox() ?>

<?= $form->field($generator, 'safe')->checkbox() ?>
