<?php
Yii::app()->clientScript->registerScript('form_question', "
$(document).ready(function() {
    $('.row input[type=\"text\"],select').each(function() {
         $(this).val('');
    });
 });
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

<div class="panel panel-primary">
    <div class="panel-heading"><h4>Pour ajouter une rubrique</h4></div>
    <div class="panel-body">
        <?php
        echo $this->renderPartial('_form_question', array('model' => $questionForm));
        ?>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><h4>Pour ajouter un bloc de questions déjà existant</h4></div>
    <div class="panel-body">
        <?php
        echo $this->renderPartial('_form_question_bloc', array('questionBloc' => $questionBloc, 'model' => $model, 'questionGroup' => $questionGroup));
        ?>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><h4>Pour ajouter un onglet</h4></div>
    <div class="panel-body">
        <?php
        echo $this->renderPartial('_form_question_group', array('model' => $questionGroup));
        ?>
    </div>
</div>
