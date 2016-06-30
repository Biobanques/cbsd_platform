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
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'AuditTrail[stamp]',
                // additional javascript options for the date picker plugin
                'options' => array(
                    'showAnim' => 'fold',
                ),
                'htmlOptions' => array(
                    'style' => 'height:25px;'
                ),
                'language' => 'fr',
            ));
            ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'user_id'); ?>
            <?php echo $form->dropDownList($model, 'user_id', $model->getAllUsers(), array('prompt' => '---')); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-6">
            <?php echo CHtml::submitButton('Rechercher', array('name' => 'rechercher', 'class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
            <?php echo CHtml::resetButton('Réinitialiser', array('class' => 'btn btn-default', 'style' => 'margin-top: 8px; padding-bottom: 23px;')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->