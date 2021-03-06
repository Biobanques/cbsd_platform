<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QuestionnaireHTMLRenderer
 * render to display elements of questionnaire
 * @author nicolas
 */
class QuestionnaireHTMLRenderer {

    /**
     * render tab associated to each group for a questionnaire
     * if isAnswered is filled, we are in case of answer.
     */
    public function renderTabbedGroup($questionnaire, $lang, $isAnswered) {
        $divTabs = "<ul class=\"nav nav-tabs\" role=\"tablist\">";
        $divPans = "<div class=\"tab-content\">";
        $firstTab = false;
        if ($isAnswered) {
            $groups = $questionnaire->answers_group;
        } else {
            $groups = $questionnaire->questions_group;
        }
        if ($groups != null) {
            foreach ($groups as $group) {
                if ($group->parent_group == null) {
                    //par defaut lang = en
                    $title = $group->title;
                    if ($lang == "fr") {
                        if (!empty($group->title_fr)) {
                            $title = $group->title_fr;
                        }
                    }
                    if ($lang == "both") {
                        $title = "<i>" . $group->title . "</i><bR> " . $group->title_fr;
                    }
                    $extraActive = "";
                    $extraActive2 = "";
                    if ($firstTab == false) {
                        $firstTab = true;
                        $extraActive = "class=\"active\"";
                        $extraActive2 = " active";
                    }
                    $divTabs .= "<li " . $extraActive . "><a href=\"#" . $group->id . "\" role=\"tab\" data-toggle=\"tab\">" . $title . "</a></li>";
                    $divPans .= " <div class=\"tab-pane " . $extraActive2 . "\" id=\"" . $group->id . "\">" . QuestionnaireHTMLRenderer::renderQuestionGroupHTML($questionnaire, $group, $lang, $isAnswered) . "</div>";
                }
            }
        }
        $divPans .= "</div>";
        $divTabs .= "</ul>";
        return "<div class=\"tabbable\">" . $divTabs . $divPans . "</div>";
    }

    /**
     * render a question group or an answer group.
     * @param type $questionnaire or answer
     * @param type $question_group
     * @param type $lang
     * @param type $isAnswered
     * @return string
     */
    public function renderQuestionGroupHTML($questionnaire, $group, $lang, $isAnswered) {
        $result = "";
        //en par defaut
        $title = $group->title;
        if ($lang == "fr") {
            if (!empty($group->title_fr)) {
                $title = $group->title_fr;
            }
        }
        if ($lang == "both") {
            $title = "<i>" . $group->title . "</i> / " . $group->title_fr;
        }
        $result .= "<div class=\"question_group\">" . $title . "</div>";
        if ($isAnswered) {
            $quests = $group->answers;
        } else {
            $quests = $group->questions;
        }
        if (isset($quests)) {
            foreach ($quests as $question) {
                $result .= QuestionnaireHTMLRenderer::renderQuestionHTML($group->id, $question, $lang, $isAnswered);
            }
        }
        //add question groups that have parents for this group
        if ($isAnswered) {
            $groups = $questionnaire->answers_group;
        } else {
            $groups = $questionnaire->questions_group;
        }
        foreach ($groups as $qg) {
            if ($qg->parent_group == $group->id) {
                $result .= QuestionnaireHTMLRenderer::renderQuestionGroupHTML($questionnaire, $qg, $lang, $isAnswered);
            }
        }
        $result .= "<div class=\"end-question-group\"></div>";
        return $result;
    }

    /*
     * render html the current question.
     */

