<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;
echo $_SESSION['htmlAvailable'];
?>

<div class="jumbotron">
<div class="container">    
    
      <h1><?php echo Yii::t('common', 'welcomeTo') . CHtml::encode(Yii::app()->name); ?></h1>
      <p><?php echo Yii::t('common', 'cbsdDescription') ?></p>
    </div>
</div>