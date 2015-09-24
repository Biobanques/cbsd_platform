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
            'heading' => 'Bienvenue sur ' . CHtml::encode(Yii::app()->name),
        ));
        ?>
        CBSDPlatform vous permet de gérer les formulaires standardisés pour les informations cliniques, génétiques et neuropathologiques diffusées aux cliniciens.
        <?php $this->endWidget(); ?>
    </div>
</div>
