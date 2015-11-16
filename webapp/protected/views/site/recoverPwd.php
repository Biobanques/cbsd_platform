<?php
/* @var $this SiteController */
?>
<h1><?php echo Yii::t('common', 'forgotedPwd'); ?></h1>

<hr />

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'recover-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <table cellpadding="10" style="margin-left:20px">
        <tr>
            <td>
                <div class="row">
                    <?php echo $form->labelEx($model, 'login'); ?>
                    <?php echo $form->textField($model, 'login'); ?>
                    <?php echo $form->error($model, 'login'); ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row">
                    <?php echo $form->labelEx($model, 'email'); ?>
                    <?php echo $form->textField($model, 'email'); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="row buttons">
                    <?php echo CHtml::submitButton(Yii::t('common', 'submit')); ?>
                </div>
            </td>
        </tr>
    </table>

    <?php
    $this->endWidget();
    ?>

</div>