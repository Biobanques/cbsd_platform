<h3 align="center"><?php echo Yii::t('common', 'bloc') . $model->title; ?></h3>
<hr />

<div>
    <?php
    echo $questionnaire->renderTabbedGroup(Yii::app()->language);
    ?>
</div>
<div style="display:inline; margin:40%; width: 100px; ">
    <?php
    echo CHtml::link('Retour', array('questionBloc/admin'), array('class' => 'btn btn-default'));
    ?>
</div>