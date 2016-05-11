<?php

/**
 * Object questionnaire to store a questionnaire definition
 *  * @property integer $id
 * @author nmalservet
 *
 */
class Questionnaire extends EMongoDocument {

    /**
     * champs classiques d echantillons
     */
    public $creator;
    public $type;
    public $id;
    public $name;
    public $name_fr;
    public $description;
    public $message_start;
    public $message_end;
    public $questions_group;
    /*
     * date last modified.
     */
    public $last_modified;

    /**
     * fields to manage add question
     */
// This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

// This method is required!
    public function getCollectionName() {
        return 'questionnaire';
    }

    public function behaviors() {
        return array('embeddedArrays' => array(
                'class' => 'ext.YiiMongoDbSuite.extra.EEmbeddedArraysBehavior',
                'arrayPropertyName' => 'questions_group', // name of property, that will be used as an array
                'arrayDocClassName' => 'QuestionGroup'  // class name of embedded documents in array
            ),
        );
    }

    public function rules() {
        return array(
            array(
                'type,id,name',
                'required'
            ),
            array(
                'id,name,questions_group',
                'safe',
                'on' => 'search'
            ),
            array(
                'description',
                'safe',
                'on' => 'insert'
            ),
        );
    }

    public function attributeLabels() {

        return array(
            'type' => 'Type de formulaire',
            'id' => 'id',
            'name' => 'Nom du formulaire',
            'message_start' => 'Message de début',
            'message_end' => 'Message de fin',
            'contributors' => 'Contributeurs'
        );
    }

    public function attributeExportedLabels() {

        return array(
            'id' => 'id',
            'questions_group' => 'Questions Group',
        );
    }

