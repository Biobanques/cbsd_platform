<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'user'); ?>
            <?php echo $form->textField($model, 'user', array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'ipAddress'); ?>
            <?php echo $form->textField($model, 'ipAddress', array('prompt' => '---', "multiple" => "multiple")); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'profil'); ?>
            <?php echo $form->textField($model, 'profil', array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>
            
        <div class="col-lg-6">
            <?php echo $form->label($model, 'connectionDate'); ?>
            <?php echo $form->textField($model, 'connectionDate', array('prompt' => '----', "onfocus"=>"datePicker(this.name)")); ?>
        </div>
    </div>
    
    <div class="row buttons">
        <div class="col-lg-7 col-lg-offset-7">
            <?php echo CHtml::submitButton(Yii::t('button', 'search'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
            <?php echo CHtml::resetButton(Yii::t('button', 'reset'), array('class' => 'btn btn-danger')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->