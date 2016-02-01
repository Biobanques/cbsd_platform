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
<div>
    <?php
    echo $model->renderTabbedGroup(Yii::app()->language);
    ?>
</div>