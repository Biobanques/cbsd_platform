<h3 align="center">Bloc <?php echo $model->title; ?></h3>

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
        echo $questionnaire->renderTabbedGroupEditMode(Yii::app()->language);
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
