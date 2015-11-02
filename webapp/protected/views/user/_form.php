<div class="form" style="margin-left:30px;">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t("common", "ChampsObligatoires"); ?></p>

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
        <?php
        if (Yii::app()->user->isGuest)
            echo $form->checkBoxList($model, 'profil', User::model()->getArrayProfilFiltered(), array('onchange' => 'js:validate_dropdown()', 'labelOptions' => array('style' => 'display:inline')));
        else
            echo $form->checkBoxList($model, 'profil', User::model()->getArrayProfilSorted(), array('onchange' => 'js:validate_dropdown()', 'labelOptions' => array('style' => 'display:inline')));
        ?>
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
        <div id="statut" <?php if (Yii::app()->user->isGuest) echo "style=\"display:none;\""; ?>>
            <?php echo CHtml::activeLabel($model, 'statut', array('required' => true)); ?>
            <?php echo $form->dropDownList($model, 'statut', User::model()->getArrayStatut(), array('prompt' => '----')); ?>
            <?php echo $form->error($model, 'statut'); ?>
        </div>
    </div>

    <div class="row buttons" style="float:left;">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Créer' : 'Enregistrer'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->