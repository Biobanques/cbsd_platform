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
            echo CHtml::label('<span class="required" style="float:right; margin-left:5px">*</span>' . Yii::t('common', 'oldFormName'), 'old_name', array('class' => 'required'));
            echo CHtml::textfield('old_name', '', array("required" => "required", "readonly" => "readonly"));
            ?>
            <div id="result"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label('<span class="required" style="float:right; margin-left:5px">*</span>' . Yii::t('common', 'newFormName'), 'new_name');
            echo CHtml::textfield('new_name', '');
            ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label('<span class="required" style="float:right; margin-left:5px">*</span>Description', 'new_name');
            echo CHtml::textarea('new_description', Questionnaire::model()->getDescription($_GET['id']));
            ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->