<div>
    <div class="myBreadcrumb">
        <div class="active"><?php echo Yii::t('common', 'queryAnonymous') ?></div>
        <div><?php echo Yii::t('common', 'queryFormulation') ?></div>
        <div><?php echo Yii::t('common', 'resultQuery') ?></div>
    </div>
</div>

<div class="search-form">
    <div class="wide form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'light_search-form',
            'action' => Yii::app()->createUrl('rechercheFiche/admin2'),
            'method' => 'post',
        ));
        ?>

        <div style="border:1px solid black;">

            <h4 style="margin-left:10px;"><u><b><?php echo Yii::t('common', 'queryAnonymous') . " " . Yii::t('common', 'to') ?></b></u></h4>
            <div class="row">
                <div class="col-lg-12">
                    <?php echo CHtml::label(Yii::t('common', 'individualSelections'), 'Answer_id_patient', array('style' => 'width:250px; padding-top:30px;')); ?>
                    <?php echo $form->dropDownList($model, 'id_patient', Answer::model()->getIdPatientFiches(), array("id" => "multiselect_simple", "class" => "multiselect", "multiple" => "multiple", "style" => "width:60%; height:120px;")); ?>
                </div>
            </div>

            <p style="margin-left:10px; color:red;"><?php echo Yii::t('common', 'notRestrict'); ?></p>

        </div>

        <div class="well">
                <p id="multiselect_simple_selection">- Cas sélectionné(s): </p>
        </div>

        <div class="row buttons">
            <div class="col-lg-7 col-lg-offset-7">
                <?php echo CHtml::submitButton(Yii::t('button', 'next'), array('id' => 'next', 'class' => 'btn btn-primary', 'onclick' => 'submit(); $("#loading_next").show();')); ?>
                <?php echo CHtml::image(Yii::app()->request->baseUrl . '/images/loading.gif', 'loading', array('id' => "loading_next", 'style' => "margin-left: 10px; margin-bottom:10px; display:none;")); ?>
            </div>
        </div>

        <?php $this->endWidget(); ?>

    </div><!-- search-form -->
</div>