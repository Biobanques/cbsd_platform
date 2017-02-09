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
        <div class="col-lg-12">
            <?php echo $form->labelEx($uploadedFile, 'filename'); ?>
            <?php echo $form->fileField($uploadedFile, 'filename'); ?>
            <?php echo $form->error($uploadedFile, 'filename'); ?>
        </div>
    </div>

    <div class="row buttons">
        <div class="col-lg-12">
            <?php
            echo CHtml::submitButton('Importer', array('class' => 'btn btn-primary', 'id' => 'import', 'style' => 'margin-top: 8px; padding-bottom: 23px;', 'onclick' => '$("#loading").show();'));
            echo CHtml::image(Yii::app()->request->baseUrl . '/images/loading.gif', 'loading', array('id' => "loading", 'style' => "margin-left: 10px; display: none;"));
            ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->