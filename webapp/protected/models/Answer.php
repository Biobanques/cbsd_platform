<?php

/**
 * Object answer to store a questionnaire definition + answers
 * Copy of object questionnaire to prevent problems of update with questionnaire and forwar compatibility
 * @property integer $idA
 * @author nmalservet
 *
 */
class Answer extends LoggableActiveRecord {

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
    public $idDonor;

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
                'id,name,answers_group,login,type,id_patient,dynamics,compare,condition,last_updated,last_updated_to,user,idDonor',
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

    public function attributeExportedSamples() {
        return array(
            'Echantillons' => 'PrelevementTissusTranche::Origin_Samples_Tissue',
            'Quantite' => 'PrelevementTissusTranche::quantity_available',
            'Cong' => 'PrelevementTissusTranche::storage_conditions'
        );
    }

    public function search($caseSensitive = false) {
        $criteria = new EMongoCriteria;
        $query = Query::model()->find();
        if (isset($_SESSION['tranche'])) {
            $neuropath = null;
            $idCbsd = array();
            foreach ($_SESSION['tranche'] as $tranche) {
                $neuropath = Neuropath::model()->findByAttributes(array('id_donor' => (int) $tranche->id_donor));
                if (!in_array($neuropath->id_cbsd, $idCbsd)) {
                    array_push($idCbsd, $neuropath->id_cbsd);
                }
            }
            $regex = '/^';
            foreach ($idCbsd as $id) {
                $regex .= $id . '$|^';
            }
            $regex .= '$/i';
            $criteria->addCond('id_patient', '==', new MongoRegex($regex));
        } elseif (isset($_POST['patientAll'])) {
            $ficheId = array();
            $allFiches = Answer::model()->findAll(Yii::app()->session['criteria']);
            if ($allFiches != null) {
                foreach ($allFiches as $fiche) {
                    array_push($ficheId, $fiche->id_patient);
                }
                $regex = '/^';
                foreach ($ficheId as $idFiche) {
                    $regex .= $idFiche . '$|^';
                }
                $regex .= '$/i';
                $criteria->addCond('id_patient', '==', new MongoRegex($regex));
                $_SESSION['test'] = $regex;
            } else {
                $criteria->addCond('id_patient', '==', "999999999999999");
                $_SESSION['test'] = "999999999999999";
            }
        } else {
            if (isset($this->type) && !empty($this->type)) {
                $criteria->addCond('type', '==', new MongoRegex(CommonTools::regexString($this->type)));
            }

            if (isset($this->user) && !empty($this->user)) {
                $regex = CommonTools::regexString($this->user);
                $criteriaUser = new EMongoCriteria;
                $criteriaUser->nom = new MongoRegex($regex);
                $criteriaUser->select(array('_id'));
                $users = User::model()->findAll($criteriaUser);
                $listUsers = array();
                if ($users != null) {
                    foreach ($users as $user) {
                        $listUsers[] = $user->_id;
                    }
                }
                $criteria->addCond('login', 'in', $listUsers);
            }

            if (isset($query->id_patient) && !empty($query->id_patient)) {
                $criteria->addCond('id_patient', '==', new MongoRegex(CommonTools::regexString($query->id_patient)));
            }

            if (isset($this->name) && !empty($this->name)) {
                $criteria->addCond('name', '==', new MongoRegex(CommonTools::regexString($this->name)));
            }

            if (isset($this->last_updated) && !empty($this->last_updated)) {
                $answerFormat = CommonTools::formatDatePicker($this->last_updated . " - " . $this->last_updated_to);
                $date_from = str_replace('/', '-', $answerFormat['date_from']);
                $date_to = str_replace('/', '-', $answerFormat['date_to']);
                $criteria->last_updated->date = array('$gte' => date('Y-m-d', strtotime($date_from)) . " 00:00:00.000000", '$lte' => date('Y-m-d', strtotime($date_to)) . " 23:59:59.000000");
                $query->last_updated = $answerFormat;
            } elseif (isset($query->last_updated) && !empty($query->last_updated)) {
                $answerFormat = CommonTools::formatDatePicker($query->last_updated);
                $date_from = str_replace('/', '-', $answerFormat['date_from']);
                $date_to = str_replace('/', '-', $answerFormat['date_to']);
                $criteria->last_updated->date = array('$gte' => date('Y-m-d', strtotime($date_from)) . " 00:00:00.000000", '$lte' => date('Y-m-d', strtotime($date_to)) . " 23:59:59.000000");
            }

            if (isset($this->dynamics) && !empty($this->dynamics)) {
                if (isset($query->dynamics) && $query->dynamics != null) {
                    if (isset($_SESSION['id_patientAll'])) {
                        foreach ($query->dynamics as $dynamicKey => $dynamicValue) {
                            $this->dynamics[$dynamicKey] = $dynamicValue['answerValue'];
                            $this->compare[$dynamicKey] = $dynamicValue['compare'];
                        }
                    }
                }
                $index = 0;
                $nbCriteria = array();
                foreach ($this->dynamics as $questionId => $answerValue) {
                    if ($index != 0) {
                        $nbCriteria = '$criteria' . $index;
                        $nbCriteria = new EMongoCriteria;
                    }
                    if (isset($this->compare[$questionId])) {
                        if ($index == 0) {
                            if ($this->compare[$questionId] == "between") {
                                $answerDate = CommonTools::formatDatePicker($answerValue);
                                $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer.date' => array('$gte' => $answerDate['date_from'] . " 00:00:00.000000", '$lte' => $answerDate['date_to'] . " 23:59:59.000000")));
                            } elseif ($this->compare[$questionId] == "equals") {
                                $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => (int) $answerValue));
                            } else {
                                $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => array(EMongoCriteria::$operators[$this->compare[$questionId]] => (int) $answerValue)));
                            }
                        } else {
                            if ($this->compare[$questionId] == "between") {
                                $answerDate = CommonTools::formatDatePicker($answerValue);
                                $nbCriteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer.date' => array('$gte' => $answerDate['date_from'] . " 00:00:00.000000", '$lte' => $answerDate['date_to'] . " 23:59:59.000000")));
                            } elseif ($this->compare[$questionId] == "equals") {
                                $nbCriteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => (int) $answerValue));
                            } else {
                                $nbCriteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => array(EMongoCriteria::$operators[$this->compare[$questionId]] => (int) $answerValue)));
                            }
                        }
                    } else {
                        $values = (!is_array($answerValue)) ? split(',', $answerValue) : $answerValue;
                        if ($index == 0) {
                            $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => new MongoRegex(CommonTools::regexString($values))));
                        } else {
                            $nbCriteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => new MongoRegex(CommonTools::regexString($values))));
                        }
                    }
                    if ($index != 0) {
                        if (isset($_SESSION['id_patientAll'])) {
                            $criteria->mergeWith($nbCriteria, '$or');
                        } else {
                            $criteria->mergeWith($nbCriteria, '$and');
                        }
                    } else {
                        if (isset($_SESSION['id_patientAll'])) {
                            $criteria->mergeWith($nbCriteria, '$and');
                        }
                    }
                    $index++;
                    $dynamics = array();
                    $dynamics['compare'] = !empty($this->compare[$questionId]) ? $this->compare[$questionId] : null;
                    $dynamics['answerValue'] = $answerValue;
                    $query->dynamics[$questionId] = $dynamics;

                    if (Yii::app()->controller->id != "fiche") {
                        $query->save();
                    }
                }
                if (isset($_SESSION['id_patientAll'])) {
                    $criteriaTest = new EMongoCriteria;
                    $criteriaTest->id_patient = new MongoRegex($_SESSION['id_patientAll']);
                    $criteria->mergeWith($criteriaTest, '$and');
                }
            } elseif (isset($query->type)) {
                $criteria->addCond('name', '==', new MongoRegex(CommonTools::regexString($query->type)));
            }
            if (Yii::app()->controller->id != "fiche") {
                $query->save();
            }
        }
        if (isset($_GET['ajax']) && isset($_SESSION['test'])) {
            $criteria = new EMongoCriteria;
            $criteria->addCond('id_patient', '==', new MongoRegex($_SESSION['test']));
        }
        $criteria->sort('idDonor', EMongoCriteria::SORT_ASC);
        $criteria->sort('id_patient', EMongoCriteria::SORT_ASC);
        $criteria->sort('type', EMongoCriteria::SORT_ASC);
        $criteria->sort('last_updated', EMongoCriteria::SORT_DESC);
        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    public function getComparaisonNumerique() {
        $res = array();
        $res ['equals'] = "=";
        $res ['noteq'] = "<>";
        $res ['less'] = "<";
        $res ['greater'] = ">";
        $res ['lesseq'] = "<=";
        $res ['greatereq'] = ">=";
        return $res;
    }

    public function getComparaisonDate() {
        $res = array();
        $res ['between'] = Yii::t('common', 'between');
        return $res;
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

    public function resultExport($models, $filter) {
        $ansQuestion = array();
        $samQuestion = array();
        $sampleQuestions = array();
        $answersList = array();
        $neuropath = null;
        $tranche = null;
        foreach ($models as $answer) {
            $counter = 0;
            $nbColumn = 0;
            $sampledQuestions = array();
            $currentAnswerBis = array();
            $neuropath = Neuropath::model()->findByAttributes(array("id_cbsd" => (int) $answer->id_patient));
            $tranche = Tranche::model()->findAllByAttributes(array("id_donor" => (int) $neuropath->id_donor));
            $answersQuestions = array();
            $count = 0;
            $sampleQuestions = array();
            $currentAnswer = array();
            $birthName = "Nom naissance";
            $useName = "Nom usuel";
            $firstName = "Prénoms";
            $birthDate = "Date naissance";
            $sexe = "Sexe";
            $currentAnswer['Nom naissance'] = $neuropath->$birthName;
            $currentAnswer['Nom usuel'] = $useName;
            $currentAnswer['Prénoms'] = $firstName;
            $currentAnswer['Date naissance'] = $birthDate;
            $currentAnswer['Sexe'] = $sexe;
            foreach ($neuropath as $k => $v) {
                if ($k != "_id" && $k != "id_cbsd") {
                    $columnFileMaker = ColumnFileMaker::model()->findByAttributes(array('newColumn' => explode('_', $k)[0]));
                    if ($columnFileMaker != null) {
                        $ansQuestion['label'] = $columnFileMaker->newColumn;
                        $ansQuestion['answer'] = $v;
                        print_r($ansQuestion);
                    }
                    if ($k == "id_donor") {
                        $answersQuestions[0] = $ansQuestion;
                    } else {
                        $answersQuestions[] = $ansQuestion;
                    }
                }
            }
            foreach ($tranche as $trancheK => $trancheV) {
                
            }
        }

        /* $currentAnswer['questions'] = $answersQuestions;
          $answersList[] = $currentAnswer;
          if ($sampledQuestions != null) {
          foreach ($sampledQuestions as $k => $v) {
          $answersQuestions = array();
          $answersQuestions[] = $v['Echantillons'];
          $answersQuestions[] = $v['Qte'];
          $answersQuestions[] = $v['Cong'];
          $currentAnswerBis['questions'] = $answersQuestions;
          $answersList[] = $currentAnswerBis;
          }
          }
          }
          $headerLineDynamic = array();
          foreach ($answersList as $cAnswer) {
          foreach ($cAnswer['questions'] as $qAnswer) {
          if (!in_array($qAnswer['label'], $headerLineDynamic)) {
          $headerLineDynamic[] = $qAnswer['label'];
          }
          }
          }
          $result[] = $headerLineDynamic;
          foreach ($answersList as $cAnswer) {
          $resultLine = array();
          $cQuestions = $cAnswer['questions'];
          //ajout des valeurs à la ligne, si aucune valeur existante pour cette column, ajoute null

          foreach ($headerLineDynamic as $columnHeader) {
          $valueExists = false;
          foreach ($cQuestions as $cQuestion) {
          if ($cQuestion['label'] == $columnHeader) {
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
          } */
        //return $result;
    }

}
