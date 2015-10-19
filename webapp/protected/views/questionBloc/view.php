<h3 align="center">Bloc <?php echo $model->title; ?></h3>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'title',
        'questions',
        '_id'
    ),
));
?>