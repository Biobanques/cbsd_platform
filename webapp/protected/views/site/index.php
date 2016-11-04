<?php
/* @var $this SiteController */
$this->pageTitle = Yii::app()->name;
?>

<div class="row-fluid">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <?php
        $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
            'heading' => Yii::t('common', 'welcomeTo') . CHtml::encode(Yii::app()->name),
        ));
        ?>
        <?php echo Yii::t('common', 'cbsdDescription') ?>
        <?php $this->endWidget(); ?>
    </div>
</div>
