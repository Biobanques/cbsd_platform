<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading"><?php echo Yii::t('administration', 'administration') ?></div>
            <div class="panel-body">
                <?php
                $this->widget('zii.widgets.CMenu', array(
                    'items' => array(
                        array('label' => Yii::t('administration', 'manageUsers')),
                        array('label' => Yii::t('administration', 'registeredUsers'), 'icon'=>'play', 'url' => array('/user/admin')),
                        array('label' => Yii::t('administration', 'manageRules'), 'icon'=>'play', 'url' => array('/administration/admin')),
                        array('label' => Yii::t('administration', 'referenceCenter'), 'icon'=>'play', 'url' => array('/referenceCenter/admin')),
                        array('label' => Yii::t('administration', 'manageForms')),
                        array('label' => Yii::t('administration', 'forms'), 'icon'=>'play', 'url' => array('/formulaire/admin')),
                        array('label' => Yii::t('administration', 'manageQuestionsBlock'), 'icon'=>'play', 'url' => array('/questionBloc/admin')),
                        array('label' => Yii::t('administration', 'managePatientForm')),
                        array('label' => Yii::t('administration', 'fiches'), 'icon'=>'play', 'url' => array('/fiche/admin')),
                        array('label' => Yii::t('administration', 'logSystem')),
                        array('label' => Yii::t('administration', 'logSystem'), 'icon'=>'play', 'url' => array('/auditTrail/admin')),
                        array('label' => Yii::t('administration', 'userLog'), 'icon'=>'play', 'url' => array('/administration/userLog')),
                        array('label' => Yii::t('administration', 'manageProjects')),
                        array('label' => Yii::t('administration', 'manageProjects'), 'icon'=>'play', 'url' => array('/project/admin')),
                        array('label' => Yii::t('administration', 'filemaker')),
                        array('label' => Yii::t('administration', 'columnFileMaker'), 'icon'=>'play', 'url' => array('/fileImport/formatColumn')),
                        array('label' => Yii::t('administration', 'importFilemaker'), 'icon'=>'play', 'url' => array('/fileImport/admin')),
                        array('label' => "Gestion des doublons", 'icon'=>'play', 'url' => array('/fileImport/adminDoublon')),
                    ),
                    'htmlOptions' => array('class' => 'operations'),
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div id="content" class='content' style="padding : 0px 5px 5px 5px;">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>
</div>


<?php $this->endContent(); ?>