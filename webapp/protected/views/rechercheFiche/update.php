<?php
Yii::app()->clientScript->registerScript('form_question', "
$('.question-label').on('dblclick', function(event) {
    $('#updateQuestion').modal();
    $('.col-lg-12 #old_question').val($(this).attr('id'));
});

$('#QuestionBlocForm_title').change(function(){
    var e = document.getElementById('QuestionBlocForm_title').value;
    if (e !== '') {
        $('#titleBloc').show();
    } else {
        $('#titleBloc').hide();
    }
});
");
Yii::app()->clientScript->registercss('input', "
input[type=\"text\"] {
height: 25px;
}
");
?>

<h3 align="center"><?php echo Yii::t('common', 'form') . $model->name; ?></h3>
<p><b>Description: </b><?php echo $model->description; ?></p>

<p><?php echo "<b>" . Yii::t('common', 'createdBy') . "</b>: " . $model->creator; ?></p>
<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'action' => $this->createUrl('update', array('id' => $model->_id)),
        'enableAjaxValidation' => false,
    ));
    ?>
    <br>
    <div>
        <?php
        echo $model->renderTabbedGroup(Yii::app()->language, $model);
        ?>
    </div>
    <hr />
        <div style="display:inline; margin-left: 35%; width: 100px; ">
        <?php
        echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-primary'));
        ?>
    </div>
    <?php
    $this->endWidget();
    ?>
</div>
<hr/>

