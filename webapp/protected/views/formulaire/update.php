<?php
Yii::app()->clientScript->registerScript('form_question', "
$(document).ready(function() {
    $(window).scrollTop($('#nameForm').offset().top).scrollLeft($('#nameForm').offset().left);
    if($('.classname').prop('id')=='test')
    {
        $('#updateQuestionForm').modal();
        var valueIdQuestionGroup = $('#test').val();
        $('#QuestionForm_idQuestionGroup').selectmenu('refresh').val(valueIdQuestionGroup).attr('selected', 'selected');
    }
});

$('.updateForm').on('click', function(event) {
    $('#updateQuestion').modal();
    $('.col-lg-12 #old_question').val($(this).attr('id'));
});

$('.updateTabForm').on('click', function(event) {
    $('#updateTabFormUpdate').modal();
    var period_val = $('.nav > .active > a > input').val();
    $('.col-lg-12 #old_onglet').val(period_val);
});

$('#nameForm').on('click', function(event) {
    $('#updateNameForm').modal();
    var str = $('h3').text();
    var replaceObj = {
        Formulaire : '',
        Form : ''
    };
    str = str.replace(/Formulaire|Form/gi, function(matched){
        return replaceObj[matched];
    });
    $('.col-lg-12 #old_name').val(str.trim());
});

$('#tabForm').on('click', function(event) {
    $('#updateForm').modal();
    var str = $('h3').text();
    var replaceObj = {
        Formulaire : '',
        Form : ''
    };
    str = str.replace(/Formulaire|Form/gi, function(matched){
        return replaceObj[matched];
    });
    $('.col-lg-12 #old_name').val(str.trim());
});

$('#createTabForm').on('click', function(event) {
    $('#updateForm').modal('hide');
    $('#updateTabForm').modal();
});

$('#addExistingTabForm').on('click', function(event) {
    $('#updateForm').modal('hide');
    $('#updateBlockForm').modal();
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

<h3 align="center" id="nameForm"><?php echo Yii::t('administration', 'form') . $model->name; ?>&nbsp;<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl . '/images/update.png')); ?></h3>
<p><b>Description: </b><?php echo $model->description; ?></p>
<?php
if ($model->last_modified != null && $model->last_modified != "") {
    $q = Questionnaire::model()->findByPk(new MongoId($_GET['id']));
    echo "<p><b>" . Yii::t('common', 'lastModifiedDate') . ": </b>" . date(CommonTools::FRENCH_SHORT_DATE_FORMAT, strtotime($q->last_modified['date'])) . "</p>";
}
?>
<p><?php echo "<b>" . Yii::t('common', 'createdBy') . "</b>: " . $model->creator; ?></p>
<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
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
                <h1 class="modal-title"><?php echo Yii::t('administration', 'forModifyQuestion') ?></h1>
            </div>
            <div class="modal-body">
                <div class="prefs-form">
                    <?php echo $this->renderPartial('_form_question_update', array('questionForm' => $questionForm)); ?>
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

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'updateNameForm-form',
    'enableAjaxValidation' => false,
        ));
?>
<div id="updateNameForm"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1 class="modal-title"><?php echo Yii::t('administration', 'forModifyDescription') ?></h1>
            </div>
            <div class="modal-body">
                <div class="prefs-form">
                    <?php echo $this->renderPartial('_form_name_update', array('model' => $model)); ?>
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

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'updateTab-form',
    'enableAjaxValidation' => false,
        ));
?>
<div id="updateTabForm"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1 class="modal-title"><?php echo Yii::t('administration', 'forAddTab') ?></h1>
            </div>
            <div class="modal-body">
                <div class="prefs-form">
                    <?php echo $this->renderPartial('_form_question_group', array('model' => $questionGroup)); ?>
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

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'updateTab-form',
    'enableAjaxValidation' => false,
        ));
?>
<div id="updateTabFormUpdate"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1 class="modal-title"><?php echo Yii::t('administration', 'createTab') ?></h1>
            </div>
            <div class="modal-body">
                <div class="prefs-form">
                    <?php echo $this->renderPartial('_form_question_group_update', array('questionGroup' => $questionGroup)); ?>
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

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'updateBlock-form',
    'enableAjaxValidation' => false,
        ));
?>
<div id="updateBlockForm"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1 class="modal-title"><?php echo Yii::t('administration', 'forAddTab') ?></h1>
            </div>
            <div class="modal-body">
                <div class="prefs-form">
                    <?php echo $this->renderPartial('_form_question_bloc', array('questionBloc' => $questionBloc, 'model' => $model, 'questionGroup' => $questionGroup)); ?>
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

<div id="updateForm"  class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1 class="modal-title"><?php echo Yii::t('administration', 'forAddTab') ?></h1>
            </div>
            <div class="modal-body">
                <div class="prefs-form">
                    Voulez-vous :
                    <?php echo CHtml::Button('Créer un onglet', array('id' => 'createTabForm', 'class' => 'btn btn-primary')); ?>
                    <?php echo CHtml::Button('Ajouter un onglet existant', array('id' => 'addExistingTabForm', 'class' => 'btn btn-primary')); ?>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->