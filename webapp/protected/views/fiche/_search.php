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
            <?php echo $form->dropDownList($model, 'name', Answer::model()->getNomsFiches(), array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>
        <div class="col-lg-6">
            <?php echo $form->label($model, 'user'); ?>
            <?php echo $form->dropDownList($model, 'user', Answer::model()->getNamesUsers(), array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo $form->label($model, 'last_updated'); ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'name' => 'Answer[last_updated]',
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
    </div>

    <div class="row buttons">
        <div class="col-lg-7 col-lg-offset-7">
            <?php echo CHtml::submitButton(Yii::t('button', 'search'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
            <?php echo CHtml::resetButton(Yii::t('button', 'reset'), array('class' => 'btn btn-danger')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->