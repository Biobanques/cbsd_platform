<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'title'); ?>
            <?php echo $form->textField($model, 'title'); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-6">
            <?php echo CHtml::submitButton('Rechercher'); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->