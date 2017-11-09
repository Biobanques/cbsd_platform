<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'updateNameForm-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    
    <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label('<span class="required" style="float:right; margin-left:5px">*</span>' . Yii::t('common', 'oldBlockName'), 'old_block', array('class' => 'required'));
            echo CHtml::textfield('old_block', '', array("required" => "required", "readonly" => "readonly"));
            ?>
            <div id="result"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label('<span class="required" style="float:right; margin-left:5px">*</span>' . Yii::t('common', 'newBlockName'), 'new_block', array('class' => 'required'));
            echo CHtml::textfield('new_block', '', array("required" => "required"));
            ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->