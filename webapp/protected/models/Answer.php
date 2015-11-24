<?php

/**
 * Object answer to store a questionnaire definition + answers
 * Copy of object questionnaire to prevent problems of update with questionnaire and forwar compatibility
 * @property integer $id
 * @author nmalservet
 *
 */
class Answer extends EMongoDocument {

    /**
     * 
     */
// This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

// This method is required!
    public function getCollectionName() {
        return 'answer';
    }

    public $creator;

    /**
     * equal id of the questionnaire
     * @var type 
     */
    public $id;

    /*
     * form type: clinique, neuropathologique, genetique
     */
    public $type;

    /**
     * user id mongo unique login filling this answer.
     * TODO refactor this attribute id au lieu de login
     */
    public $login;

    /**
     * user unique patient id filling this answer.
     */
    public $id_patient;

    /*
     * unique id of the questionnaire
     */
    public $questionnaireMongoId;

    /**
     * nom du formulaire
     * @var type 
     */
    public $name;

    /**
     * field last modified from the questionnaire source.
     * @var type 
     */
    public $last_modified;
    public $description;
    public $message_start;
    public $message_end;
    public $answers_group;
    /*
     * last date of save action
     */
    public $last_updated;

    /**
     * contributors are people working on thi squetsionnaire
     */
    public $contributors;

    public function behaviors() {
        return array('embeddedArrays' => array(
                'class' => 'ext.YiiMongoDbSuite.extra.EEmbeddedArraysBehavior',
                'arrayPropertyName' => 'answers_group', // name of property, that will be used as an array
                'arrayDocClassName' => 'AnswerGroup'  // class name of embedded documents in array
            ),
        );
    }

    public function rules() {
        return array(
            array(
                'id,login,questionnaireMongoId',
                'required'
            ),
            array(
                'id,name,answers_group',
                'safe',
                'on' => 'search'
            )
        );
    }

    public function attributeLabels() {

        return array(
            'id' => 'Id',
            'name' => 'Nom',
            'last_updated' => 'Date de saisie',
            'last_modified' => 'Date de l\'examen',
        );
    }

    public function attributeExportedLabels() {
        return array(
            'id_patient' => 'N° anonymat',
            'id' => 'N° fiche',
            'name' => 'Nom de la fiche',
            'type' => 'Type de fiche',
            'last_updated' => 'Date de saisie',
            'last_modified' => 'Date de l\'examen',
        );
    }

