<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;

?>

<div class="row-fluid">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <?php
        $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
            'heading' => 'Bienvenue sur ' . CHtml::encode(Yii::app()->name),
        ));
        ?>
        CBSDPlatform vous permet de gérer les formulaires standardisés pour les informations cliniques, génétiques et neuropathologiques diffusées aux cliniciens.
        <?php $this->endWidget(); ?>
    </div>
</div>