    public function renderQuestionHTML($idquestiongroup, $question, $lang, $isAnswered) {
        $result = "";
        $style = "style=\"\"";
        $color = (isset($_SESSION['test'][$question->label])) ? "style=\"color:red\";" : "";
        if ($question->style != "") {
            $style = "style=\"" . $question->style . "\"";
        }
        if ($question->precomment != "" && $question->style == "") {
            $style = "style=\"clear:both\"";
        }
        $result .= "<div " . $style . ">";
        if ($question->precomment != null) {
            $precomment = $question->precomment;
            if ($lang == "fr") {
                $precomment = $question->precomment_fr;
            }
            if ($lang == "both") {
                $precomment = "<i>" . $question->precomment . "</i><br>" . $question->precomment_fr;
            }
            $result .= "<div class=\"question-precomment\"><h3><u>" . $precomment . "</u></h3></div>";
        }
        //par defaut lang = enif ($lang == "en")
        $label = $question->label;
        if ($lang == "fr") {
            $label = $question->label_fr;
        }
        if ($lang == "both") {
            $label = "<i>" . $question->label . "</i><br>" . $question->label_fr;
        }

        $result .= "<div class=\"question-label\"" . $color . ">" . $label;
        if (isset($question->help)) {
            $result .= HelpDivComponent::getHtml("help-" . $question->id, $question->help);
        }
        $result .= "</div>";
        $result .= "<div class=\"question-input\">";

        //affichage de l input selon son type
        $idInput = "id=\"" . $idquestiongroup . "_" . $question->id . "\" name=\"Questionnaire[" . $idquestiongroup . "_" . $question->id . "]" . ($question->type == "checkbox" ? "[]" : "") . "\"";
        $valueInput = "";
        if (Yii::app()->controller->id != "formulaire") {
            if ($question->id == "examdate") {
                $valueInput = date("d/m/Y");
            }
            if ($question->id == "doctorname") {
                $valueInput = ucfirst(Yii::app()->user->getPrenom()) . " " . strtoupper(Yii::app()->user->getNom());
            }
            if ($question->id == "patientage" && isset($_SESSION["patientBirthDate"])) {
                if (strpos($_SESSION["patientBirthDate"], '/')) {
                    $birthdateFormat = explode('/', $_SESSION["patientBirthDate"]);
                } else {
                    $birthdateFormat = array_swap(explode('-', $_SESSION["patientBirthDate"]), $_SESSION["patientBirthDate"][0], $_SESSION["patientBirthDate"][2]);
                }
                $dateNow = explode('/', date(CommonTools::FRENCH_SHORT_DATE_FORMAT));
                if (($birthdateFormat[1] < $dateNow[1]) || (($birthdateFormat[1] == $dateNow[1]) && ($birthdateFormat[0] <= $dateNow[0]))) {
                    $valueInput = $dateNow[2] - $birthdateFormat[2];
                } else {
                    $valueInput = $dateNow[2] - $birthdateFormat[2] - 1;
                }
            }
        }
        if ($isAnswered) {
            if ($question->type == "date") {
                if ($question->answer['date'] != null) {
                    $valueInput = date("d/m/Y", strtotime($question->answer['date']));
                } else {
                    $valueInput = null;
                }
            } else {
                $valueInput = $question->answer;
            }
        }
        if (isset($question->defaultValue)) {
            $valueInput = $question->defaultValue;
        }
        if ($question->type == "input") {
            $result .= "<input type=\"text\" " . $idInput . " value=\"" . $valueInput . "\"/>";
        }
        if ($question->type == "number" || $question->type == "expression") {
            $result .= "<input type=\"number\" " . $idInput . " value=\"" . $valueInput . "\"/>";
        }
        if ($question->type == "date") {
            if (Yii::app()->controller->id == "answer" || Yii::app()->controller->id == "fiche") {
                if ($valueInput != "") {
                    $result .= "<input type=\"text\" " . $idInput . " value=\"" . $valueInput . "\" onfocus=\"singleDatePicker(this.name)\"/>";
                } else {
                    $result .= "<input type=\"text\" " . $idInput . " value=\"\" placeholder=\"Format jj/mm/aaaa\" onfocus=\"singleDatePicker(this.name)\"/>";
                }
            } else {
                if ($valueInput != "") {
                    $result .= "<input type=\"text\" " . $idInput . " value=\"" . $valueInput . "\" placeholder=\"Format jj/mm/aaaa\" onfocus=\"singleDatePicker(this.name)\"/>";
                } else {
                    $result .= "<input type=\"text\" " . $idInput . " value=\"\" placeholder=\"Format jj/mm/aaaa\" onfocus=\"singleDatePicker(this.name)\"/>";
                }
            }
        }
        if ($question->type == "radio") {

            if ($lang == "fr" && $question->values_fr != "") {
                $values = $question->values_fr;
            } else {
                $values = $question->values;
            }
            $arvalue = split(",", $values);

            foreach ($arvalue as $value) {
                $result .= "<input type=\"radio\" " . $idInput . " value=\"" . $value . "\" " . ($value == $valueInput ? 'checked' : '') . ">&nbsp;" . $value . "</input>&nbsp;";
            }
        }
        if ($question->type == "checkbox") {
            $values = $question->values;
            if ($lang == "fr" && isset($question->values_fr)) {
                $values = $question->values_fr;
            }
            $arvalue = split(",", $values);
            foreach ($arvalue as $value) {
                $checked = false;
                //in case of check box $ValuesInput is stored into an array.
                if ($valueInput != null) {
                    foreach ($valueInput as $vInput) {
                        if ($vInput == $value) {
                            $checked = true;
                        }
                    }
                }
                $result .= "<input type=\"checkbox\" " . $idInput . " value=\"" . $value . "\" " . ($checked ? 'checked' : '') . ">&nbsp;" . $value . "</input><br>";
            }
        }
        if ($question->type == "text") {
            $result .= "<textarea " . $idInput . ">" . $valueInput . "</textarea>";
        }
        if ($question->type == "list") {
            $values = $question->values;
            $arvalue = split(",", $values);
            $result .= "<select " . $idInput . ">";
            $result .= "<option  value=\"\"></option>";
            foreach ($arvalue as $value) {
                $result .= "<option  value=\"" . $value . "\" " . ($valueInput == $value ? 'selected' : '') . ">" . $value . "</option>";
            }
            $result .= "</select>";
        }
        //close question input
        $result .= "</div>";
        //close row input
        $result .= "</div>";
        return $result;
    }

