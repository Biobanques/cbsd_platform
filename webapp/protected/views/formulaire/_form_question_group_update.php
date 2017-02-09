<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'updateOnglet-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <div class="row">
        <div class="col-lg-12">
            <?php echo CHtml::label(Yii::t('common', 'currentQuestionGroup'), 'old_onglet'); ?>
            <?php echo CHtml::dropDownList('old_onglet', '', $questionGroup->getOnglets(), array("prompt" => "----", "required" => "required")); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo CHtml::label(Yii::t('common', 'newQuestionGroup'), 'new_onglet'); ?>
            <?php echo CHtml::textfield('new_onglet', '', array("required" => "required")); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-1 col-lg-offset-10">
            <?php echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-primary', 'style' => 'padding-bottom: 23px;')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->