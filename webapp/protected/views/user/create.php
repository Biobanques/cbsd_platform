<?php Yii::app()->clientScript->registerScript('search', "
$(function() {
    $(window).scrollTop($('#createUser').offset().top).scrollLeft($('#createUser').offset().left);
});
"); ?>

<h1 id="createUser"><?php echo Yii::t('administration', 'createUser') ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>