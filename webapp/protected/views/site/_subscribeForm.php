<?php
/* @var $this SiteController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="container">
    <div class="row">

        <div class="form">
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'user-form',
                'action' => array('site/subscribe'),
                'enableAjaxValidation' => false,
            ));
            ?>

            <hr />

            <p class="note"><?php echo Yii::t('common', 'ChampsObligatoires'); ?></p>

            <?php echo $form->errorSummary($model); ?>

            <div class="row">
                <div class="col-lg-3">
                    <?php echo $form->labelEx($model, 'prenom'); ?>
                    <?php echo $form->textField($model, 'prenom', array('size' => 20, 'maxlength' => 250)); ?>
                    <?php echo $form->error($model, 'prenom'); ?>
                </div>
                <div class="col-lg-3">
                    <?php echo $form->labelEx($model, 'nom'); ?>
                    <?php echo $form->textField($model, 'nom', array('size' => 20, 'maxlength' => 250)); ?>
                    <?php echo $form->error($model, 'nom'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <?php echo $form->labelEx($model, 'login'); ?>
                    <?php echo $form->textField($model, 'login', array('size' => 20, 'maxlength' => 250)); ?>
                    <?php echo $form->error($model, 'login'); ?>
                </div>

                <div class="col-lg-3">
                    <?php echo $form->labelEx($model, 'password'); ?>
                    <?php echo $form->passwordField($model, 'password', array('size' => 20, 'maxlength' => 250)); ?>
                    <?php echo $form->error($model, 'password'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    <?php echo $form->labelEx($model, 'email'); ?>
                    <?php echo $form->textField($model, 'email', array('size' => 20, 'maxlength' => 250)); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>

                <div class="col-lg-3">
                    <?php echo $form->labelEx($model, 'telephone'); ?>
                    <?php echo $form->textField($model, 'telephone', array('size' => 20, 'maxlength' => 250, 'placeholder' => 'Format 0C CC CC CC CC')); ?>
                    <?php echo $form->error($model, 'telephone'); ?>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-3">
                    <?php echo $form->labelEx($model, 'profil'); ?>
                    <?php
                    echo $form->textField($model, 'profil[]', array(
                        'value' => $profil,
                        'readonly' => true
                    ));
                    ?>
                    <?php echo $form->error($model, 'profil'); ?>
                </div>
                <div class="col-lg-3">
                    <?php echo $form->labelEx($model, 'gsm'); ?>
                    <?php echo $form->textField($model, 'gsm', array('size' => 20, 'maxlength' => 250)); ?>
                    <?php echo $form->error($model, 'gsm'); ?>
                </div>
            </div>

            <div class="col-lg-3">
                <div id="address" <?php if ($profil != "clinicien") echo "style=\"display:none;\"" ?>>
                    <?php echo CHtml::activeLabel($model, 'address', array('required' => true)); ?>
                    <?php echo $form->textField($model, 'address', array('size' => 20, 'maxlength' => 250)); ?>
                    <?php echo $form->error($model, 'address'); ?>
                </div>
            </div>

            <div class="col-lg-3">
                <div id="centre" <?php if ($profil != "neuropathologiste") echo "style=\"display:none;\"" ?>>
                    <?php echo CHtml::activeLabel($model, 'centre', array('required' => true)); ?>
                    <?php echo $form->dropDownList($model, 'centre', User::model()->getArrayCentre(), array('prompt' => '----')); ?>
                    <?php echo $form->error($model, 'centre'); ?>
                </div>
            </div>

            <div class="col-lg-12">

                <div class="row buttons" style="float:left;">
                    <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('common', 'subscribe') : Yii::t('common', 'save')); ?>
                </div>
            </div>
            <?php $this->endWidget(); ?>

        </div><!-- form -->
    </div>
</div>