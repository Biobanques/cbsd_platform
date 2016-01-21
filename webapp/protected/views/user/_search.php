<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
    ));
    ?>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'login'); ?>
            <?php echo $form->textField($model, 'login'); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'profil'); ?>
            <?php echo $form->dropDownList($model, 'profil', $model->getArrayProfil(), array('prompt' => '---')); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'nom'); ?>
            <?php echo $form->textField($model, 'nom'); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'prenom'); ?>
            <?php echo $form->textField($model, 'prenom'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'email'); ?>
            <?php echo $form->textField($model, 'email'); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-6">
            <?php echo CHtml::submitButton('Rechercher'); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->