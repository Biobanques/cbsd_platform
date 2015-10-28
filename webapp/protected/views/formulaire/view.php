<hr />
<h3 align="center">Formulaire <?php echo $model->name; ?></h3>
<p>Description: <?php echo $model->description; ?></p>
<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
<div>
    <?php
    echo $model->renderTabbedGroup(Yii::app()->language);
    ?>
</div>
<?php ?>
    