    /*
     * render html the current question.
     */

    public function renderQuestionForSearchHTML($question, $lang, $isAnswered) {
        $condition = false;
        $result = "";

        $result .= "<div class=\"col-lg-12\">";

        //par defaut lang = enif ($lang == "en")
        $label = $question->label;
        if ($lang == "fr") {
            $label = $question->label_fr;
        }
        if ($lang == "both") {
            $label = "<i>" . $question->label . "</i><br>" . $question->label_fr;
        }
        /* $result .= "<div class=\"condition\"><div style=\"clear:both;\"></div>";
          $result .= CHtml::dropDownList("Answer[condition][" . $question->id . "]", 'addCondition', array('$and' => Yii::t('common', 'and'), '$or' => Yii::t('common', 'or')), array('style' => 'width:auto'));
          $result .= "<div style=\"clear:both;\"></div></div>"; */

        $result .= "<label for=\"Answer_dynamics_" . $question->id . "\" style=\"font-style:italic; color:blue;\">" . $label;
        if (isset($question->help)) {
            $result .= HelpDivComponent::getHtml("help-" . $question->id, $question->help);
        }
        $result .= "</label>";

        $result .= "<div class=\"question-input\">";
        // Liste déroulante des opérateurs de comparaison
        if ($question->type == "number" || $question->type == "expression") {
            $result .= CHtml::dropDownList("Answer[compare][" . $question->id . "]", 'addCompare', Answer::model()->getComparaisonNumerique(), array('style' => 'width:auto'));
            $condition = true;
        } elseif ($question->type == "date") {
            $result .= CHtml::dropDownList("Answer[compare][" . $question->id . "]", 'addCompare', Answer::model()->getComparaisonDate(), array('style' => 'width:auto'));
            $condition = true;
        }
        if ($condition) {
            $idInput = "id=\"Answer_dynamics_" . $question->id . "\" name=\"Answer[dynamics][" . $question->id . "]" . ($question->type == "checkbox" ? "[]" : "") . "\" style=\"margin-left:110px;\"";
        } else {
            $idInput = "id=\"Answer_dynamics_" . $question->id . "\" name=\"Answer[dynamics][" . $question->id . "]" . ($question->type == "checkbox" ? "[]" : "") . "\"";
        }
        $valueInput = "";
        if ($isAnswered) {
            $valueInput = $question->answer;
        }
        if ($question->type == "input") {
            $result .= "<input type=\"text\" " . $idInput . " value=\"" . $valueInput . "\"required/>";
        }
        if ($question->type == "number" || $question->type == "expression") {
            $result .= "<input type=\"number\" " . $idInput . " value=\"" . $valueInput . "\"required/>";
        }
        if ($question->type == "date") {
            $result .= "<input type=\"date\" " . $idInput . " value=\"" . $valueInput . "\" placeholder=\"Format jj/mm/aaaa\" onfocus=\"datePicker(this.name)\" required/>";
        }
        if ($question->type == "radio") {

            if ($lang == "fr" && $question->values_fr != "") {
                $values = $question->values_fr;
            } else {
                $values = $question->values;
            }
            $arvalue = split(",", $values);

            foreach ($arvalue as $value) {
                $result .= "<input type=\"radio\" " . $idInput . " value=\"" . $value . "\" " . ($value == $valueInput ? 'checked' : '') . "required>&nbsp;" . $value . "</input>&nbsp;";
            }
        }
        if ($question->type == "checkbox") {
            $values = $question->values;
            if ($lang == "fr" && isset($question->values_fr)) {
                $values = $question->values_fr;
            }
            $arvalue = split(",", $values);
            foreach ($arvalue as $value) {
                $checked = false;
                //in case of check box $ValuesInput is stored into an array.
                if ($valueInput != null) {
                    foreach ($valueInput as $vInput) {
                        if ($vInput == $value) {
                            $checked = true;
                        }
                    }
                }
                $result .= "<input type=\"checkbox\" " . $idInput . " value=\"" . $value . "\" " . ($checked ? 'checked' : '') . ">&nbsp;" . $value . "</input><br>";
            }
        }
        if ($question->type == "text") {
            $result .= "<textarea rows=\"4\" cols=\"250\" " . $idInput . " style=\"width: 645px; height: 70px;\" required>" . $valueInput . "</textarea>";
        }
        if ($question->type == "list") {
            $values = $question->values;
            $arvalue = split(",", $values);
            $result .= "<select " . $idInput . "required>";
            $result .= "<option  value=\"\"></option>";
            foreach ($arvalue as $value) {
                $result .= "<option  value=\"" . $value . "\" " . ($valueInput == $value ? 'selected' : '') . ">" . $value . "</option>";
            }

            $result .= "</select>";
        }
        $result .= "</div>";

        //display cross delete picture
        $imgHtml = CHtml::image('images/cross.png', Yii::t('common', 'deleteQuestion'), array('class' => 'deleteQuestion', 'style' => 'height:20px;width:20px;'));

        $result .= $imgHtml;

        $result .= CHtml::button(Yii::t('common', 'okQuery'), array('class' => 'btn btn-success validateQuery', 'style' => 'margin-left:15px;'));
        //close row input
        $result .= "</div>";
        return $result;
    }

