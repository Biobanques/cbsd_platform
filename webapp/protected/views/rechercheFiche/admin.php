<?php
Yii::app()->clientScript->registerScript('search', "
$('#selectDate').click(function(){
    if ($('#Answer_last_updated').val().length > 0) {
        $('#selectDate').attr('disabled',true);
    }
    return false;
});
");
?>
<div style="margin-left:20px;">
    <div class="myBreadcrumb">
        <div class="active"><?php echo Yii::t('common', 'queryAnonymous') ?></div>
        <div><?php echo Yii::t('common', 'queryFormulation') ?></div>
        <div><?php echo Yii::t('common', 'resultQuery') ?></div>
    </div>
</div>

<div class="search-form">
    <div class="wide form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'light_search-form',
            'action' => Yii::app()->createUrl('rechercheFiche/admin2'),
            'method' => 'post',
        ));
        ?>

        <div style="border:1px solid black;">
            
            <h4 style="margin-left:10px;"><u><b><?php echo "Requête portant sur :" ?></b></u></h4>

            <div class="row">
                <div class="col-lg-12">
                    <?php echo CHtml::label(Yii::t('common', 'restrictQuery'), 'Answer_type'); ?>
                    <?php echo $form->dropDownList($model, 'type', Questionnaire::model()->getNomsFiches()); ?>
                </div>
            </div>

            <div class ="row">
                <div class="col-lg-12">
                    <?php echo CHtml::label(Yii::t('common', 'restrictPeriod'), 'Answer_last_updated'); ?>
                    <?php echo $form->textField($model, 'last_updated', array("onfocus" => "datePicker(this.name)", 'placeholder' => 'Du')); ?>
                    <?php echo CHtml::textField('Answer[last_updated_to]', '', array("onfocus" => "datePicker(this.name)", 'placeholder' => 'Au')); ?>
                </div>
            </div>

            <p style="margin-left:10px; color:red;"><?php echo Yii::t('common', 'notRestrict'); ?></p>

        </div>

        <div class="row buttons">
            <div class="col-lg-7 col-lg-offset-7">
                <?php echo CHtml::submitButton(Yii::t('button', 'next'), array('id' => 'next', 'class' => 'btn btn-primary', 'onclick' => 'submit(); $("#loading_next").show();')); ?>
                <?php echo CHtml::image(Yii::app()->request->baseUrl . '/images/loading.gif', 'loading', array('id' => "loading_next", 'style' => "margin-left: 10px; margin-bottom:10px; display:none;")); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- search-form -->
</div>