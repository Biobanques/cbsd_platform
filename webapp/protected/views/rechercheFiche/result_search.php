<h1> Voir les fiches associées à ces patients </h1>

<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'type' => 'striped bordered condensed',
    'dataProvider' => $dataProvider,
    'template' => "{items}",
    'columns' => array(
        'id_patient',
        'id',
        'name',
        'type',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'buttons' => array(
                'view',
                'update',
                'delete'
            ),
            'htmlOptions' => array('style' => 'width: 70px'),
        ),
    ),
));

echo CHtml::link('Exporter en CSV', array('rechercheFiche/exportCsv'), array('class' => 'btn btn-default')); ?>