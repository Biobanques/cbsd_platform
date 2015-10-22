<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
$action = "";
if (isset(Yii::app()->user->id))
    $action = Yii::app()->createUrl('site/updateSubscribe');
else
    $action = Yii::app()->createUrl('site/subscribe');
?>

<h1>Connexion utilisateur</h1>
<hr />

<div class="row">

    <div class="form">
        <div class="span3" style="margin-left:60px;">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'login-form',
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));
            ?>

            <p class="note"><?php echo Yii::t('common', 'ChampsObligatoires'); ?></p>

            <div class="row">
                <?php echo $form->labelEx($model, 'username'); ?>
                <?php echo $form->textField($model, 'username'); ?>
                <?php echo $form->error($model, 'username'); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'password'); ?>
                <?php echo $form->passwordField($model, 'password'); ?>
                <?php echo $form->error($model, 'password'); ?>
            </div>

            <div class="row rememberMe">
                <?php echo $form->label($model, 'rememberMe'); ?>
                <?php echo $form->checkBox($model, 'rememberMe'); ?>
                <?php echo $form->error($model, 'rememberMe'); ?>
            </div>

            <div class="row">
                <fieldset>
                    <?php echo $form->labelEx($model, 'profil'); ?>
                    <?php echo $form->radioButtonList($model, 'profil', User::model()->getArrayProfilFiltered(), array('onchange' => 'js:validate_dropdown()', 'labelOptions' => array('style' => 'display:inline'))); ?>
                    <?php echo $form->error($model, 'profil'); ?>
                </fieldset>
            </div>

            <?php
            echo CHtml::link(Yii::t('common', 'forgotedPwd'), array_merge(array("site/recoverPwd"), isset($_GET['layout']) ? array('layout' => $_GET['layout']) : array()));
            ?>

            <div class="row buttons">
                <?php echo CHtml::submitButton(Yii::t('common', 'seconnecter')); ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div><!-- form -->
        <div class="span5" style="margin-top:70px;">
        <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'subscribe-form',
                'action' => $action,
                'enableClientValidation' => true,
                'clientOptions' => array(
                    'validateOnSubmit' => true,
                ),
            ));
            ?>
            <a href="#" class="btn btn-sq-lg btn-default userProfil">
                <i class="fa fa-user fa-5x"></i><br/>
                S'inscrire en tant que <br><?php echo CHtml::submitButton('clinicien', array('name' => 'clinicien')); ?>
            </a>
            <a href="#" class="btn btn-sq-lg btn-default userProfil">
                <i class="fa fa-user fa-5x"></i><br/>
                S'inscrire en tant que <br><?php
            echo CHtml::submitButton('neuropathologiste', array('name' => 'neuropathologiste'));
            ?>
            </a>
            <a href="#" class="btn btn-sq-lg btn-default userProfil">
                <i class="fa fa-user fa-5x"></i><br/>
                S'inscrire en tant que <br><?php
            echo CHtml::submitButton('geneticien', array('name' => 'geneticien'));
            ?>
            </a>
            <a href="#" class="btn btn-sq-lg btn-default userProfil">
                <i class="fa fa-user fa-5x"></i><br/>
                S'inscrire en tant que <br><?php
            echo CHtml::submitButton('chercheur', array('name' => 'chercheur'));
            ?>
            </a>
        <?php $this->endWidget(); ?>
    </div>
</div>