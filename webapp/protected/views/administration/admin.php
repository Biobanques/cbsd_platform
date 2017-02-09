<h1><?php echo Yii::t('common', 'manageRules'); ?></h1>

<h3><?php echo Yii::t('common', 'patientClinical') ?></h3>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProviderClinique,
    'columns' => array(
        array('header' => $model->attributeLabels()["profil"], 'name' => 'profil'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}',
        ),
    ),
));
?>

<hr />

<h3><?php echo Yii::t('common', 'patientNeuropathologist') ?></h3>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProviderNeuropath,
    'columns' => array(
        array('header' => $model->attributeLabels()["profil"], 'name' => 'profil'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}',
        ),
    ),
));
?>

<hr />

<h3><?php echo Yii::t('common', 'patientGeneticist') ?></h3>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProviderGene,
    'columns' => array(
        array('header' => $model->attributeLabels()["profil"], 'name' => 'profil'),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}',
        ),
    ),
));
?>