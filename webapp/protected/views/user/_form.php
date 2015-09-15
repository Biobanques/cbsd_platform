<div class="form" style="margin-left:30px;">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t("common", "ChampsObligatoires"); ?></p>

<?php echo $form->errorSummary($model); ?>

    <table cellpadding="10" style="margin-left:-10px"><tr>

            <td>
                <?php echo $form->labelEx($model, 'login'); ?>
                <?php echo $form->textField($model, 'login', array('size' => 60, 'maxlength' => 250)); ?>
                <?php echo $form->error($model, 'login'); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model, 'password'); ?>
                <?php echo $form->textField($model, 'password', array('size' => 60, 'maxlength' => 250)); ?>
                <?php echo $form->error($model, 'password'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model, 'profil'); ?>
                <?php echo $form->dropDownList($model, 'profil', User::model()->getArrayProfil(), array('prompt' => '----')); ?>
                <?php echo $form->error($model, 'profil'); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model, 'nom'); ?>
                <?php echo $form->textField($model, 'nom', array('size' => 60, 'maxlength' => 250)); ?>
                <?php echo $form->error($model, 'nom'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model, 'prenom'); ?>
                <?php echo $form->textField($model, 'prenom', array('size' => 60, 'maxlength' => 250)); ?>
                <?php echo $form->error($model, 'prenom'); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model, 'email'); ?>
                <?php echo $form->textField($model, 'email', array('size' => 60, 'maxlength' => 250)); ?>
                <?php echo $form->error($model, 'email'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model, 'telephone'); ?>
                <?php echo $form->textField($model, 'telephone', array('size' => 60, 'maxlength' => 250)); ?>
                <?php echo $form->error($model, 'telephone'); ?>
            </td>
            <td>
                <?php echo $form->labelEx($model, 'gsm'); ?>
                <?php echo $form->textField($model, 'gsm', array('size' => 60, 'maxlength' => 250)); ?>
                <?php echo $form->error($model, 'gsm'); ?>
            </td>
        </tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model, 'inactif'); ?>
                <?php echo $form->dropDownList($model, 'inactif', User::model()->getArrayInactif(), array('prompt' => '----')); ?>
                <?php echo $form->error($model, 'inactif'); ?>
            </td>
        </tr>
        <tr>
            <td style="float:right;">
                <div class="row buttons" style="float:left;">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Créer' : 'Enregistrer'); ?>
                </div>
            </td>
        </tr>
    </table>

<?php $this->endWidget(); ?>

</div><!-- form -->