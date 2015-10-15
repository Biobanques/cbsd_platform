
<div >
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'question_bloc-form',
        'enableAjaxValidation' => false,
    ));
    ?>

    <p class="note"><?php echo Yii::t('common', 'ChampsObligatoires'); ?></p>

    <?php echo $form->errorSummary($questionForm, null, null, array('class' => 'alert alert-error')); ?>
    <p>*L'id doit être unique parmi les questions. Il permet de gérer des mécanismes liées à l'ordonnancement des questions et aux traitements de validation.</p>
    <p>*Les valeurs sont utilisés pour les listes (radio bouton et listes déroulantes). Le séparateur de valeurs est la virgule.</p>
    <p>*Le style permet d'affecter un style css à la question. Par exemple, si vous souahitez aligner votr equestion à droite de la précédente , saisissez dans le champ le texte en gras: <b>float:right</b></p>

    <div class="question_group" style="float: none">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title'); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <?php
    if (isset($dataProvider)) {
        $this->widget('bootstrap.widgets.TbGridView', array(
            'type' => 'striped bordered condensed',
            'dataProvider' => $dataProvider,
            'template' => "{items}",
            'emptyText' => 'Il n\'y a pas de questions associées à ce bloc.',
            'columns' => array(
                array('name' => 'id', 'header' => 'id'),
                array('name' => 'label', 'header' => 'label'),
                array('name' => 'type', 'header' => 'type'),
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => '{delete}',
                    'buttons' => array
                        (
                        'delete' => array
                            (
                            'url' => " Yii::app()->createUrl('questionBloc/deleteQuestion', array('id' => " . '$data->_id' . ",'blocId'=>'$model->_id'))"
                        ),
//        'update' => array
//        (
//                            'url' => '(Yii::app()->user->id == $data->getUserId() && Yii::app()->user->getState(\'activeProfil\')==\'genetique\')?true:false;'
//                        ),
//                        'delete' => array
//                            (
//                            'url' => '(Yii::app()->user->id == $data->getUserId() && Yii::app()->user->getState(\'activeProfil\')==\'genetique\')?true:false;'
//                        )
                    ),
                //'htmlOptions' => array('style' => 'width: 70px'),
                ),
            ),
        ));
    }
    ?>

    <div class="panel panel-primary">
        <div class="panel-heading"><h4>Ajouter une nouvelle question</h4></div>
        <div class="panel-body">
            <div >
                <div class="col-lg-6">
                    <?php echo $form->labelEx($questionForm, 'id'); ?>
                    <?php echo $form->textField($questionForm, 'id', array('size' => 5, 'maxlength' => 45)); ?>
                    <?php echo $form->error($questionForm, 'id'); ?>
                </div>
                <div class="col-lg-6">
                    <?php echo $form->labelEx($questionForm, 'label'); ?>
                    <?php echo $form->textField($questionForm, 'label', array('size' => 5, 'maxlength' => 45)); ?>
                    <?php echo $form->error($questionForm, 'label'); ?>

                </div>
            </div>
            <div>
                <div class="col-lg-6">
                    <?php echo $form->labelEx($questionForm, 'type'); ?>
                    <?php echo $form->dropDownList($questionForm, 'type', $questionForm->getArrayTypes(), array('prompt' => '----')); ?>
                    <?php echo $form->error($questionForm, 'type'); ?>
                </div>
                <div class="col-lg-6">
                    <?php echo $form->labelEx($questionForm, 'idQuestionBefore'); ?>
                    <?php echo $form->dropDownList($questionForm, 'idQuestionBefore', array()); ?>
                    <?php echo $form->error($questionForm, 'idQuestionBefore'); ?>
                </div>
            </div>

            <div>
                <div class="col-lg-6">
                    <?php echo $form->labelEx($questionForm, 'style'); ?>
                    <?php echo $form->textField($questionForm, 'style', array('size' => 5, 'maxlength' => 45)); ?>
                    <?php echo $form->error($questionForm, 'style'); ?>
                </div>
                <div class="col-lg-6">
                    <?php echo $form->labelEx($questionForm, 'values'); ?>
                    <?php echo $form->textField($questionForm, 'values', array('size' => 5, 'maxlength' => 45)); ?>
                    <?php echo $form->error($questionForm, 'values'); ?>
                </div>
            </div>
            <div class="buttons">
                <?php echo CHtml::submitButton('Enregistrer'); ?>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->


