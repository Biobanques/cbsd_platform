<div class="form" style="margin-left:30px;">

    <?php
    $form = $this->beginWidget(
            'CActiveForm', array(
        'id' => 'upload-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
            )
    );
    ?>

    <?php echo $form->errorSummary($uploadedFile); ?>

    <div class="row">
        <?php echo $form->labelEx($uploadedFile, 'filename'); ?>
        <?php echo $form->fileField($uploadedFile, 'filename'); ?>
        <?php echo $form->error($uploadedFile, 'filename'); ?>
    </div>      

    <div class="row buttons" style="float:left;">
        <?php
        echo CHtml::submitButton('Importer', array('class' => 'btn btn-default', 'id' => 'import', 'style' => 'margin-top: 8px; padding-bottom: 23px;', 'onclick' => '$("#loading").show();'));
        echo CHtml::image(Yii::app()->request->baseUrl . '/images/loading.gif', 'loading', array('id' => "loading", 'style' => "margin-left: 10px; display: none;"));
        ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->