    public function renderQuestionForSearchReplaceHTML($question, $lang, $isAnswered) {
        $condition = false;
        $result = "";

        $result .= "<div class=\"col-lg-12\">";

        //par defaut lang = enif ($lang == "en")
        $label = $question->label;
        if ($lang == "fr") {
            $label = $question->label_fr;
        }
        if ($lang == "both") {
            $label = "<i>" . $question->label . "</i><br>" . $question->label_fr;
        }

        $result .= "<label for=\"result\" style=\"font-style:italic; color:blue;\">" . $label;
        if (isset($question->help)) {
            $result .= HelpDivComponent::getHtml("help-" . $question->id, $question->help);
        }
        $result .= "</label>";

        $result .= "<div class=\"question-input\">";
        $valueInput = "";
        if ($isAnswered) {
            $valueInput = $question->answer;
        }
        if ($question->type == "input") {
            $result .= CHtml::dropDownList('test', 'select', Answer::model()->getAllAnswersByFilter($_SESSION['model'], $question->id));
            $result .= '<br>';
            $result .= "<label for=\"result\" style=\"font-style:italic; color:blue;\">Nouvelle variable</label>";
            $result .= "<input type=\"text\" id=\"result\" name=\"result\" required/>";
            $result .= "<input type='hidden' name=\"hidden_id\" value=\"" . $question->id . "\" />";
        }
        if ($question->type == "number" || $question->type == "expression") {
            $result .= CHtml::dropDownList('test', 'select', Answer::model()->getAllAnswersByFilter($_SESSION['model'], $question->id));
            $result .= '<br>';
            $result .= "<label for=\"result\" style=\"font-style:italic; color:blue;\">Nouvelle variable</label>";
            $result .= "<input type=\"number\" id=\"result\" name=\"result\" required/>";
            $result .= "<input type='hidden' name=\"hidden_id\" value=\"" . $question->id . "\" />";
        }
        if ($question->type == "date") {
            $result .= CHtml::dropDownList('test', 'select', Answer::model()->getAllAnswersByFilter($_SESSION['model'], $question->id));
            $result .= '<br>';
            $result .= "<label for=\"result\" style=\"font-style:italic; color:blue;\">Nouvelle variable</label>";
            $result .= "<input type=\"date\" id=\"result\" name=\"result\" placeholder=\"Format jj/mm/aaaa\" required/>";
            $result .= "<input type='hidden' name=\"hidden_id\" value=\"" . $question->id . "\" />";
        }
        if ($question->type == "radio") {
            $result .= CHtml::dropDownList('test', 'select', Answer::model()->getAllAnswersByFilter($_SESSION['model'], $question->id));
            $result .= "<select id=\"result\" name=\"result\" required>";
            if ($lang == "fr" && $question->values_fr != "") {
                $values = $question->values_fr;
            } else {
                $values = $question->values;
            }
            $arvalue = split(",", $values);

            foreach ($arvalue as $value) {
                $result .= "<option  value=\"" . $value . "\" " . ($valueInput == $value ? 'selected' : '') . ">" . $value . "</option>";
            }
            $result .= "</select>";
            $result .= "<input type='hidden' name=\"hidden_id\" value=\"" . $question->id . "\" />";
        }
        if ($question->type == "checkbox") {
            $result .= CHtml::dropDownList('test', 'select', Answer::model()->getAllAnswersByFilter($_SESSION['model'], $question->id));
            $result .= "<select id=\"result\" name=\"result\" required>";
            $values = $question->values;
            if ($lang == "fr" && isset($question->values_fr)) {
                $values = $question->values_fr;
            }
            $arvalue = split(",", $values);
            $result .= "<option  value=\"\"></option>";
            foreach ($arvalue as $value) {
                $result .= "<option  value=\"" . $value . "\" " . ($valueInput == $value ? 'selected' : '') . ">" . $value . "</option>";
            }
            $result .= "</select>";
            $result .= "<input type='hidden' name=\"hidden_id\" value=\"" . $question->id . "\" />";
        }
        if ($question->type == "text") {
            $result .= "<textarea rows=\"4\" cols=\"250\" " . $idInput . " style=\"width: 645px; height: 70px;\" required>" . $valueInput . "</textarea>";
            $result .= "<input type='hidden' name=\"hidden_id\" value=\"" . $question->id . "\" />";
        }
        if ($question->type == "list") {
            $result .= CHtml::dropDownList('test', 'select', Answer::model()->getAllAnswersByFilter($_SESSION['model'], $question->id));
            $result .= "<select id=\"result\" name=\"result\" required>";
            $values = $question->values;
            $arvalue = split(",", $values);
            $result .= "<option  value=\"\"></option>";
            foreach ($arvalue as $value) {
                $result .= "<option  value=\"" . $value . "\" " . ($valueInput == $value ? 'selected' : '') . ">" . $value . "</option>";
            }

            $result .= "</select>";
            $result .= "<input type='hidden' name=\"hidden_id\" value=\"" . $question->id . "\" />";
        }
        $result .= "</div>";

        //display cross delete picture
        $imgHtml = CHtml::image('images/cross.png', Yii::t('common', 'deleteQuestion'), array('class' => 'deleteQuestion', 'style' => 'height:20px;width:20px;'));

        $result .= $imgHtml;

        //close row input
        $result .= "</div>";
        return $result;
    }

