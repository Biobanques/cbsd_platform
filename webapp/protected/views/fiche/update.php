
<h3 align="center">Formulaire <?php echo $model->name; ?></h3>
<p>Description: <?php echo $model->description; ?></p>
<hr />
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
    <div class="panel-heading"><h4>Pour ajouter une question</h4></div>
    <div class="panel-body">
        <?php
        echo $this->renderPartial('_form_question', array('model' => $questionForm));
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
