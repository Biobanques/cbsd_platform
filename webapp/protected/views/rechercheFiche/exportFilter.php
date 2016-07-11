<?php
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

<h1> Colonnes à exporter </h1>
<br>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
));
?>

<div class="checkboxgroup"> <?php
    echo CHtml::checkBoxList('filter', 'addFilter', Answer::model()->getAllQuestionsByFilter($models), array(
        'labelOptions' => array('style' => 'display:inline'),
        'separator' => '',
        'template' => '<div>{input}&nbsp;{label}</div>'
    ));
    ?>
</div><br>
<?php echo CHtml::submitButton('Exporter', array('name' => 'exporter', 'class' => 'btn btn-default')); ?>
<?php $this->endWidget(); ?>