    /**
     * render tab associated to each group for a questionnaire in edit mode
     * if isAnswered is filled, we are in case of answer.
     */
    public function renderTabbedGroupEditMode($questionnaire, $lang) {
        $divTabs = "<ul class=\"nav nav-tabs\" role=\"tablist\">";
        $divPans = "<div class=\"tab-content\">";
        $firstTab = false;
        $groups = $questionnaire->questions_group;
        if ($groups != null) {
            foreach ($groups as $group) {
                if ($group->parent_group == null) {
                    //par defaut lang = en
                    $title = $group->title;
                    if ($lang == "fr") {
                        if (!empty($group->title_fr)) {
                            $title = $group->title_fr;
                        }
                    }
                    if ($lang == "both") {
                        $title = "<i>" . $group->title . "</i><br> " . $group->title_fr;
                    }
                    $extraActive = "";
                    $extraActive2 = "";
                    if (isset($_POST['QuestionForm']['idQuestionGroup'])) {
                        if ($group->id == $_POST['QuestionForm']['idQuestionGroup']) {
                            $extraActive = "class=\"active\"";
                            $extraActive2 = " active";
                        }
                    } elseif ($firstTab == false) {
                        $firstTab = true;
                        $extraActive = "class=\"active\"";
                        $extraActive2 = " active";
                    }
                    $divTabs .= "<li " . $extraActive . "><a href=\"#" . $group->id . "\" role=\"tab\" data-toggle=\"tab\">" . $title . "<input type=\"hidden\" value=\"" . $group->id . "\"></a></li>";
                    $divPans .= " <div class=\"tab-pane " . $extraActive2 . "\" id=\"" . $group->id . "\">" . QuestionnaireHTMLRenderer::renderQuestionGroupHTMLEditMode($questionnaire, $group, $lang) . "</div>";
                }
            }
        }
        $divPans .= "</div>";
        if (Yii::app()->controller->id != "questionBloc") {
            $divTabs .= "<li><a href=\"#\" id=\"tabForm\" role=\"tab\" style='background-color:#5cb85c;'>&boxplus; Ajouter un onglet</a></li>";
        }
        $divTabs .= "</ul>";
        return "<div class=\"tabbable\">" . $divTabs . $divPans . "</div>";
    }

