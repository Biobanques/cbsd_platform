<?php
Yii::app()->clientScript->registerScript('form_question', "
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

<h3 align="center">Formulaire <?php echo $model->name; ?></h3>
<p><b>Description: </b><?php echo $model->description; ?></p>
<?php
if ($model->last_modified != null && $model->last_modified != "") {
    echo "<p><b>Dernière mise à jour le: </b>" . $model->getLastModified() . "</p>";
}
?>
<p><b>Crée par: </b><?php echo $model->creator; ?></p>
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

<div class="panel-group" id="accordion">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                Pour ajouter une rubrique
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
                Pour ajouter un bloc de questions déjà existant
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
                Pour ajouter un onglet
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
                Pour modifier un onglet
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
    
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapse5">
                Pour modifier une question
            </h3>
        </div>
        <div id="collapse5" class="panel-collapse collapse">
            <div class="panel-body">
                <?php
                echo $this->renderPartial('_form_question_update', array('model' => $questionForm));
                ?>
            </div>
        </div>
    </div>
</div>