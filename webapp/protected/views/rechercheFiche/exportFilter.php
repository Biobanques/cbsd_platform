<?php
Yii::app()->clientScript->registerScript('searchView', "
$('#select-all').click(function(event) {   
    $(':checkbox').each(function() {
            this.checked = true;                        
        });
});
$('#unselect-all').click(function(event) {   
    $(':checkbox').each(function() {
            this.checked = false;                        
        });
})
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
<span class="btn btn-success" id="select-all"><i class="glyphicon glyphicon-ok"></i>&nbsp;<?php echo Yii::t('button', 'selectAll'); ?></span>
<span class="btn btn-danger" id="unselect-all"><i class="glyphicon glyphicon-remove"></i>&nbsp;<?php echo Yii::t('button', 'unselectAll'); ?></span>
<div class="checkboxgroup"> 
    <?php
    $fiches = Answer::model()->getNomsFichesByFilter($models);
    echo "<h3><u>Variables communes</u></h3>";
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
            ));
            ?></table><?php
    }
    ?>
</div><br>
<?php echo CHtml::submitButton('Exporter', array('name' => 'exporter', 'class' => 'btn btn-primary')); ?>
<?php $this->endWidget(); ?>