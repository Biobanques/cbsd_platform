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
            <?php echo CHtml::textfield('old_onglet', '', array("required" => "required", 'readOnly' => true)); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo CHtml::label(Yii::t('common', 'newQuestionGroup'), 'new_onglet'); ?>
            <?php echo CHtml::textfield('new_onglet', '', array("required" => "required")); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->