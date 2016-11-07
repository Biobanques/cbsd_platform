<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'name'); ?>
            <?php echo $form->dropDownList($model, 'name', Questionnaire::model()->getNomsFiches(), array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-6">
            <?php echo CHtml::submitButton(Yii::t('common', 'search'), array('name' => 'rechercher', 'class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
            <?php echo CHtml::resetButton(Yii::t('common', 'reset'), array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->