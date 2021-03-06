<?php

/**
 * Object answer to store a questionnaire definition + answers
 * Copy of object questionnaire to prevent problems of update with questionnaire and forwar compatibility
 * @property integer $idA
 * @author nmalservet
 *
 */
class AnswerBis extends LoggableActiveRecord {

    /**
     *
     */
// This has to be defined in every model, this is same as with standard Yii ActiveRecord
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

// This method is required!
    public function getCollectionName() {
        return 'answerBis';
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
     * @var MongoDate
     */
    public $last_modified;
    public $description;
    public $answers_group;

    /**
     * last date of save action
     */
    public $last_updated;
    public $last_updated_to;

    /**
     * Working variable to add dynamic search filters
     * @var array
     */
    public $dynamics;
    public $compare;
    public $condition;
    public $available;

    /**
     * use for search by user name
     * @var string
     */
    public $user;

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
                'id,name,answers_group,login,type,id_patient,dynamics,compare,condition,last_updated,last_updated_to,user',
                'safe',
                'on' => 'search'
            )
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'Identifiant de la fiche',
            'id_patient' => Yii::t('common', 'anonymat'),
            'name' => Yii::t('common', 'formName'),
            'type' => Yii::t('common', 'formType'),
            'last_updated' => Yii::t('common', 'entryDate'),
            'last_modified' => 'Date de mise à jour du questionnaire',
            'user' => Yii::t('common', 'login'),
            'examDate' => Yii::t('common', 'examDate'),
        );
    }

    public function attributeExportedLabels() {
        return array(
            'id_patient' => 'N° anonymat',
            'id' => 'Identifiant de la fiche',
            'name' => 'Nom de la fiche',
            'type' => 'Type de fiche',
            'last_updated' => 'Date de saisie',
            'last_modified' => 'Date de mise à jour du questionnaire',
        );
    }

    /**
     * render in html the questionnaire
     */
    public function renderHTML($lang) {
        $result = "";
        foreach ($this->answers_group as $answer_group) {
            $result .= AnswerHTMLRenderer::renderAnswerGroupHTML($this, $answer_group, $lang);
            $result .= "<br><div style=\”clear:both;\"></div>";
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
        $this->last_modified = $questionnaire->last_modified;
        foreach ($questionnaire->questions_group as $question_group) {
            $answerGroup = new AnswerGroup;
            $answerGroup->copy($question_group);
            $this->answers_group[] = $answerGroup;
        }
    }

    /**
     * get the last modified value into a french date format JJ/MM/AAAA
     * @return type
     */
    public function getLastModified() {
        if ($this->last_modified != null) {
            return date(CommonTools::FRENCH_SHORT_DATE_FORMAT, strtotime($this->last_modified['date']));
        } else {
            return null;
        }
    }

    /**
     * get the last updatedvalue into a french date format JJ/MM/AAAA
     * @return type
     */
    public function getLastUpdated() {
        if ($this->last_updated != null) {
            return date('d/m/Y H:i', strtotime($this->last_updated['date']));
        } else {
            return null;
        }
    }

    /**
     * retourne le user qui a renseigné le formulaire
     * @return type
     */
    public function getUserRecorderName() {
        $result = "-";
        $user = User::model()->findByPk(new MongoID($this->login));
        if ($user != null) {
            $result = ucfirst($user->prenom) . " " . strtoupper($user->nom);
        }
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
     * retourne toutes les noms des fiches
     * @return type
     */
    public function getNomsFiches() {
        $res = array();
        $fiches = Answer::model()->findAll();
        foreach ($fiches as $fiche) {
            if (!in_array($fiche->name, $res)) {
                $res[$fiche->name] = $fiche->name;
            }
        }
        asort($res, SORT_NATURAL | SORT_FLAG_CASE);
        return $res;
    }

    public function getNomsFichesByFilter($models) {
        $res = array();
        foreach ($models as $fiche) {
            if (!in_array($fiche->name, $res)) {
                $res[$fiche->name] = $fiche->name;
            }
        }
        asort($res, SORT_NATURAL | SORT_FLAG_CASE);
        return $res;
    }

    /**
     * retourne l'id max lors de l'ajout des gènes
     * @return type
     */
    public function getMaxIdGene($nbMax, $answerQuestionId) {
        $nb = preg_replace("/[^0-9]/", "", $answerQuestionId);
        return ($nbMax < $nb) ? $nbMax = $nb : $nb;
    }

    /**
     * add gene to AnswerQuestion model
     * @return type
     */
    public function addGene($nbMax, $gene) {
        $gene->id = "gene" . $nbMax;
        $gene->label = "Nom du gène";
        $gene->label_fr = "Nom du gène";
        $gene->type = "input";
        $gene->style = "";
        $gene->values = "";
        $gene->values_fr = "";
        $gene->answer = "";
        $gene->precomment = "";
        $gene->precomment_fr = "";

        return $gene;
    }

    /**
     * add analyse to AnswerQuestion model
     * @return type
     */
    public function addAnalyse($nbMax, $analyse) {
        $analyse->id = "analyse" . $nbMax;
        $analyse->label = "Analysé";
        $analyse->label_fr = "Analysé";
        $analyse->type = "radio";
        $analyse->style = "float:right;";
        $analyse->values = "Oui,Non";
        $analyse->values_fr = "";
        $analyse->answer = "Non";
        $analyse->precomment = "";
        $analyse->precomment_fr = "";

        return $analyse;
    }

    /**
     * add mutation to AnswerQuestion model
     * @return type
     */
    public function addMutation($nbMax, $mutation) {
        $mutation->id = "mutation" . $nbMax;
        $mutation->label = "Mutation(s)";
        $mutation->label_fr = "Mutation(s)";
        $mutation->type = "input";
        $mutation->style = "";

        return $mutation;
    }

    /**
     * add comment to AnswerQuestion model
     * @return type
     */
    public function addComment($nbMax, $comment) {
        $comment->id = "comment" . $nbMax;
        $comment->label = "Commentaire";
        $comment->label_fr = "Commentaire";
        $comment->type = "input";
        $comment->style = "float:right;";

        return $comment;
    }

    /**
     * Ajoute les 4 champs "gene", "analyse", "mutation", "commentaire" dans les réponses
     * @return type
     */
    public function addGeneToAnswers($answerGroup, $gene, $analyse, $mutation, $comment) {
        foreach ($answerGroup as $answer_group) {
            if ($answer_group->id == "gene") {
                $answer_group->answers[] = $gene;
                $answer_group->answers[] = $analyse;
                $answer_group->answers[] = $mutation;
                $answer_group->answers[] = $comment;
            }
        }
    }

    /**
     * retourne la liste de toutes les questions de toutes les fiches
     * @return type
     */
    public function getAllQuestions() {
        $result = array();
        $answers = $this->getAllDetailledQuestions();
        foreach ($answers as $answer) {
            //$result[$answer->answer->id] = "[" . $answer->fiche . "][" . $answer->group . "] " . $answer->answer->label_fr;
            $result[$answer->answer->id] = "[" . $answer->group . "] " . $answer->answer->label_fr;
        }
        natcasesort($result);
        return $result;
    }

    /**
     * retourne la liste de toutes les questions en fonction des types de fiches sélectionnées
     * @return type
     */
    public function getAllQuestionsByTypeForm($typeForm) {
        $result = array();
        $answers = $this->getAllDetailledQuestionsByTypeForm($typeForm);
        if (!empty($answers)) {
            foreach ($answers as $answer) {
                $result[$answer->answer->id] = "[" . $answer->fiche . "] " . $answer->answer->label_fr;
            }
            natcasesort($result);
        }
        return $result;
    }

    public function getAllDetailledQuestionsByTypeForm($typeForm) {
        $result = array();
        if ($typeForm != null) {
            $criteria = new EMongoCriteria;
            $criteria->name = $typeForm;
            $fiches = Answer::model()->findAll($criteria);
        } else {
            $fiches = Answer::model()->findAll();
        }
        foreach ($fiches as $fiche) {
            foreach ($fiche->answers_group as $group) {
                foreach ($group->answers as $answer) {
                    $toAdd = new stdClass();
                    $toAdd->answer = $answer;
                    $toAdd->fiche = $fiche->name;
                    $toAdd->group = $group->title_fr;
                    $result[] = $toAdd;
                }
            }
        }
        return $result;
    }

    public function getAllQuestionsByFilterName($model, $name) {
        $result = array();
        $models = $this->getAllQuestionsByFilter($model);
        foreach ($models as $answer => $value) {
            $pattern = '`\((.+?)\)`';
            $subject = $value;
            preg_match($pattern, $subject, $matches);
            if ($name == $matches[1]) {
                $result[$answer] = str_replace($matches[0], '', $value);
            }
        }
        return $result;
    }

    /**
     * retourne la liste de toutes les questions de toutes les fiches
     * @return type
     */
    public function getAllQuestionsByFilter($model) {
        $result = array();
        $answers = $this->getAllDetailledQuestionsByFilter($model);
        foreach ($answers as $answer) {
            //$result[$answer->answer->id] = "[" . $answer->fiche . "][" . $answer->group . "] " . $answer->answer->label_fr;
            $result["(" . $answer->answer->id . ")" . $answer->answer->label_fr] = "(" . $answer->fiche . ")" . $answer->answer->label_fr . " <font color='#0C5D86'>(" . $answer->answer->id . ")</font>";
        }
        natcasesort($result);
        return $result;
    }

    public function getAllQuestionsByFilterBis($model) {
        $result = array();
        $answers = $this->getAllDetailledQuestionsByFilter($model);
        foreach ($answers as $answer) {
            $result[$answer->answer->id] = "(" . $answer->fiche . ")" . $answer->answer->label_fr;
        }
        natcasesort($result);
        return $result;
    }

    public function getAllAnswersByFilter($model, $idQuestion) {
        $result = array();
        $answers = $this->getAllDetailledQuestionsByFilter($model);
        foreach ($answers as $answer) {
            if ($idQuestion == $answer->answer->id) {
                if ($answer->answer->type == "date") {
                    $result[$answer->answer->answer['date']] = CommonTools::formatDateAndTimeFR($answer->answer->answer['date']);
                } elseif ($answer->answer->type == "checkbox") {
                    if (is_array($answer->answer->answer)) {
                        foreach ($answer->answer->answer as $k => $v) {
                            $result[$v] = $v;
                        }
                    }
                } else {
                    $result[$answer->answer->answer] = $answer->answer->answer;
                }
            }
        }
        natcasesort($result);
        return $result;
    }

    public function getAllDetailledQuestionsByFilter($fiches) {
        $result = array();
        foreach ($fiches as $fiche) {
            foreach ($fiche->answers_group as $group) {
                foreach ($group->answers as $answer) {
                    $toAdd = new stdClass();
                    $toAdd->answer = $answer;
                    $toAdd->fiche = $fiche->name;
                    $toAdd->group = $group->title_fr;
                    $result[] = $toAdd;
                }
            }
        }
        return $result;
    }

    /**
     * retourne la liste de toutes les questions de toutes les fiches
     * @return type
     */
    public function getTypeQuestionByLabel($label) {
        $type = "";
        $criteria = new EMongoCriteria;
        $criteria->answers_group->answers->label = $label;
        $fiches = Answer::model()->findAll($criteria);
        foreach ($fiches as $fiche) {
            foreach ($fiche->answers_group as $group) {
                foreach ($group->answers as $answer) {
                    if ($answer->label == $label) {
                        $type = $answer->type;
                    }
                }
            }
        }
        return $type;
    }

    public function findAllDetailledQuestionById($id) {
        $result = null;
        foreach ($this->getAllDetailledQuestions() as $question) {
            if ($question->answer->id == $id) {
                $result = $question->answer;
            }
        }
        return $result;
    }

    public function getAllDetailledQuestions() {
        $result = array();
        $fiches = Answer::model()->findAll();
        foreach ($fiches as $fiche) {
            foreach ($fiche->answers_group as $group) {
                foreach ($group->answers as $answer) {
                    $toAdd = new stdClass();
                    $toAdd->answer = $answer;
                    $toAdd->fiche = $fiche->name;
                    $toAdd->group = $group->title_fr;
                    $result[] = $toAdd;
                }
            }
        }
        return $result;
    }

    public function getAnswerByQuestionId($id) {
        $result = null;
        foreach ($this->answers_group as $group) {
            foreach ($group->answers as $answer) {
                if ($answer->id == $id) {
                    if ($answer->type != "date") {
                        $result = $answer->answer;
                    } else {
                        $result = date(CommonTools::FRENCH_SHORT_DATE_FORMAT, strtotime($answer->answer['date']));
                    }
                }
            }
        }
        return $result;
    }

    /**
     * retourne la liste de toutes les labels des questions en fonction de l'id de la question
     * @return type
     */
    public function getLabelQuestionById($id) {
        $label = "";
        $criteria = new EMongoCriteria;
        $criteria->answers_group->answers->id = $id;
        $fiches = Answer::model()->findAll($criteria);
        foreach ($fiches as $fiche) {
            foreach ($fiche->answers_group as $group) {
                foreach ($group->answers as $answer) {
                    if ($answer->id == $id) {
                        $label = $answer->label;
                    }
                }
            }
        }
        return $label;
    }

    /**
     * retourne toutes les id patient des fiches
     * @return type
     */
    public function getIdPatientFiches() {
        $res = array();
        $fiches = Answer::model()->findAll();
        foreach ($fiches as $fiche) {
            if (!in_array($fiche->id_patient, $res)) {
                $res[$fiche->id_patient] = $fiche->id_patient;
            }
        }
        asort($res, SORT_NUMERIC);
        return $res;
    }

    public function getUserFicheById($idFiche) {
        $fiche = Answer::model()->findByPk(new MongoId($idFiche));
        return $fiche->login;
    }

    /**
     * retourne toutes les noms des utilisateurs qui ont renseigné les fiches
     * @return type
     */
    public function getNamesUsers() {
        $res = array();
        $fiches = Answer::model()->findAll();
        foreach ($fiches as $fiche) {
            $criteria = new EMongoCriteria;
            $criteria->_id = new MongoId($fiche->login);
            $user = User::model()->find($criteria);
            if (!in_array($user->nom, $res)) {
                $res[$user->nom] = $user->prenom . " " . $user->nom;
            }
        }
        asort($res, SORT_NATURAL | SORT_FLAG_CASE);
        return $res;
    }

    public function getAllTypes() {
        $result = array();
        $types = Answer::model()->getCollection()->distinct("type");
        foreach ($types as $type) {
            $result[$type] = $type;
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
    public function resultToArray($models, $filter) {
        $result = array();
        $ansQuestion = array();
        $headerLineFixe = $this->attributeExportedLabels();
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
                    $label = "(" . $answerQuestion->id . ")" . $answerQuestion->label;
                    $value = $answerQuestion->getLiteralAnswer();
                    $ansQuestion['label'] = $label;
                    $ansQuestion['answer'] = $value;
                    $answersQuestions[] = $ansQuestion;
                }
            }
            $currentAnswer['questions'] = $answersQuestions;
            //reconstruction de la
            $answersList[] = $currentAnswer;
        }
        //var_dump($answersQuestions);
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
        $intersect = array();
        $intersect = array_intersect($headerLineDynamic, $filter);
        $headerLine = array_merge($headerLineFixe, $intersect);
        if (!in_array('id_patient', $filter)) {
            unset($headerLine['id_patient']);
        }
        if (!in_array('id', $filter)) {
            unset($headerLine['id']);
        }
        if (!in_array('name', $filter)) {
            unset($headerLine['name']);
        }
        if (!in_array('type', $filter)) {
            unset($headerLine['type']);
        }
        if (!in_array('last_updated', $filter)) {
            unset($headerLine['last_updated']);
        }
        if (!in_array('last_modified', $filter)) {
            unset($headerLine['last_modified']);
        }
        $result[] = $headerLine;
        foreach ($answersList as $cAnswer) {
            $resultLine = array();
            if (in_array('id_patient', $filter)) {
                $resultLine[] = $cAnswer['id_patient'];
            }
            if (in_array('id', $filter)) {
                $resultLine[] = $cAnswer['id'];
            }
            if (in_array('name', $filter)) {
                $resultLine[] = $cAnswer['name'];
            }
            if (in_array('type', $filter)) {
                $resultLine[] = $cAnswer['type'];
            }
            if (in_array('last_updated', $filter)) {
                $resultLine[] = $cAnswer['last_updated'];
            }
            if (in_array('last_modified', $filter)) {
                $resultLine[] = $cAnswer['last_modified'];
            }
            $cQuestions = $cAnswer['questions'];
            //ajout des valeurs à la ligne, si aucune valeur existante pour cette column, ajoute null

            foreach ($intersect as $columnHeader) {
                $valueExists = false;
                foreach ($cQuestions as $cQuestion) {
                    if ($cQuestion['label'] == $columnHeader) {
                        //$resultLine[] = is_array($cQuestion['answer']) ? implode(', ', $cQuestion['answer']) : $cQuestion['answer'];
                        if (is_array($cQuestion['answer'])) {
                            if (isset($cQuestion['answer']['date'])) {
                                $resultLine[] = date(CommonTools::FRENCH_SHORT_DATE_FORMAT, strtotime($cQuestion['answer']['date']));
                            } else {
                                $resultLine[] = implode(', ', $cQuestion['answer']);
                            }
                        } else {
                            $resultLine[] = $cQuestion['answer'];
                        }
                        $valueExists = true;
                        break;
                    }
                }
                if (!$valueExists) {
                    $resultLine[] = "";
                }
            }
            $result[] = $resultLine;
        }
        return $result;
    }

}
