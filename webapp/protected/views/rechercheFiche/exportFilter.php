<?php
Yii::app()->clientScript->registerScript('searchView', "
$(document).ready(function() {
    $('.allFields').hide();
});
$('#select-all').click(function(event) {   
    $(':checkbox').each(function() {
            this.checked = true;                        
        });
});
$(document).on('click', 'span', function () {
    var str = this.id;
    var res = str.replace('_Show', '');
    var resBis = res.replace('_Hide', '');
    if (str.search('_Show') != -1) {
        $('#' + resBis).show();
    } else {
        $('#' + resBis).hide();
    }
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
<div id="select-all" style="float:left">&nbsp;<?php echo CHtml::Button(Yii::t('button', 'selectAll'), array('id' => 'selectAll', 'class' => 'btn btn-primary')); ?></div>
<div id="unselect-all">&nbsp;<?php echo CHtml::Button(Yii::t('button', 'unselectAll'), array('id' => 'selectAll', 'class' => 'btn btn-danger')); ?></div>
<div class="checkboxgroup"> 
    <?php
    $fiches = Answer::model()->getNomsFichesByFilter($_SESSION['models']);
    echo "<h3><u>Variables communes</u></h3>";
    ?>
    <?php
    echo CHtml::checkBoxList('filter', 'addFilter', Answer::model()->attributeExportedLabels(), array(
        'labelOptions' => array('style' => 'display:inline'),
        'separator' => '',
        'template' => '<div>{input}&nbsp;{label}</div><br>'
    ));
    ?>
    <?php
        foreach ($fiches as $key => $value) {
            echo "<h3><u>" . $value . "</u><span id=\"" . str_replace(' ', '_', $value) . "_Show\">&#9660;</span><span id=\"" . str_replace(' ', '_', $value) . "_Hide\">&#9650;</span></h3>";
            ?><table id="<?php echo str_replace(' ', '_', $value); ?>" class="allFields">
            <?php
            echo CHtml::checkBoxList('filter', 'addFilter', Answer::model()->getAllQuestionsByFilterName($_SESSION['models'], $value), array(
                'labelOptions' => array('style' => 'display:inline'),
                'separator' => '',
                'template' => '<tr><td>{input}&nbsp;{label}</td></tr>'
            ));
            ?></table><?php
}
?>
</div><br>
<div class="row">
    <div class="col-lg-6">
<?php echo CHtml::submitButton('Exporter', array('name' => 'exporter', 'class' => 'btn btn-primary')); ?>
    </div>
    <div class="col-lg-6">
<?php echo CHtml::submitButton('Exporter les tranches', array('name' => 'exportTranche', 'class' => 'btn btn-primary')); ?>
    </div>
</div>
<?php $this->endWidget(); ?>