<div class="wide form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'updateQuestion-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label(Yii::t('common', 'currentLabel'), 'old_question');
            echo CHtml::dropDownList('old_question', '', $model->getArrayQuestions(), array("required" => "required"));
            ?>
            <div id="result"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php
            echo CHtml::label(Yii::t('common', 'newLabel'), 'new_question');
            echo CHtml::textfield('new_question', '', array("required" => "required"));
            ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->