
<h3 align="center">Formulaire <?php echo $model->id; ?></h3>
<p>Description: Formulaire <?php echo $model->description; ?></p>
<hr />

<br><bR>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'myModalContributors')); ?>

<div class="modal-header">
    <a class="close" data-dismiss="modal">&times;</a>
    <h4>Contributors</h4>
</div>

<div class="modal-body span5" >
    <?php echo $model->renderContributors(); ?>
</div>

<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Close',
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    ));
    ?>
</div>

<?php $this->endWidget(); ?>

<?php
$this->widget('bootstrap.widgets.TbButton', array(
    'label' => 'Contributors',
    'type' => 'primary',
    'htmlOptions' => array(
        'data-toggle' => 'modal',
        'data-target' => '#myModalContributors',
    ),
));
?>
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
    <div class="panel-heading"><h4>Ajouter une question</h4></div>
    <div class="panel-body">
        <?php
        echo $this->renderPartial('_form_question', array('model' => $questionForm));
        ?>
    </div>
</div>
