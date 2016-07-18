<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
?>

<h1>Connexion utilisateur</h1>
<hr />

<div class="row">
    <div class="span4" style="margin-left:60px;">
            <div class="form">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'login-form',
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                ));
                ?>

                <p class="note"><?php echo Yii::t('common', 'requiredField'); ?></p>

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
                    <?php echo $form->checkBox($model, 'rememberMe'); ?>
                    <?php echo $form->label($model, 'rememberMe'); ?>
                    <?php echo $form->error($model, 'rememberMe'); ?>
                </div>

                <div class="row buttons">
                    <?php echo CHtml::submitButton(Yii::t('common', 'seconnecter')); ?>
                </div>

                <?php
                $this->endWidget();
                echo CHtml::link(Yii::t('common', 'forgotedPwd'), array_merge(array("site/recoverPwd"), isset($_GET['layout']) ? array('layout' => $_GET['layout']) : array()));
                ?>


            </div><!-- form -->
    </div>
    <div class="span3" style="margin-top:70px;">
            <div align='center'>
                <?php echo "Vous n'êtes pas encore inscrit ?" ?><br><br>
                <?php
                echo CHtml::button(Yii::t('common', 'subscribe'), array(
                    'submit' => array_merge(array("site/subscribeProfil"), isset($_GET['layout']) ? array('layout' => $_GET['layout']) : array())
                ));
                ?>
            </div>
    </div>
</div>