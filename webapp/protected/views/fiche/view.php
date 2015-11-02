<h3 align="center">Formulaire <?php echo $model->name; ?></h3>
<p>Description: <?php echo $model->description; ?></p>
<hr />
<?php
echo CHtml::link('Vue format HTML', array('fiche/viewOnePage', 'id' => $model->_id));
?>
<div style="margin-top: -15px; text-align:right;">
    <?php
    $img = CHtml::image(Yii::app()->request->baseUrl . '/images/page_white_acrobat.png', 'export as pdf');
    echo CHtml::link('Exporter au format PDF' . $img, array('answer/exportPDF', 'id' => $model->_id), array());
    ?>
</div>

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error')); ?>
<div>
    <?php
    echo $model->renderTabbedGroup(Yii::app()->language);
    ?>
</div>

