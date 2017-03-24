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
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('user-grid', {
        data: $(this).serialize()
    });
    return false;
});
");
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
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
    'columns' => array(
        array('header' => $model->attributeLabels()["login"], 'name' => 'login'),
        array('header' => $model->attributeLabels()["nom"], 'name' => 'nom'),
        array('header' => $model->attributeLabels()["prenom"], 'name' => 'prenom'),
        array('header' => $model->attributeLabels()["email"], 'name' => 'email'),
        array(
            'class' => 'CButtonColumn',
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>