<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading"><?php echo Yii::t('common', 'administration') ?></div>
            <div class="panel-body">
                <?php
                $this->widget('bootstrap.widgets.TbMenu', array(
                    'type'=>'list',
                    'items' => array(
                        array('label' => Yii::t('common', 'manageUsers')),
                        array('label' => Yii::t('common', 'registeredUsers'), 'icon'=>'play', 'url' => array('/user/admin')),
                        array('label' => Yii::t('common', 'manageRules'), 'icon'=>'play', 'url' => array('/administration/admin')),
                        array('label' => Yii::t('common', 'manageForms')),
                        array('label' => Yii::t('common', 'forms'), 'icon'=>'play', 'url' => array('/formulaire/admin')),
                        array('label' => Yii::t('common', 'manageQuestionsBlock'), 'icon'=>'play', 'url' => array('/questionBloc/admin')),
                        array('label' => Yii::t('common', 'managePatientForm')),
                        array('label' => Yii::t('common', 'fiches'), 'icon'=>'play', 'url' => array('/fiche/admin')),
                        array('label' => Yii::t('common', 'logSystem')),
                        array('label' => Yii::t('common', 'logSystem'), 'icon'=>'play', 'url' => array('/auditTrail/admin')),
                        array('label' => Yii::t('common', 'userLog'), 'icon'=>'play', 'url' => array('/administration/userLog')),
                        array('label' => Yii::t('common', 'importedFiles'), 'icon'=>'play', 'url' => array('/fileImport/admin'))
                    ),
                    'htmlOptions' => array('class' => 'operations'),
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div id="content" class='content'style="padding : 0px 5px 5px 5px;">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>
</div>


<?php $this->endContent(); ?>