    public function search($caseSensitive = false) {
        $criteria = new EMongoCriteria;

        if (isset($this->login) && !empty($this->login)) {
            $criteriaUser = new EMongoCriteria;
            $criteriaUser->_id = $this->login;
            $user = User::model()->findAll($criteriaUser);
            foreach ($user as $k => $v) {
                if ($k == "nom") {
                    $this->login = $v;
                    $criteria->addCond('login', '==', new MongoRegex('/' . $v . '/i'));
                }
            }
            //$criteria->addCond('nom', '==', new MongoRegex('/' . $nom . '/i'));
        }

        if (isset($this->id_patient) && !empty($this->id_patient))
            $criteria->addCond('id_patient', '==', new MongoRegex('/' . $this->id_patient . '/i'));

        if (isset($this->name) && !empty($this->name))
            $criteria->addCond('name', '==', new MongoRegex('/' . $this->name . '/i'));

        if (isset($this->type) && !empty($this->type))
            $criteria->addCond('type', '==', new MongoRegex('/' . $this->type . '/i'));

        //if (isset($this->last_modified) && !empty($this->last_modified))
        //$criteria->addCond('last_modified', '==', new MongoRegex('/' . date("d/m/Y H:m", strtotime($this->last_modified->sec)) . '/i'));
        //$criteria->addCond('last_modified', '==', date("d/m/Y H:m", strtotime($this->last_modified->sec)));
        //if (isset($this->last_updated) && !empty($this->last_updated))
        //$criteria->addCond('last_updated', '==', new MongoRegex('/' . $this->getLastUpdated() . '/i'));

        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    /**
     * render in html the questionnaire
     */
    public function renderHTML($lang) {
        $result = "";
        foreach ($this->answers_group as $answer_group) {
            $result.=AnswerHTMLRenderer::renderAnswerGroupHTML($this, $answer_group, $lang);
            $result.= "<br><div style=\”clear:both;\"></div>";
        }
        return $result;
    }

    public function renderTabbedGroup($lang) {
        return QuestionnaireHTMLRenderer::renderTabbedGroup($this, $lang, true);
    }

    /**
     * copy attributes of questionnaire recursively to the final state answer-question.
     * @param type $questionnaire
     */
    public function copy($questionnaire) {
        $this->id = $questionnaire->id;
        $this->questionnaireMongoId = $questionnaire->_id;
        $this->name = $questionnaire->name;
        $this->description = $questionnaire->description;
        $this->message_start = $questionnaire->message_start;
        $this->message_end = $questionnaire->message_end;
        $this->last_modified = $questionnaire->last_modified;
        foreach ($questionnaire->questions_group as $question_group) {
            $answerGroup = new AnswerGroup;
            $answerGroup->copy($question_group);
            $this->answers_group[] = $answerGroup;
        }
    }

    /**
     * render contributors
     * used in plain page and tab page
     * @return string
     */
    public function renderContributors() {
        return QuestionnaireHTMLRenderer::renderContributors($this->contributors);
    }

    public function getFicheName() {
        $result = "";
        $fiche = Answer::model()->findByPk(new MongoID($this->_id));
        if ($fiche != null)
            $result = $fiche->name;
        return $result;
    }

    /**
     * get the last modified value into a french date format JJ/MM/AAAA
     * @return type
     */
    public function getLastModified() {
        if ($this->last_modified != null)
            return date("d/m/Y H:m", $this->last_modified->sec);
        else
            return null;
    }

    /**
     * get the last updatedvalue into a french date format JJ/MM/AAAA
     * @return type
     */
    public function getLastUpdated() {
        if ($this->last_updated != null)
            return date("d/m/Y H:m", $this->last_updated->sec);
        else
            return null;
    }

    /**
     * retourne le user qui a renseigné le formulaire
     * @return type
     */
    public function getUserRecorderName() {
        $result = "-";
        $user = User::model()->findByPk(new MongoID($this->login));
        if ($user != null)
            $result = $user->nom;
        return $result;
    }

    /**
     * retourne l'id de user qui a renseigné la fiche
     * @return type
     */
    public function getUserId() {
        return $this->login;
    }

    /**
     * retourne la liste de toutes les questions de toutes les fiches
     * @return type
     */
    public function getAllQuestions() {
        $result = array();
        $fiche = Answer::model()->findAll();
        foreach ($fiche as $key => $value) {
            foreach ($value as $k => $v) {
                if ($k == "answers_group") {
                    foreach ($v as $i => $j) {
                        foreach ($j as $k => $l) {
                            if ($k == "answers") {
                                foreach ($l as $label => $test) {
                                    foreach ($test as $a => $b) {
                                        if ($a == "label_fr") {
                                            if (!in_array($b, $result)) {
                                                $result[] = $b;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * method to convert a result set provided by a search to an array.
     * Each tree leaf will be converted to an array representation
     * Example :
     * a - 1 - x
     *       - y
     *       - z
     *   - 2
     *   - 3
     * 
     * Will produce :
     * [a.1.x] [a.1.y] [a.1.z] [a.2] [a.3] 
     * 
     * //champs communs par defaut pour chaque ligne
     * 'id_patient' => 'N° anonymat',
     *       'id' => 'N° fiche',
     *       'name' => 'Nom de la fiche',
     *       'type' => 'Type de fiche',
     *       'last_updated' => 'Date de saisie',
     *       'last_modified' => 'Date de l\'examen',
     * 
     * @param $models : list of answers
     * @result array : each line = each model answer
     */
    public function resultToArray($models) {
        $result = array();
        $headerLineFixe =$this->attributeExportedLabels();
        $answersList = array();
        foreach ($models as $answer) {
            //chaque ligne est un tableau de colonne
            $currentAnswer = array();
            $currentAnswer['id_patient'] = $answer->id_patient;
            $currentAnswer['id'] = $answer->id;
            $currentAnswer['name'] = $answer->name;
            $currentAnswer['type'] = $answer->type;
            $currentAnswer['last_updated'] = $answer->getLastUpdated();
            $currentAnswer['last_modified'] = $answer->getLastModified();
            //parcours de chaque sous  groupe poru recuperer les feuilles de l arbre
            //et ainsi reconstruire uen ligne par 
            //tableau associatif de label/question
            //pretraitement pour reconstruire par la suite les entetes de colonnes
            $answersQuestions = array();
            foreach ($answer->answers_group as $group) {
                foreach ($group->answers as $answerQuestion) {
                    //construction du label de colonne 
                    $label = "[".$answer->name . "][" . $group->title_fr . "][" . $answerQuestion->label."]";
                    $value = $answerQuestion->getLiteralAnswer();
                    $ansQuestion[] = array();
                    $ansQuestion['label'] = $label;
                    $ansQuestion['answer'] = $value;
                    $answersQuestions[]=$ansQuestion;
                }
            }
            $currentAnswer['questions'] = $answersQuestions;
            //reconstruction de la 
            $answersList[] = $currentAnswer;
        }
        //formattage des reponses en ligne
        //preparation de la ligne d entete pour touts
        //pour chaque nouveau label de question, on ajoute une colonne
        $headerLineDynamic = array();
        foreach ($answersList as $cAnswer) {
            foreach ($cAnswer['questions'] as $qAnswer) {
                if (!in_array($qAnswer['label'], $headerLineDynamic)) {
                    $headerLineDynamic[] = $qAnswer['label'];
                }
            }
        }
        //formatage de chaque ligne
        $headerLine=array_merge($headerLineFixe,$headerLineDynamic);
        $result[]=$headerLine;
        foreach ($answersList as $cAnswer) {
            $resultLine = array();
            $resultLine[] = $cAnswer['id_patient'];
            $resultLine[] = $cAnswer['id'];
            $resultLine[] = $cAnswer['name'];
            $resultLine[] = $cAnswer['type'];
            $resultLine[] = $cAnswer['last_updated'];
            $resultLine[] = $cAnswer['last_modified'];
            $cQuestions = $cAnswer['questions'];
            //ajout des valeurs à la ligne, si aucune valeur existante pour cette column, ajoute null
           
            foreach ($headerLineDynamic as $columnHeader) {
                $valueExists=false;
                foreach($cQuestions as $cQuestion){
                    if($cQuestion['label']==$columnHeader){
                        $resultLine[] = $cQuestion['answer'];
                        $valueExists=true;
                        break;
                    }
                }
                if (!$valueExists) {
                    $resultLine[] = 'null';
                }
            }
            $result[] = $resultLine;
        }
        return $result;
    }

}

?>