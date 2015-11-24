<?php
/* @var $this SiteController */
/* @var $form CActiveForm  */
?>

<div class="container">
    <div class="row">
        <div class="col-lg-5" style="margin-left:60px;">
            <h1>Profil utilisateur</h1>
            <hr />
            <?php
            $this->beginWidget('CActiveForm', array(
                'id' => 'subscribe-form',
                'action' => array('site/loginProfil'),
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));
            ?>
            <?php echo "Bienvenue <b>" . ucfirst(Yii::app()->user->getPrenom()) . " " . strtoupper(Yii::app()->user->getNom()) . "</b>, <br>sous quel profil voulez-vous vous connecter?"; ?>
            <br><br>
            <div>
                <?php echo CHtml::radioButtonList("profil", '', array("administrateur" => "administrateur"), array('disabled' => (in_array("administrateur", Yii::app()->user->getUserProfil())) ? "" : "disabled", 'labelOptions' => array('style' => 'display:inline'))); ?>
            </div>

            <div>
                <?php echo CHtml::radioButtonList("profil", '', array("clinicien" => "clinicien"), array('disabled' => (in_array("clinicien", Yii::app()->user->getUserProfil())) ? "" : "disabled", 'labelOptions' => array('style' => 'display:inline'))); ?>
            </div>

            <div>
                <?php echo CHtml::radioButtonList("profil", '', array("neuropathologiste" => "neuropathologiste"), array('disabled' => (in_array("neuropathologiste", Yii::app()->user->getUserProfil())) ? "" : "disabled", 'labelOptions' => array('style' => 'display:inline'))); ?>
            </div>

            <div>
                <?php echo CHtml::radioButtonList("profil", '', array("geneticien" => "geneticien"), array('disabled' => (in_array("geneticien", Yii::app()->user->getUserProfil())) ? "" : "disabled", 'labelOptions' => array('style' => 'display:inline'))); ?>
            </div>

            <div>
                <?php echo CHtml::radioButtonList("profil", '', array("chercheur" => "chercheur"), array('disabled' => (in_array("chercheur", Yii::app()->user->getUserProfil())) ? "" : "disabled", 'labelOptions' => array('style' => 'display:inline'))); ?>
            </div>
            <br>
            <div>
                <?php echo CHtml::submitButton('se connecter'); ?>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    <div class="span3" style="margin-top:100px;">
        <?php
        $this->beginWidget('CActiveForm', array(
            'id' => 'subscribe-form',
            'action' => array('site/updateSubscribe'),
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        ));
        ?>
        <div style=<?php if (in_array("clinicien", Yii::app()->user->getUserProfil())) echo "display:none"; ?>>
            <a href="#" class="btn btn-sq-lg btn-default userProfil">
                <i class="fa fa-user fa-5x"></i><br/>
                S'inscrire comme <br>
                <?php echo CHtml::submitButton('clinicien', array('name' => 'clinicien')); ?>
            </a>
        </div>
        <div style=<?php if (in_array("neuropathologiste", Yii::app()->user->getUserProfil())) echo "display:none"; ?>>
            <a href="#" class="btn btn-sq-lg btn-default userProfil">
                <i class="fa fa-user fa-5x"></i><br/>
                S'inscrire comme <br>
                <?php echo CHtml::submitButton('neuropathologiste', array('name' => 'neuropathologiste')); ?>
            </a>
        </div>
        <div style=<?php if (in_array("geneticien", Yii::app()->user->getUserProfil())) echo "display:none"; ?>>
            <a href="#" class="btn btn-sq-lg btn-default userProfil">
                <i class="fa fa-user fa-5x"></i><br/>
                S'inscrire comme <br>
                <?php echo CHtml::submitButton('geneticien', array('name' => 'geneticien')); ?>
            </a>
        </div>
        <div style=<?php if (in_array("chercheur", Yii::app()->user->getUserProfil())) echo "display:none"; ?>>
            <a href="#" class="btn btn-sq-lg btn-default userProfil">
                <i class="fa fa-user fa-5x"></i><br/>
                S'inscrire comme <br>
                <?php echo CHtml::submitButton('chercheur', array('name' => 'chercheur')); ?>
            </a>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
</div>
