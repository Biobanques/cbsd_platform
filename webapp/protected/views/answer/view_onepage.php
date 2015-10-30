<h1>Vue HTML du formulaire <?php echo $model->name; ?></h1>
<hr />

<?php
echo $model->renderHTML(Yii::app()->language);
?>

<div style="clear:both;"></div>

<div>
    <?php
    echo CHtml::link('Retour', array('answer/affichepatient', 'id' => $model->_id), array('class' => 'btn btn-primary', 'style' => 'margin-top: -15px;margin-left:20px;'));
    ?>
</div>
