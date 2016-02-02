<h3 align="center">Bloc <?php echo $model->title; ?></h3>
<hr />

<div>
    <?php
    echo $questionnaire->renderTabbedGroup(Yii::app()->language);
    ?>
</div>