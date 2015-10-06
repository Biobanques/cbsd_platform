
<div class="question_group">Bloc <?php echo $model->title; ?></div>
<?php
//$this->widget('zii.widgets.CDetailView', array(
//    'data' => $model,
//    'attributes' => array(
//        'title',
//          'questions',
//        '_id'
//    ),
//));
//$this->renderPartial('_preview', array('data' => $model));
QuestionnaireHTMLRenderer::renderQuestionGroupHTML(new Questionnaire(), $model, 'fr', false);
?>