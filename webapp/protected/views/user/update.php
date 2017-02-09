<h1><?php echo Yii::t('common', 'userUpdate') . " " . $model->login; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>