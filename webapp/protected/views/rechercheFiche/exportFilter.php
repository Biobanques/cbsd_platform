<?php
Yii::app()->clientScript->registerScript('searchView', "
$('#select-all').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;                        
        });
    }
})
");

Yii::app()->clientScript->registerCss('checkBoxListColumn', "
    #filter input {
    float: left;
    margin-right: 10px;
}
.checkboxgroup{
    overflow:auto;
}
.checkboxgroup div{
    width:420px;
    float:left;
} 
");
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
));
?>
<?php 
if (Yii::app()->user->getState('activeProfil') == "Administrateur de projet") {
    echo CHtml::label(Yii::t('common', 'projectName'), 'project', array('required' => 'required'));
    echo " " . CHtml::textField('project');
} 
?>
<br>
<label><input type="checkbox" name="select-all" id="select-all" />&nbsp;&nbsp;&nbsp;<?php echo Yii::t('button', 'selectAll'); ?></label><br>
<div class="checkboxgroup"> 
    <?php
    $fiches = Answer::model()->getNomsFichesByFilter($models);
    echo CHtml::checkBoxList('filter', 'addFilter', Answer::model()->attributeExportedLabels(), array(
            'labelOptions' => array('style' => 'display:inline'),
            'separator' => '',
            'template' => '<div>{input}&nbsp;{label}</div><br>'
        ));
    foreach ($fiches as $key => $value) {
        ?><table><?php
        echo "<h3><u>" . $value . "</u></h3>"; 
        echo CHtml::checkBoxList('filter', 'addFilter', Answer::model()->getAllQuestionsByFilterName($models, $value), array(
            'labelOptions' => array('style' => 'display:inline'),
            'separator' => '',
            'template' => '<tr><td>{input}&nbsp;{label}</td></tr>'
        ));?></table><?php
    }
    ?>
</div><br>
<?php echo CHtml::submitButton('Exporter', array('name' => 'exporter', 'class' => 'btn btn-primary')); ?>
<?php $this->endWidget(); ?>