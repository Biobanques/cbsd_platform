<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'id'); ?>
            <?php echo $form->textField($model, 'id', array('size' => 11, 'maxlength' => 11)); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->label($model, 'old_value'); ?>
            <?php echo $form->textField($model, 'old_value', array('rows' => 6, 'cols' => 50)); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'new_value'); ?>
            <?php echo $form->textField($model, 'new_value', array('rows' => 6, 'cols' => 50)); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->label($model, 'action'); ?>
            <?php echo $form->dropDownList($model, 'action', $model->getActions(), array('prompt' => '---')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'model'); ?>
            <?php echo $form->textField($model, 'model', array('size' => 60, 'maxlength' => 255)); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->label($model, 'field'); ?>
            <?php echo $form->textField($model, 'field', array('size' => 60, 'maxlength' => 64)); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'stamp'); ?>
            <?php echo $form->textField($model, 'stamp', array("onfocus"=>"datePicker(this.name)")); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->label($model, 'user_id'); ?>
            <?php echo $form->dropDownList($model, 'user_id', $model->getAllUsers(), array('prompt' => '---')); ?>
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