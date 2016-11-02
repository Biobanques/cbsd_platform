<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="row">
    <div class="col-lg-3">
        <div class="panel panel-primary">
            <!-- Default panel contents -->
            <div class="panel-heading">Administration</div>
            <div class="panel-body">
                <?php
                $this->widget('bootstrap.widgets.TbMenu', array(
                    'items' => array(
                        array('label' => Yii::t('common', 'users'), 'url' => array('/user/admin')),
                        array('label' => Yii::t('common', 'forms'), 'url' => array('/formulaire/admin')),
                        array('label' => 'Fiches', 'url' => array('/fiche/admin')),
                        array('label' => Yii::t('common', 'manageQuestionsBlock'), 'url' => array('/questionBloc/admin')),
                        array('label' => Yii::t('common', 'manageRules'), 'url' => array('/administration/admin')),
                        array('label' => 'Log système', 'url' => array('/auditTrail/admin')),
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