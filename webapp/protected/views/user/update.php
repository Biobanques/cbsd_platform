<h1><?php echo Yii::t('administration', 'userUpdate') . " " . $model->login; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>