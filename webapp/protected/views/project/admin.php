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
$(function() {
    $(window).scrollTop($('#project-grid').offset().top).scrollLeft($('#project-grid').offset().left);
});
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('project-grid', {
        data: $(this).serialize()
    });
    return false;
});

");
?>
<?php
Yii::app()->clientScript->registerScript('getUnchecked', "
       function getUncheckeds(){
            var unch = [];
            $('[name^=Project_id]').not(':checked,[name$=all]').each(function(){unch.push($(this).val());});
            return unch.toString();
       }
       "
);
?>

<h1><?php echo Yii::t('administration', 'manageProjects'); ?></h1>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'post',
        ));

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'project-grid',
    'dataProvider' => $model->search(),
    'selectableRows'=>2,
    'beforeAjaxUpdate'=>'function(id,options){options.data={checkedIds:$.fn.yiiGridView.getChecked("project-grid","Project_id").toString(),
        uncheckedIds:getUncheckeds()};
        return true;}',

    'ajaxUpdate'=>true,
    'enablePagination' => true,
    'columns' => array(
        array('class' => 'CCheckBoxColumn', 'id' => 'Project_id', 'checked'=>isset($_GET['ajax']) ? 'Yii::app()->user->getState($data->_id)' : '0'),
        array('header' => $model->attributeLabels()["user"], 'name' => 'user'),
        array('header' => $model->attributeLabels()["project_name"], 'name' => 'project_name'),
        array('header' => $model->attributeLabels()["file"], 'name' => 'file'),
        array('header' => $model->attributeLabels()["project_date"], 'name' => 'project_date', 'value' => '$data->getDateProject()'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{import}{delete}',
            'buttons' => array(
                'import' => array(
                    'label' => 'Import CSV file',
                    'imageUrl' => Yii::app()->request->baseUrl . '/images/page_white_csv.png',
                    'url'=>'Yii::app()->createUrl("project/import", array("project_file"=>$data->file))'
                )
            ),
        ),
    )
));
?>

<div class="row">
    <div class="col-lg-5">
        <?php echo CHtml::submitButton(Yii::t('button', 'deleteSelectedProjects'), array('name' => 'rechercher', 'class' => 'btn btn-primary')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>