<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'updateQuestion-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    
    <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label('<span class="required" style="float:right; margin-left:5px">*</span>' . Yii::t('common', 'currentLabel'), 'old_question', array('class' => 'required'));
            echo CHtml::textfield('old_question', '', array("required" => "required", "readonly" => "readonly"));
            ?>
            <div id="result"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label(Yii::t('common', 'newLabel'), 'new_question');
            echo CHtml::textfield('new_question', '');
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label('Type', 'new_type');
            echo CHtml::dropDownList('new_type', '', $questionForm->getArrayTypes(), array('prompt' => '----'));
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label(Yii::t('common', 'values'), 'new_type');
            echo CHtml::textfield('new_values', '');
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label(Yii::t('common', 'help'), 'new_help');
            echo CHtml::textfield('new_help', '');
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label(Yii::t('common', 'defaultValue'), 'new_defaultValue');
            echo CHtml::textfield('new_defaultValue', '');
            ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->