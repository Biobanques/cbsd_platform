<h3 align="center">Formulaire <?php echo $model->name; ?></h3>
<p>Description: <?php echo $model->description; ?></p>
<?php
if ($model->last_modified != null && $model->last_modified != "") {
    echo "<p>Dernière mise à jour le: " . $model->getLastModified() . "</p>";
}
?>
<p>Crée par: <?php echo $model->creator; ?></p>
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
    <div class="panel-heading"><h4>Ajouter un bloc de questions</h4></div>
    <div class="panel-body">
        <?php
        echo $this->renderPartial('_form_question_bloc', array('questionBloc' => $questionBloc, 'model' => $model, 'questionGroup' => $questionGroup));
        ?>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading"><h4>Ajouter un onglet ou un groupe</h4></div>
    <div class="panel-body">
        <?php
        echo $this->renderPartial('_form_question_group', array('model' => $questionGroup));
        ?>
    </div>
</div>
<!--<div class="panel panel-primary">
    <div class="panel-heading"><h4>Ajouter un groupe de questions</h4></div>
    <div class="panel-body">
<?php
//        echo $this->renderPartial('_form_question_bloc', array('model' => $questionGroup));
?>
    </div>
</div>-->
<div class="panel panel-primary">
    <div class="panel-heading"><h4>Ajouter une question</h4></div>
    <div class="panel-body">
        <?php
        echo $this->renderPartial('_form_question', array('model' => $questionForm));
        ?>
    </div>
</div>
