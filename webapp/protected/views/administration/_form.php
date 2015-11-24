<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'droit-form',
        //'action' => Yii::app()->createUrl('/administration/index'),
        'enableAjaxValidation' => false,
    ));
    ?>
    <?php echo $form->errorSummary($model); ?>

    <?php
    echo $form->checkBoxList($model, 'role', Droits::model()->getActions(), array('labelOptions' => array('style' => 'display:inline')));
    ?>
    <br>
    <div class="row buttons" style="float:left;">
        <?php echo CHtml::submitButton('Enregistrer'); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>