<?php
Yii::app()->clientScript->registerScript('form_question', "
$('.question-label').on('click', function(event) {
    $('#updateQuestion').modal();
    $('.col-lg-12 #old_question').val($(this).attr('id'));
    $('option:not(:selected)').hide();
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
<?php
if ($model->last_modified != null && $model->last_modified != "") {
    $q = Questionnaire::model()->findByPk(new MongoId($_GET['id']));
    echo "<p><b>" . Yii::t('common', 'lastModifiedDate') . ": </b>" . date('d/m/Y', strtotime($q->last_modified['date'])) . "</p>";
}
?>
<p><?php echo "<b>" . Yii::t('common', 'createdBy') . "</b>: " . $model->creator; ?></p>
<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <br>
    <div>
        <?php
        echo $model->renderTabbedGroupEditMode(Yii::app()->language);
        ?>
    </div>
    <?php
    $this->endWidget();
    ?>
</div>
<hr/>
<div class="panel-group" id="accordion">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                <?php echo Yii::t('common', 'forAddQuestion') ?>
            </h3>
        </div>
        <div id="collapse1" class="panel-collapse collapse in">
            <div class="panel-body">
                <?php
                echo $this->renderPartial('_form_question', array('model' => $questionForm));
                ?>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                <?php echo Yii::t('common', 'forAddQuestionBlock') ?>
            </h3>
        </div>
        <div id="collapse2" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                echo $this->renderPartial('_form_question_bloc', array('questionBloc' => $questionBloc, 'model' => $model, 'questionGroup' => $questionGroup));
                ?>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                <?php echo Yii::t('common', 'forAddTab') ?>
            </h3>
        </div>
        <div id="collapse3" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                echo $this->renderPartial('_form_question_group', array('model' => $questionGroup));
                ?>
            </div>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                <?php echo Yii::t('common', 'forModifyTab') ?>
            </h3>
        </div>
        <div id="collapse4" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                echo $this->renderPartial('_form_question_group_update', array('questionGroup' => $questionGroup));
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'updateQuestion-form',
    'enableAjaxValidation' => false,
        ));
?>
<div id="updateQuestion"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1 class="modal-title"><?php echo Yii::t('common', 'forModifyQuestion') ?></h1>
            </div>
            <div class="modal-body">
                <div class="prefs-form">
                    <?php echo $this->renderPartial('_form_question_update', array('model' => $questionForm)); ?>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  role="button"><?php echo Yii::t('common', 'cancel'); ?></button>
                    </div>
                    <div class="btn-group btn-delete hidden" role="group">
                        <button type="button" id="delImage" class="btn btn-default btn-hover-red" data-dismiss="modal"  role="button">Delete</button>
                    </div>
                    <div class="btn-group" role="group">
                        <?php echo CHtml::submitButton(Yii::t('common', 'saveBtn'), array('class' => 'btn btn-primary')); ?>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php $this->endWidget(); ?>