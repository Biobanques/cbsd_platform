<?php Yii::app()->clientScript->registerScript('search', "
$(function() {
    $(window).scrollTop($('#titleBloc').offset().top).scrollLeft($('#titleBloc').offset().left);
});
"); ?>

<h3 id="titleBloc" align="center"><?php echo Yii::t('administration', 'bloc') . $model->title; ?></h3>

<hr />

<div>
    <?php echo $questionnaire->renderTabbedGroup(Yii::app()->language); ?>
</div>

<hr />

<div style="display:inline; margin:40%; width: 100px; ">
    <?php echo CHtml::link('Retour', array('questionBloc/admin'), array('class' => 'btn btn-primary')); ?>
</div>