    /**
     *
     * @param type $questionnaire
     * @param type $group
     * @param type $lang
     * @param type $isAnswered
     * @return string
     */
    public function renderQuestionGroupHTMLEditMode($questionnaire, $group, $lang) {
        $result = "";
        //en par defaut
        $title = $group->title;
        if ($lang == "fr") {
            $title = $group->title_fr;
        }
        if ($lang == "both") {
            $title = "<i>" . $group->title . "</i> / " . $group->title_fr;
        }
        if (Yii::app()->controller->id != "questionBloc") {
            $imghtmlUpdate = CHtml::image(Yii::app()->request->baseUrl . '/images/update.png');
            $imghtml = CHtml::image('images/cross.png', Yii::t('common', 'deleteQuestion'), array('class' => 'deleteQuestion'));
            $lienSupprimer = "<div style=\"float:right\">" . CHtml::link($imghtmlUpdate . ' ' . "Modifier le titre de l'onglet", '#', array("class" => "updateTabForm")) . CHtml::link($imghtml . " " . Yii::t('common', 'deleteQuestionGroup'), Yii::app()->createUrl('formulaire/deleteQuestionGroup', array('idFormulaire' => $questionnaire->_id, 'idQuestionGroup' => $group->id)), array('style' => 'margin-left: 30px;')) . "</div>";
            $_SESSION['id'] = $questionnaire->_id;
            $_SESSION['tab'] = $group->id;
            $result .= "<div class=\"question_group\">" . $title . $lienSupprimer . "</div><a class=\"btn\" style='background-color:#5cb85c;color: black;' onclick=\"$('#updateQuestionForm').modal();var period_val = $('.nav > .active > a > input').val();$('#QuestionForm_idQuestionGroup').val(period_val);
    \">&boxplus; Ajouter une rubrique</a><br><br>";
        } else {
            $result .= "<div class=\"question_group\">" . $title . "</div><a class=\"btn\" style='background-color:#5cb85c;color: black;' onclick=\"$('#updateQuestionForm').modal();var period_val = $('.nav > .active > a > input').val();$('#QuestionForm_idQuestionGroup').val(period_val);
    \">&boxplus; Ajouter une rubrique</a><br><br>";
        }
        $quests = $group->questions;

        if (isset($quests)) {
            foreach ($quests as $question) {
                $result .= QuestionnaireHTMLRenderer::renderQuestionHTMLEditMode($questionnaire->_id, $group->id, $question, $lang);
            }
        }
        //add question groups that have parents for this group
        $groups = $questionnaire->questions_group;
        foreach ($groups as $qg) {
            if ($qg->parent_group == $group->id) {
                $result .= QuestionnaireHTMLRenderer::renderQuestionGroupHTMLEditMode($questionnaire, $qg, $lang);
            }
        }
        $result .= "<div class=\"end-question-group\"></div>";
        return $result;
    }

    /*
     * render a question in html
     * render html the current question.
     */

