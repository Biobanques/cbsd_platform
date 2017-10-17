<div id="statusMsg">
    <?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="flash-success">
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::app()->user->hasFlash('error')): ?>
        <div class="flash-error">
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>
    <?php endif; ?>
</div>

<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('#User_id_all').click(function(){
    $('input:checkbox').attr('checked', true);
    $.fn.yiiGridView.update('user-grid', {
        data: $(this).serialize()
    });
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('user-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
Yii::app()->clientScript->registerScript('getUnchecked', "
       function getUncheckeds(){
            var unch = [];
            $('[name^=User_id]').not(':checked,[name$=all]').each(function(){unch.push($(this).val());});
            return unch.toString();
       }
       "
);
?>

<h1><?php echo Yii::t('administration', 'registeredUsers'); ?></h1>
<div class="info">
    <div class="title"><?php echo Yii::t('user', 'infoTitle') ?></div>
    <div class="content"><?php echo Yii::t('user', 'infoContent') ?></div>
</div>
<?php
$imagecreateuser = CHtml::image(Yii::app()->baseUrl . '/images/user_add.png', Yii::t('common', 'createUser'));
echo CHtml::link($imagecreateuser . Yii::t('administration', 'createUser'), array('user/create'));
?>
<br />
<?php
$imagesearch = CHtml::image(Yii::app()->baseUrl . '/images/zoom.png', Yii::t('administration', 'advancedsearch'));
echo CHtml::link($imagesearch . Yii::t('common', 'advancedsearch'), '#', array('class' => 'search-button'));
?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post',
        ));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
    'selectableRows'=>2,
    'beforeAjaxUpdate'=>'function(id,options){options.data={checkedIds:$.fn.yiiGridView.getChecked("user-grid","User_id").toString(),
        uncheckedIds:getUncheckeds()};
        return true;}',

    'ajaxUpdate'=>true,
    'enablePagination' => true,
    'columns' => array(
        array('class' => 'CCheckBoxColumn', 'id'=>'User_id', 'checked'=>isset($_GET['ajax']) ? 'Yii::app()->user->getState($data->_id)' : '0'),
        array('header' => $model->attributeLabels()["login"], 'name' => 'login'),
        array('header' => $model->attributeLabels()["nom"], 'name' => 'nom'),
        array('header' => $model->attributeLabels()["prenom"], 'name' => 'prenom'),
        array('header' => $model->attributeLabels()["email"], 'name' => 'email'),
        array('header' => $model->attributeLabels()["profil"], 'name' => 'profil', 'value' => '$data->getAllProfilesUser($data->login)'),
        array(
            'class' => 'CButtonColumn',
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>
<div class="row">
    <div class="col-lg-5">
        <?php echo CHtml::submitButton(Yii::t('button', 'deleteSelectedUsers'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>
