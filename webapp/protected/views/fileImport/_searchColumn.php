<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($modelColumn, 'currentColumn'); ?>
            <?php echo $form->textField($modelColumn, 'currentColumn', array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($modelColumn, 'newColumn'); ?>
            <?php echo $form->textField($modelColumn, 'newColumn', array('prompt' => '---', "multiple" => "multiple")); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($modelColumn, 'type'); ?>
            <?php echo $form->textField($modelColumn, 'type', array('prompt' => '----', "multiple" => "multiple")); ?>
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