<?php
$this->breadcrumbs=array(
	'Question Blocs',
);

$this->menu=array(
	array('label'=>'Create QuestionBloc', 'url'=>array('create')),
	array('label'=>'Manage QuestionBloc', 'url'=>array('admin')),
);
?>

<h1>Question Blocs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>