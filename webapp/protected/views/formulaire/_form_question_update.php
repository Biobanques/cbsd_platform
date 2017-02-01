<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'updateQuestion-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <div class="row">
        <?php
        echo CHtml::label(Yii::t('common', 'currentLabel'), 'old_question');
        echo CHtml::dropDownList('old_question', '', $model->getArrayQuestions(), array("prompt" => "----", "required" => "required"));
        ?>
    </div>
    <div class="row">
    <?php
        echo CHtml::label(Yii::t('common', 'newLabel'), 'new_question');
        echo CHtml::textfield('new_question', '', array("required" => "required"));
    ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->