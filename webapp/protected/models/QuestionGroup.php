<?php

/**
 * classe embarquée Question, définit les objets Question dans les questionnaires
 * @author matthieu
 *
 */
class QuestionGroup extends EMongoEmbeddedDocument {

    public $questionnaire;
    public $id;
    public $title;
    public $title_fr;
    public $questions;

    /**
     * parent group if setted.
     * @var type
     */
    public $parent_group;

    public function behaviors() {
        return array('embeddedArrays' => array(
                'class' => 'ext.YiiMongoDbSuite.extra.EEmbeddedArraysBehavior',
                'arrayPropertyName' => 'questions', // name of property, that will be used as an array
                'arrayDocClassName' => 'Question'  // class name of embedded documents in array
            ),
        );
    }

    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array(
                'id,title',
                'required'
            ),
            array(
                'questionnaire', 'unsafe'
            ),
            array('parent_group', 'safe')
        );
    }

    /**
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('common', 'idQuestionGroup'),
            'title' => 'Titre',
            'title_fr' => 'Titre',
            'parent_group' => 'Groupe parent'
        );
    }

    /**
     * copy attributes of questionBlocForm and questionBloc to QuestionGroup.
     * @param type
     */
    public function copy($questionBlocForm, $questionBloc) {
        $this->id = $questionBlocForm->id;
        $this->title = $questionBloc->title;
        $this->title_fr = $questionBloc->title;
        $this->parent_group = $questionBlocForm->parent_group;
        $this->questions = array();
    }

    /**
     * delete a question into the question group by his idQuestion
     * return true if the question is deleted
     */
    public function deleteQuestion($idQuestion) {
        if ($this->questions != null && count($this->questions) > 0)
            foreach ($this->questions as $key => $question) {
                if ($question->id == $idQuestion) {
                    unset($this->questions[$key]);
                    return true;
                }
            }
        return false;
    }

    public function getArrayGroups() {
        return $this->questionnaire->getArrayGroups();
    }

    public function getOnglets() {
        return $this->questionnaire->getOnglets();
    }

    public function validatewithId($form, $attributes = null, $clearErrors = true) {
        parent::validate($attributes, $clearErrors);
        foreach ($form->questions_group as $group) {
            if ($group->id == $this->id)
                $this->addError('id', Yii::t('common', 'loginExist'));
        }
        return !$this->hasErrors();
    }

}