    public function search($caseSensitive = false) {
        $criteria = new EMongoCriteria ();
        if (isset($this->name) && !empty($this->name))
            $criteria->addCond('name', '==', new MongoRegex('/' . $this->name . '/i'));

        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'name ASC',
            )
        ));
    }

    /**
     * get an array of form type used by dropDownLIst.
     */
    public function getArrayType() {
        $res = array();
        $res ['clinique'] = "clinique";
        $res ['genetique'] = "genetique";
        $res ['neuropathologique'] = "neuropathologique";
        return $res;
    }

    /**
     * get an array sorted by value.
     */
    public function getArrayTypeSorted() {
        $resArraySorted = new ArrayObject($this->getArrayType());
        $resArraySorted->asort();
        return $resArraySorted;
    }

    public function getFiche($activeProfil) {
        if ($activeProfil == "clinicien")
            $typeFiche = "clinique";
        if ($activeProfil == "neuropathologiste")
            $typeFiche = "neuropathologique";
        if ($activeProfil == "geneticien")
            $typeFiche = "genetique";
        $criteria = new EMongoCriteria();
        $criteria->type = $typeFiche;
        $questionnaire = Questionnaire::model()->findAll($criteria);
        $res = array();
        foreach ($questionnaire as $fiche => $value) {
            $res[$value['id']] = $value['name'];
        }
        return $res;
    }

    /**
     * render in html the questionnaire
     */
    public function renderHTML($lang) {
        $result = "";
        foreach ($this->questions_group as $question_group) {
            if ($question_group->parent_group == "") {
                $result.=QuestionnaireHTMLRenderer::renderQuestionGroupHTML($this, $question_group, $lang, false);
                $result.= "<br><div style=\”clear:both;\"></div>";
            }
        }
        $result.=$this->renderContributors();
        return $result;
    }

    public function renderTabbedGroup($lang) {
        return QuestionnaireHTMLRenderer::renderTabbedGroup($this, $lang, false);
    }

    /**
     * Restitue le formulaire en mode edition. Permet de le modifier
     * @param type $lang
     * @return type
     */
    public function renderTabbedGroupEditMode($lang) {
        return QuestionnaireHTMLRenderer::renderTabbedGroupEditMode($this, $lang);
    }

    /**
     * update questionnaire with fields filled.
     * Add question group if necessary
     */
    public function updateForm($questionnaireGroupForm) {
        $result = false;
        //check if fields required are filled
        if ($questionnaireGroupForm->validate()) {
            $qg = new QuestionGroup;
            $qg->id = $questionnaireGroupForm->formQuestionGroupId;
            $qg->title = $questionnaireGroupForm->formQuestionGroupTitle;
            $qg->title_fr = $questionnaireGroupForm->formQuestionGroupTitleFr;
            $this->questions_group[] = $qg;
            $this->save();
            $result = true;
        }
        return $result;
    }

    /**
     * render contributors
     * used in plain page and tab page
     * @return string
     */
    public function renderContributors() {
        return QuestionnaireHTMLRenderer::renderContributors($this->contributors);
    }

    /**
     * get the last modified value into a french date format JJ/MM/AAAA
     * @return type
     */
    public function getLastModified() {
        return date("d/m/Y", $this->last_modified->sec);
    }

    /**
     * methode pour ajouter un groupe de question en fin de groupes
     * @param type $title
     */
    public function addQuestionGroup($id, $title) {
        $qg = new QuestionGroup;
        $qg->id = $id;
        $qg->title = $title;
        $qg->title_fr = $title;
        $this->questions_group[] = $qg;
    }

    /**
     * get array questions for a questionnaire
     * filtered by idQuestionGroup
     */
    public function getArrayQuestions($idQuestionGroup) {
        $res = array();
        if ($this->questions_group != null) {
            foreach ($this->questions_group as $group) {
                if ($group->id == $idQuestionGroup)
                    if ($group->questions != null) {
                        foreach ($group->questions as $question) {
                            $res [$question->id] = $question->label;
                        }
                    }
            }
        }
        return $res;
    }

    /**
     * get array groups for a questionnaire
     */
    public function getArrayGroups() {
        $res = array();
        if ($this->questions_group != null) {
            foreach ($this->questions_group as $group) {
                $res [$group->id] = $group->title != "" ? $group->title : $group->title_fr;
            }
        }
        return $res;
    }

    public function getOnglets() {
        $res = array();
        if ($this->questions_group != null) {
            foreach ($this->questions_group as $group) {
                if ($group->parent_group == null)
                    $res [$group->id] = $group->title != "" ? $group->title : $group->title_fr;
            }
        }
        return $res;
    }

    public function getGroups() {
        $res = array();
        if ($this->questions_group != null) {
            foreach ($this->questions_group as $group) {
                if ($group->parent_group != null)
                    $res [$group->id] = $group->title != "" ? $group->title : $group->title_fr;
            }
        }
        return $res;
    }

    public function getGroupsInOnglet($parent) {
        $res = array();
        if ($this->questions_group != null) {
            foreach ($this->questions_group as $group) {
                if ($group->parent_group != null && $group->parent_group == $parent)
                    $res [$group->id] = $group->title != "" ? $group->title : $group->title_fr;
            }
        }
        return $res;
    }

    /**
     * delete a question into the questionnaire by his id
     * true if the question is deleted
     */
    public function deleteQuestion($idQuestion) {
        if ($this->questions_group != null && count($this->questions_group) > 0) {
            foreach ($this->questions_group as $group) {
                if ($group->deleteQuestion($idQuestion)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * delete a question group  by his idQuestionGroup
     * return true if the question group is deleted
     */
    public function deleteQuestionGroup($idQuestionGroup) {
        if ($this->questions_group != null && count($this->questions_group) > 0) {
            foreach ($this->questions_group as $key => $group) {
                if ($group->id == $idQuestionGroup) {
                    unset($this->questions_group[$key]);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * save a new group into the questionnaire
     * si pas de positionnement on ajoute la questionen fin du premier groupe
     * @param questionnaire
     */
    public function saveQuestionnaireNewGroup($questionGroup) {
        $this->last_modified = new MongoDate();
        if ($questionGroup != null) {

            //sinon positionnement relatif
            if ($this->questions_group != null) {
                $this->questions_group[] = $questionGroup;
            } else {
                $this->questions_group = array();
                $this->questions_group[] = $questionGroup;
            }
        }
        if ($this->save())
            Yii::app()->user->setFlash('success', "L'onglet a bien été ajouté dans le formulaire.");
        else {
            Yii::app()->user->setFlash('error', "L'onglet n'a pas été enregistré. Un problème est apparu.");
            Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
        }
        return $this;
    }

    public function saveQuestionnaireNewQuestion($questionForm) {
        $this->last_modified = new MongoDate();
        $cquestion = new Question;
        $cquestion->setAttributesByQuestionForm($questionForm);
        Yii::log("save questionnaire", CLogger::LEVEL_TRACE);
        //si pas de position fournie, on ajoute la question a la fin, dans le premier groupe de question
        if (!isset($questionForm->idQuestionBefore) || empty($questionForm->idQuestionBefore)) {
            if ($this->questions_group != null && count($this->questions_group) > 0) {
                foreach ($this->questions_group as $group) {
                    if ($group->id == $questionForm->idQuestionGroup) {
                        if ($group->questions == null) {
                            $group->questions = array();
                            $group->questions[] = $cquestion;
                        } else {
                            array_unshift($group->questions, $cquestion);
                        }
                    }
                }
            }
        } else {
            //sinon positionnement relatif
            if ($this->questions_group != null) {
                foreach ($this->questions_group as $group) {
                    if ($group->questions != null) {
                        foreach ($group->questions as $key => $question) {
                            if ($question->id == $questionForm->idQuestionBefore) {
                                array_splice($group->questions, ($key + 1), 0, array($cquestion));
                            }
                        }
                    }
                }
            }
        }
        if ($this->save())
            Yii::app()->user->setFlash('success', "La question a bien été ajouté dans le formulaire.");
        else {
            Yii::app()->user->setFlash('error', "La question n'a pas été enregistré. Un problème est apparu.");
            Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
        }
        return $this;
    }

    public function saveQuestionnaireNewQuestionBloc($questionForm) {
        $this->last_modified = new MongoDate();
        $cquestion = new Question;
        $cquestion->setAttributesByQuestionForm($questionForm);
        Yii::log("save questionnaire", CLogger::LEVEL_TRACE);
        //si pas de position fournie, on ajoute la question a la fin, dans le premier groupe de question
        if (!isset($questionForm->idQuestionBefore) || empty($questionForm->idQuestionBefore)) {
            if ($this->questions_group != null && count($this->questions_group) > 0) {
                foreach ($this->questions_group as $group) {
                    if ($group->id == $questionForm->idQuestionGroup) {
                        if ($group->questions == null) {
                            $group->questions = array();
                            $group->questions[] = $cquestion;
                        } else {
                            array_push($group->questions, $cquestion);
                        }
                    }
                }
            }
        } else {
            //sinon positionnement relatif
            if ($this->questions_group != null) {
                foreach ($this->questions_group as $group) {
                    if ($group->questions != null) {
                        foreach ($group->questions as $key => $question) {
                            if ($question->id == $questionForm->idQuestionBefore) {
                                array_splice($group->questions, ($key + 1), 0, array($cquestion));
                            }
                        }
                    }
                }
            }
        }
        if ($this->save())
            Yii::app()->user->setFlash('success', "Le bloc de questions a bien été ajouté dans le formulaire.");
        else {
            Yii::app()->user->setFlash('error', "Le bloc de questions n'a pas été enregistré. Un problème est apparu.");
            Yii::log("pb save answer" . print_r($answer->getErrors()), CLogger::LEVEL_ERROR);
        }
        return $this;
    }

}
