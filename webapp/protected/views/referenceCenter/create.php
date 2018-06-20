<?php Yii::app()->clientScript->registerScript('search', "
$(function() {
    $(window).scrollTop($('.note').offset().top).scrollLeft($('.note').offset().left);
});
"); ?>

<h1><?php echo Yii::t('administration', 'createCenter') ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>