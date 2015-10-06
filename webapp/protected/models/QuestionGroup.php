<?php

/**
 * classe embarquée Question, définit les objets Question dans les questionnaires
 * @author matthieu
 *
 */
class QuestionGroup extends EMongoEmbeddedDocument
{
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
    /**
     * display rule
     * condition to display the question group
     * @return type
     */
    public $display_rule;

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
            'id' => 'id',
            'title' => 'titre',
            'title_fr' => 'titre',
            'parent_group' => 'Groupe parent'
        );
    }

    /**
     * TODO : display rule dinammcally ( with JS)
     * make the javascript display rule.
     */
    public function makeDisplayRule() {

    }

    /**
     * delete a question into the question group by his idQuestion
     * return true if the question is deleted
     */
    public function deleteQuestion($idQuestion) {
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

}