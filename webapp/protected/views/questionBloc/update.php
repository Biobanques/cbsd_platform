<h3 align="center"><?php echo Yii::t('common', 'bloc') . $model->title; ?></h3>

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

<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?php echo Yii::t('common', 'forAddQuestion') ?>
        </h3>
    </div>
    <div class="panel-body">
        <?php
        echo $this->renderPartial('_form_question', array('model' => $questionForm));
        ?>
    </div>
</div>
