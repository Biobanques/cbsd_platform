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
            <?php echo $form->dropDownList($model, 'login', User::model()->getAllUsersLogin(), array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'profil'); ?>
            <?php echo $form->dropDownList($model, 'profil', $model->getArrayProfil(), array('prompt' => '---', "multiple" => "multiple")); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'nom'); ?>
            <?php echo $form->dropDownList($model, 'nom', User::model()->getAllUsersLastnames(), array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>

        <div class="col-lg-6">
            <?php echo $form->label($model, 'prenom'); ?>
            <?php echo $form->dropDownList($model, 'prenom', User::model()->getAllUsersFirstnames(), array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php echo $form->label($model, 'email'); ?>
            <?php echo $form->dropDownList($model, 'email', User::model()->getAllUsersEmail(), array('prompt' => '----', "multiple" => "multiple")); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-7 col-lg-offset-7">
            <?php echo CHtml::submitButton(Yii::t('common', 'search'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
            <?php echo CHtml::resetButton(Yii::t('common', 'reset'), array('class' => 'btn btn-danger')); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->