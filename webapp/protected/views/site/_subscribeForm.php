<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="wide form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <hr />

    <p class="note"><?php echo Yii::t('common', 'ChampsObligatoires'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'prenom'); ?>
        <?php echo $form->textField($model, 'prenom', array('size' => 20, 'maxlength' => 250)); ?>
        <?php echo $form->error($model, 'prenom'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'nom'); ?>
        <?php echo $form->textField($model, 'nom', array('size' => 20, 'maxlength' => 250)); ?>
        <?php echo $form->error($model, 'nom'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'login'); ?>
        <?php echo $form->textField($model, 'login', array('size' => 20, 'maxlength' => 250)); ?>
        <?php echo $form->error($model, 'login'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password', array('size' => 20, 'maxlength' => 250)); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', array('size' => 20, 'maxlength' => 250)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'profil'); ?>
        <?php echo $form->dropDownList($model, 'profil', User::model()->getArrayProfilFiltered(), array('prompt' => '----', 'onchange'=>'js:validate_dropdown(this.value)')); ?>
        <?php echo $form->error($model, 'profil'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'telephone'); ?>
        <?php echo $form->textField($model, 'telephone', array('size' => 20, 'maxlength' => 250)); ?>
        <?php echo $form->error($model, 'telephone'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'gsm'); ?>
        <?php echo $form->textField($model, 'gsm', array('size' => 20, 'maxlength' => 250)); ?>
        <?php echo $form->error($model, 'gsm'); ?>
    </div>
    
    <div class="row">
        <div id="address" style="display:none;">
        <?php echo $form->labelEx($model, 'address'); ?>
        <?php echo $form->textField($model, 'address', array('size' => 20, 'maxlength' => 250)); ?>
        <?php echo $form->error($model, 'address'); ?>
        </div>
    </div>

    <div class="row">
        <div id="centre" style="display:none;">
        <?php echo $form->labelEx($model, 'centre'); ?>
        <?php echo $form->textField($model, 'centre', array('size' => 20, 'maxlength' => 250)); ?>
        <?php echo $form->error($model, 'centre'); ?>
        </div>
    </div>
    
    <div class="row">
        <p class="note">Cliquez sur l'image pour rafraichir</p>
        <?php
        $this->widget('CCaptcha', array('clickableImage' => true, 'showRefreshButton' => false));
        echo '<br>';
        echo $form->labelEx($model, 'verifyCode');
        echo $form->textField($model, 'verifyCode');
        echo $form->error($model, 'verifyCode');
        ?>
    </div>

    <div class="row">

        <div class="row buttons" style="float:left;">
            <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('common', 'subscribe') : Yii::t('common', 'save')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->

    <script type="text/javascript">
        function validate_dropdown(id) {           
            if (id === "0") {
                $('#address').show();
                //$('#centre').hide();
            }
            else if (id === "2") {
                $('#address').hide();
                $('#centre').show();
            }
            else {
                $('#address').hide();
                $('#centre').hide();
            }
        }
    </script>