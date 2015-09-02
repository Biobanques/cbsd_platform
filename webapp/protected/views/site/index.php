<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>
<div class="row-fluid">
    <div class="span2">
        <?php
        echo CHtml::image("./images/logobb.png", "Biobanques");
        ?>
    </div>
    <div class="span10">
        <?php
        $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
            'heading' => 'Welcome to ' . CHtml::encode(Yii::app()->name),
        ));
        ?>
        <?php $this->endWidget(); ?>
    </div>
</div>
