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
     * @var MongoDate
     */
    public $last_modified;
    public $description;
    public $answers_group;

    /**
     * last date of save action
     */
    public $last_updated;

    /**
     * Working variable to add dynamic search filters
     * @var array
     */
    public $dynamics;
    public $compare;

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
                'id,name,answers_group,login,type,id_patient,dynamics,compare,last_updated,user',
                'safe',
                'on' => 'search'
            )
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'Id',
            'id_patient' => 'N° anonymat',
            'name' => 'Nom du formulaire',
            'type' => 'Type de formulaire',
            'last_updated' => 'Date de saisie',
            'last_modified' => 'Date de mise à jour du questionnaire',
            'user' => 'Nom de l\'utilisateur',
            'examDate' => 'Date d\'examen',
        );
    }

    public function attributeExportedLabels() {
        return array(
            'id_patient' => 'N° anonymat',
            'id' => 'N° fiche',
            'name' => 'Nom de la fiche',
            'type' => 'Type de fiche',
            'last_updated' => 'Date de saisie',
            'last_modified' => 'Date de mise à jour du questionnaire',
        );
    }

    public function search($caseSensitive = false) {
        $criteria = new EMongoCriteria;

        if (isset($this->type) && !empty($this->type)) {
            $regex = '/';
            foreach ($this->type as $value) {
                $regex .= $value;
                if ($value != end($this->type)) {
                    $regex.= '|';
                }
            }
            $regex .= '/i';
            $criteria->addCond('type', '==', new MongoRegex($regex));
        }

        if (isset($this->user) && !empty($this->user)) {
            $regex = '/';
            foreach ($this->user as $value) {
                $regex .= $value;
                if ($value != end($this->user)) {
                    $regex.= '|';
                }
            }
            $regex .= '/i';
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

        if (isset($this->id_patient) && !empty($this->id_patient)) {
            $regex = '/';
            foreach ($this->id_patient as $patient) {
                $regex.= $patient;
                if ($patient != end($this->id_patient)) {
                    $regex.= '|';
                }
            }
            $regex .= '/i';
            $criteria->addCond('id_patient', '==', new MongoRegex($regex));
        }

        if (isset($this->name) && !empty($this->name)) {
            $regex = '/';
            foreach ($this->name as $n) {
                $regex.= $n;
                if ($n != end($this->name)) {
                    $regex.= '|';
                }
            }
            $regex .= '/i';
            $criteria->addCond('name', '==', new MongoRegex($regex));
        }
        if (isset($this->last_updated) && !empty($this->last_updated)) {
            $date = str_replace('/', '-', $this->last_updated);
            $criteria->last_updated = array('$gte' => new MongoDate(strtotime($date)), '$lte' => new MongoDate(strtotime($date . " 23:59:59.999Z")));
        }
        if (isset($this->dynamics) && !empty($this->dynamics)) {
            foreach ($this->dynamics as $questionId => $answerValue) {
                if ($answerValue != null && !empty($answerValue)) {
                    if ($questionId == "examdate") {
                        $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => $answerValue));
                    } else {
                        switch ($this->compare[$questionId]) {
                            case "egale":
                                $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => (int) $answerValue));
                                break;
                            case "notEq":
                                $criteria->answers_group->answers->id = $questionId;
                                $criteria->answers_group->answers->answer('!=', (int) $answerValue);
                                //$criteria->addCond('answers_group.answers', 'noteq', array('id' => $questionId, 'answer' => (int)$answerValue));
                                break;
                            case "less":
                                $criteria->answers_group->answers->id = $questionId;
                                $criteria->answers_group->answers->answer('<', (int) $answerValue);
                                break;
                            case "greater":
                                $criteria->answers_group->answers->id = $questionId;
                                $criteria->answers_group->answers->answer('>', (int) $answerValue);
                                break;
                            case "lessEq":
                                $criteria->answers_group->answers->id = $questionId;
                                $criteria->answers_group->answers->answer('<=', (int) $answerValue);
                                break;
                            case "greaterEq":
                                $criteria->answers_group->answers->id = $questionId;
                                $criteria->answers_group->answers->answer('>=', (int) $answerValue);
                                break;
                            case "contient_uniquement":
                                $values = split(',', $answerValue);
                                $regex = '/';
                                foreach ($values as $value) {
                                    $regex.= '^' . $value . '$';
                                    if ($value != end($values)) {
                                        $regex.= '|';
                                    }
                                }
                                $regex .= '/i';
                                $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => new MongoRegex($regex)));
                                break;
                            case "partiellement":
                                $values = split(',', $answerValue);
                                $regex = '/';
                                foreach ($values as $value) {
                                    $regex.= $value;
                                    if ($value != end($values)) {
                                        $regex.= '|';
                                    }
                                }
                                $regex .= '/i';
                                $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => new MongoRegex($regex)));
                                break;
                            default:
                                $criteria->addCond('answers_group.answers', 'elemmatch', array('id' => $questionId, 'answer' => $answerValue));
                        }
                    }
                }
            }
        }

        Yii::app()->session['criteria'] = $criteria;
        return new EMongoDocumentDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'name ASC',
            )
        ));
    }

    public function getComparaisonNumerique() {
        $res = array();
        $res ['egale'] = "égale à";
        $res ['notEq'] = "différent de";
        $res ['less'] = "inférieure à";
        $res ['greater'] = "supérieure à";
        $res ['lessEq'] = "inférieure ou égale à";
        $res ['greaterEq'] = "supérieure ou égale à";
        return $res;
    }

    public function getComparaisonString() {
        $res = array();
        $res ['contient_uniquement'] = "contient uniquement";
        $res ['partiellement'] = "contient partiellement";
        return $res;
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
            return date("d/m/Y H:m", $this->last_modified->sec);
        } else {
            return null;
        }
    }

    public function getLastSqlModified() {
        if ($this->last_modified != null) {
            return date("Y-m-d H:m", $this->last_modified->sec);
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
            return date("d/m/Y H:m", $this->last_updated->sec);
        } else {
            return null;
        }
    }

    public function getLastSqlUpdated() {
        if ($this->last_updated != null) {
            return date("Y-m-d H:m", $this->last_updated->sec);
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
            $result = "$user->prenom $user->nom";
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
    
    public function getAllQuestionsByFilterName($model, $name) {
        $result = array();
        $models = $this->getAllQuestionsByFilter($model);
        foreach ($models as $answer=>$value) {
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
            $result["(" . $answer->answer->id . ")" . $answer->answer->label_fr] = "(" . $answer->fiche . ")" . "(" . $answer->answer->id . ") " . $answer->answer->label_fr;
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
        foreach($fiches as $fiche){
            foreach ($fiche->answers_group as $group) {
                foreach($group->answers as $answer){
                    if ($answer->label == $label) {
                        $type = $answer->type;
                    }
                }
            }
        }
        return $type;
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

    public function findAllDetailledQuestionById($id) {
        $result = null;
        foreach ($this->getAllDetailledQuestions() as $question) {
            if ($question->answer->id == $id)
                $result = $question->answer;
        }
        return $result;
    }

    public function getAnswerByQuestionId($id) {
        $result = null;
        foreach ($this->answers_group as $group) {
            foreach ($group->answers as $answer) {
                if ($answer->id == $id) {
                    $result = $answer->answer;
                }
            }
        }
        return $result;
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
        $typeQuestion = array();
        $result = array();
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
                    // $label = "[" . $answer->name . "][" . $group->title_fr . "][" . $answerQuestion->label . "]";
                    $label = "(" . $answerQuestion->id . ")" . $answerQuestion->label;
                    $typeQuestion[$label] = $answerQuestion->type;
                    $value = $answerQuestion->getLiteralAnswer();
                    $ansQuestion[] = array();
                    $ansQuestion['label'] = $label;
                    $ansQuestion['answer'] = $value;
                    $answersQuestions[] = $ansQuestion;
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
        $intersect = array();
        $intersect = array_intersect($headerLineDynamic, $filter);
        $headerLine = array_merge($headerLineFixe, $intersect);
        $result[] = $headerLine;
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

            foreach ($intersect as $columnHeader) {
                $valueExists = false;
                foreach ($cQuestions as $cQuestion) {
                    if ($cQuestion['label'] == $columnHeader) {

                        $resultLine[] = is_array($cQuestion['answer']) ? implode(', ', $cQuestion['answer']) : $cQuestion['answer'];
                        $valueExists = true;
                        break;
                    }
                }
                if (!$valueExists) {
                    $type = $typeQuestion[$columnHeader];
                    if ($type != "number" && $type != "expression")
                        $resultLine[] = 'null';
                    else
                        $resultLine[] = "";
                }
            }
            $result[] = $resultLine;
        }
        return $result;
    }

}



