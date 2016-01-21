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

<h1>Gestion des utilisateurs</h1>
<?php echo CHtml::link('Créer un utilisateur', array('user/create')); ?>
<br />
<?php echo CHtml::link('Recherche avancée', '#', array('class' => 'search-button')); ?>
<div class="search-form" style="display:none">
    <?php
    $this->renderPartial('_search', array(
        'model' => $model,
    ));
    ?>
</div><!-- search-form -->

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'user-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $model->search(),
    'columns' => array(
        'login',
        'nom',
        'prenom',
        'email',
        array(
            'class' => 'CButtonColumn',
            'htmlOptions' => array('style' => 'width: 70px')
        ),
    ),
));
?>