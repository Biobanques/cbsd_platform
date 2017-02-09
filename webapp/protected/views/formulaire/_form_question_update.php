<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'updateQuestion-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label(Yii::t('common', 'currentLabel'), 'old_question');
            echo CHtml::dropDownList('old_question', '', $model->getArrayQuestions(), array("prompt" => "----", "required" => "required"));
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label(Yii::t('common', 'newLabel'), 'new_question');
            echo CHtml::textfield('new_question', '', array("required" => "required"));
            ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-1 col-lg-offset-10">
            <?php echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-primary', 'style' => 'padding-bottom: 23px;')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->