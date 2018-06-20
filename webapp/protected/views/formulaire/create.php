<?php Yii::app()->clientScript->registerScript('search', "
$(function() {
    $(window).scrollTop($('#questionnaire-form').offset().top).scrollLeft($('#questionnaire-form').offset().left);
});
"); ?>

<h1><?php echo Yii::t('administration', 'createForm') ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model));