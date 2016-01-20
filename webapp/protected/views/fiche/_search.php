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
            <?php echo $form->textField($model, 'name'); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'user'); ?>
            <?php echo $form->textField($model, 'user'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
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
        <div class="col-lg-6">
            <?php echo CHtml::submitButton('Rechercher'); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->