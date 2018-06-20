<?php Yii::app()->clientScript->registerScript('form_question', "
$(document).ready(function() {
    $(window).scrollTop($('#questionnaire-form').offset().top).scrollLeft($('#questionnaire-form').offset().left);
    if($('.classname').prop('id')=='test')
    {
        $('#updateQuestionForm').modal();
        var valueIdQuestionGroup = $('#test').val();
        $('#QuestionForm_idQuestionGroup').selectmenu('refresh').val(valueIdQuestionGroup).attr('selected', 'selected');
    }
});
"); ?>

<h3 align="center"><?php echo Yii::t('administration', 'bloc') . $model->title; ?></h3>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'questionnaire-form',
        'enableAjaxValidation' => false,
    ));
    ?>
    <br>
    <div>
        <?php echo $questionnaire->renderTabbedGroupEditMode(Yii::app()->language); ?>
    </div>
    <?php $this->endWidget(); ?>

</div>

<hr />

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'updateQuestion-form',
    'enableAjaxValidation' => false,
        ));
?>
<div id="updateQuestionForm"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1 class="modal-title"><?php echo Yii::t('administration', 'forAddQuestion') ?></h1>
            </div>
            <div class="modal-body">
                <div class="prefs-form">
                    <?php echo $this->renderPartial('_form_question', array('questionForm' => $questionForm)); ?>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"  role="button"><?php echo Yii::t('button', 'cancel'); ?></button>
                    </div>
                    <div class="btn-group btn-delete hidden" role="group">
                        <button type="button" id="delImage" class="btn btn-default btn-hover-red" data-dismiss="modal"  role="button">Delete</button>
                    </div>
                    <div class="btn-group" role="group">
                        <?php echo CHtml::submitButton(Yii::t('button', 'saveBtn'), array('name' => 'updateForm', 'class' => 'btn btn-primary')); ?>
                    </div>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php $this->endWidget(); ?>