    public function renderQuestionHTMLEditMode($idMongoQuestionnaire, $idquestiongroup, $question, $lang) {
        $result = "";
        $style = "style=\"\"";
        if ($question->style != "") {
            $style = "style=\"" . $question->style . "\"";
        }
        if ($question->precomment != "" && $question->style == "") {
            $style = "style=\"clear:both\"";
        }
        $result .= "<div " . $style . ">";
        if ($question->precomment != null) {
            $precomment = $question->precomment;
            if ($lang == "fr") {
                $precomment = $question->precomment_fr;
            }
            if ($lang == "both") {
                $precomment = "<i>" . $question->precomment . "</i><br>" . $question->precomment_fr;
            }
            $result .= "<div class=\"question-precomment\"><u>" . $precomment . "</u></div>";
        }
        //par defaut lang = enif ($lang == "en")
        $label = $question->label;
        if ($lang == "fr") {
            $label = $question->label_fr;
        }
        if ($lang == "both") {
            $label = "<i>" . $question->label . "</i><br>" . $question->label_fr;
        }
        $label .= "<br><font color=\"blue\"><b><i>" . $question->id . "</i></b></font>";

        $result .= "<div class=\"question-label\" id=\"" . $question->id . "\">" . $label . CHtml::link(CHtml::image(Yii::app()->request->baseUrl . '/images/update.png'), '#', array("class" => "updateForm", "id" => $question->id));
        if (isset($question->help)) {
            $result .= HelpDivComponent::getHtml("help-" . $question->id, $question->help);
        }
        $result .= "</div>";
        $result .= "<div class=\"question-input\">";

        //affichage de l input selon son type
        $idInput = "id=\"" . $idquestiongroup . "_" . $question->id . "\" name=\"Questionnaire[" . $idquestiongroup . "_" . $question->id . "]" . ($question->type == "checkbox" ? "[]" : "") . "\"";
        $valueInput = "";
        if (isset($question->defaultValue)) {
            $valueInput = $question->defaultValue;
        }
        if ($question->type == "input") {
            $result .= "<input type=\"text\" " . $idInput . " value=\"" . $valueInput . "\"/>";
        }
        if ($question->type == "number" || $question->type == "expression") {
            $result .= "<input type=\"number\" " . $idInput . " \"" . $valueInput . "\"/>";
        }
        if ($question->type == "date") {
            $result .= "<input type=\"date\" " . $idInput . " value=\"" . $valueInput . "\" placeholder=\"Format jj/mm/aaaa\"/>";
        }
        if ($question->type == "radio") {

            if ($lang == "fr" && $question->values_fr != "") {
                $values = $question->values_fr;
            } else {
                $values = $question->values;
            }
            $arvalue = split(",", $values);

            foreach ($arvalue as $value) {
                $result .= "<input type=\"radio\" " . $idInput . " value=\"" . $value . "\" " . ($value == $valueInput ? 'checked' : '') . ">&nbsp;" . $value . "</input>&nbsp;";
            }
        }
        if ($question->type == "checkbox") {
            $values = $question->values;
            if ($lang == "fr" && isset($question->values_fr)) {
                $values = $question->values_fr;
            }
            $arvalue = split(",", $values);
            foreach ($arvalue as $value) {
                $result .= "<input type=\"checkbox\" " . $idInput . " value=\"" . $value . "\" " . (in_array($value, explode(",", $valueInput)) ? 'checked' : '') . ">&nbsp;" . $value . "</input><br>";
            }
        }
        if ($question->type == "text") {
            $result .= "<textarea rows=\"4\" cols=\"100\" " . $idInput . "></textarea>";
        }
        if ($question->type == "list") {
            $values = $question->values;
            $arvalue = split(",", $values);
            $result .= "<select " . $idInput . ">";
            $result .= "<option  value=\"\"></option>";
            foreach ($arvalue as $value) {
                $result .= "<option  value=\"" . $value . "\" " . ($valueInput == $value ? 'selected' : '') . ">" . $value . "</option>";
            }
            $result .= "</select>";
        }
        //add link delete
        if (Yii::app()->controller->id == "questionBloc") {
            $imghtml = CHtml::image('images/cross.png', Yii::t('common', 'deleteQuestion'));
            $result .= "<div style=\"float:right;margin-left:5px;\">" . CHtml::link($imghtml, Yii::app()->createUrl('questionBloc/deleteQuestion', array('id' => $_GET['id'], 'idQuestion' => $question->_id))) . "</div>";
        } else {
            $imghtml = CHtml::image('images/cross.png', Yii::t('common', 'deleteQuestion'));
            $result .= "<div style=\"float:right;margin-left:5px;\">" . CHtml::link($imghtml, Yii::app()->createUrl('formulaire/deleteQuestion', array('idFormulaire' => $idMongoQuestionnaire, 'idQuestion' => $question->id))) . "</div>";
        }
        $result .= "</div>";

        //close row input
        $result .= "</div>";
        return $result;
    }

}

?>