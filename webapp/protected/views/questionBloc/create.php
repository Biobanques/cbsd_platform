<?php
$this->breadcrumbs = array(
    'Question Blocs' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List QuestionBloc', 'url' => array('index')),
    array('label' => 'Manage QuestionBloc', 'url' => array('admin')),
);
?>

<h1>Créer un bloc de questions</h1>

<?php echo $this->renderPartial('_form_question_bloc', array('model' => $model, 'questionForm' => $questionForm));
?>