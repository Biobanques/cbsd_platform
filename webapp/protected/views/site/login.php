<?php
Yii::app()->clientScript->registerScript('loginForm', "
    $('.icon').hover(function () {
        $('.password').attr('type', 'text');
    }, function () {
        $('.password').attr('type', 'password');
    });
");
?>

<h1><?php echo Yii::t('common', 'signin'); ?></h1>
<hr />

<div class="row">
    <div class="col-lg-6" style="margin-left:60px;">
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
                <?php echo $form->passwordField($model, 'password', array('class' => 'password')); ?><?php echo CHtml::image(Yii::app()->request->baseUrl . '/images/eye.png', 'View password', array('class' => 'icon', 'style' => 'padding-left:5px;')); ?>
                <?php echo $form->error($model, 'password'); ?>
            </div>

            <div class="row rememberMe">
                <?php echo $form->checkBox($model, 'rememberMe'); ?>
                <?php echo $form->label($model, 'rememberMe'); ?>
                <?php echo $form->error($model, 'rememberMe'); ?>
            </div>

            <div class="row buttons">
                <?php echo CHtml::submitButton(Yii::t('common', 'seconnecter'), array('class' => 'btn btn-primary')); ?>
            </div>

            <?php
            $this->endWidget();
            echo CHtml::link(Yii::t('common', 'forgotedPwd'), array_merge(array("site/recoverPwd"), isset($_GET['layout']) ? array('layout' => $_GET['layout']) : array()));
            ?>


        </div><!-- form -->
    </div>
    <div class="col-lg-5" style="margin-top:70px;">
        <div align='center'>
            <?php echo Yii::t('common', 'noAccount'); ?><br><br>
            <?php
            echo CHtml::button(Yii::t('common', 'subscribe'), array(
                'class' => 'btn btn-primary',
                'submit' => array_merge(array("site/subscribeProfil"), isset($_GET['layout']) ? array('layout' => $_GET['layout']) : array())
            ));
            ?>
        </div>
    </div>
</div>