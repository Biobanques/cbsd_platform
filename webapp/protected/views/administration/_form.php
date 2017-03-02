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
        <?php echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-primary')); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>