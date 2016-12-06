<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'updateOnglet-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <div class="row">
        <?php
        echo CHtml::label(Yii::t('common', 'currentQuestionGroup'), 'old_onglet');
        echo CHtml::dropDownList(Yii::t('common', 'currentQuestionGroup'), '', $questionGroup->getOnglets(), array("prompt" => "----", "required" => "required"));
        ?>
    </div>
    <div class="row">
    <?php
        echo CHtml::label(Yii::t('common', 'newQuestionGroup'), '');
        echo CHtml::textfield(Yii::t('common', 'newQuestionGroup'), '', array("required" => "required"));
    ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->