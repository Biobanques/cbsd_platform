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
class AnswerHTMLRenderer {

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
                        if (!empty($group->title_fr))
                            $title = $group->title_fr;
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
                    $divTabs.= "<li " . $extraActive . "><a href=\"#" . $group->id . "\" role=\"tab\" data-toggle=\"tab\">" . $title . "</a></li>";
                    $divPans.= " <div class=\"tab-pane " . $extraActive2 . "\" id=\"" . $group->id . "\">" . QuestionnaireHTMLRenderer::renderQuestionGroupHTML($questionnaire, $group, $lang, $isAnswered) . "</div>";
                }
            }
        }
        $divPans.="</div>";
        $divTabs.="</ul>";
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
    public function renderAnswerGroupHTML($answer, $group, $lang) {
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
        $result.="<div class=\"question_group\">" . $title . "</div>";
        $quests = $group->answers;
        if (isset($quests)) {
            foreach ($quests as $ans) {
                $result.=AnswerHTMLRenderer::renderAnswerHTML($group->id, $ans, $lang);
            }
        }
        //add question groups that have parents for this group
        $groups = $answer->answers_group;

        foreach ($groups as $qg) {
            if ($qg->parent_group == $group->id) {
                $result.=AnswerHTMLRenderer::renderAnswerGroupHTML($answer, $qg, $lang);
            }
        }
        $result .= "<div class=\"end-question-group\"></div>";
        return $result;
    }

    /*
     * render html the current question.
     */

    public function renderAnswerHTML($idanswergroup, $answer, $lang) {
        $result = "";
        $style = "style=\"\"";
        if ($answer->style != "") {
            $style = "style=\"" . $answer->style . "\"";
        }
        $result.="<div " . $style . ">";
        if ($answer->precomment != null) {
            $precomment = $answer->precomment;
            if ($lang == "fr") {
                $precomment = $answer->precomment_fr;
            }
            if ($lang == "both") {
                $precomment = "<i>" . $answer->precomment . "</i><br>" . $answer->precomment_fr;
            }
            $result.="<div class=\"question-precomment\">" . $precomment . "</div>";
        }
        //par defaut lang = enif ($lang == "en")
        $label = $answer->label;
        if ($lang == "fr") {
            $label = $answer->label_fr;
        }
        if ($lang == "both") {
            $label = "<i>" . $answer->label . "</i><br>" . $answer->label_fr;
        }

        $result.="<div class=\"question-label\" >" . $label;
        if (isset($answer->help)) {
            $result.=HelpDivComponent::getHtml("help-" . $answer->id, $answer->help);
        }
        $result.="</div>";
        $result.="<div class=\"question-input\">";

        //affichage de l input selon son type
        $idInput = "id=\"" . $idanswergroup . "_" . $answer->id . "\" name=\"Questionnaire[" . $idanswergroup . "_" . $answer->id . "]" . ($answer->type == "checkbox" ? "[]" : "") . "\"";
        if ($answer->type == "input") {
            $result.="<input type=\"text\" " . $idInput . " value=\"" . $answer->answer . "\"/>";
        }
        if ($answer->type == "date") {
            $result.="<input type=\"date\" " . $idInput . " value=\"" . $answer->answer . "\" placeholder=\"Format jj/mm/aaaa\"/>";
        }
        if ($answer->type == "radio") {

            if ($lang == "fr" && $answer->values_fr != "") {
                $values = $answer->values_fr;
            } else {
                $values = $answer->values;
            }
            $arvalue = split(",", $values);

            foreach ($arvalue as $value) {
                $result.="<input type=\"radio\" " . $idInput . " value=\"" . $value . "\" " . ($value == $answer->answer ? 'checked' : '') . ">&nbsp;" . $value . "</input>&nbsp;";
            }
        }
        if ($answer->type == "checkbox") {
            $values = $answer->values;
            if ($lang == "fr" && isset($answer->values_fr)) {
                $values = $answer->values_fr;
            }
            $arvalue = split(",", $values);
            foreach ($arvalue as $value) {
                $checked = false;
                //in case of check box $ValuesInput is stored into an array.
                if ($answer->answer != null) {
                    foreach ($answer->answer as $vInput) {
                        if ($vInput == $value) {
                            $checked = true;
                        }
                    }
                }
                $result.="<input type=\"checkbox\" " . $idInput . " value=\"" . $value . "\" " . ($checked ? 'checked' : '') . ">&nbsp;" . $value . "</input><br>";
            }
        }
        if ($answer->type == "text") {
            $result.="<textarea rows=\"4\" cols=\"100\" " . $idInput . " style=\"width: 220px; height: 70px;\" >" . $answer->answer . "</textarea>";
        }
        if ($answer->type == "image") {
            $result.="<input " . $idInput . " type=\"file\" />";

            if ($answer->answer != null) {
                $result.="<div>here the image</div>";
            }
        }
        if ($answer->type == "list") {
            $values = $answer->values;
            $arvalue = split(",", $values);
            $result.="<select " . $idInput . ">";
            $result.="<option  value=\"\"></option>";
            foreach ($arvalue as $value) {
                $result.="<option  value=\"" . $value . "\" " . ($answer->answer == $value ? 'selected' : '') . ">" . $value . "</option>";
            }
            $result.="</select>";
        }
        //close question input
        $result.="</div>";
        //close row input
        $result.="</div>";
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
                        if (!empty($group->title_fr))
                            $title = $group->title_fr;
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
                    $divTabs.= "<li " . $extraActive . "><a href=\"#" . $group->id . "\" role=\"tab\" data-toggle=\"tab\">" . $title . "</a></li>";
                    $divPans.= " <div class=\"tab-pane " . $extraActive2 . "\" id=\"" . $group->id . "\">" . QuestionnaireHTMLRenderer::renderQuestionGroupHTMLEditMode($questionnaire, $group, $lang) . "</div>";
                }
            }
        }
        $divPans.="</div>";
        $divTabs.="</ul>";
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
            $imghtml = CHtml::image('images/cross.png');
            $lienSupprimer = "<div style=\"float:right;margin-left:5px;\">" . CHtml::link($imghtml . " Supprimer l'onglet de questions", Yii::app()->createUrl('formulaire/deleteQuestionGroup', array('idFormulaire' => $questionnaire->_id, 'idQuestionGroup' => $group->id))) . "</div>";

            $result.="<div class=\"question_group\">" . $title . $lienSupprimer . "</div>";
        } else {
            $result.="<div class=\"question_group\">" . $title . "</div>";
        }
        $quests = $group->questions;

        if (isset($quests)) {
            foreach ($quests as $question) {
                $result.=QuestionnaireHTMLRenderer::renderQuestionHTMLEditMode($questionnaire->_id, $group->id, $question, $lang);
            }
        }
        //add question groups that have parents for this group
        $groups = $questionnaire->questions_group;
        foreach ($groups as $qg) {
            if ($qg->parent_group == $group->id) {
                $result.=QuestionnaireHTMLRenderer::renderQuestionGroupHTMLEditMode($questionnaire, $qg, $lang);
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
        $result.="<div " . $style . ">";
        if ($question->precomment != null) {
            $precomment = $question->precomment;
            if ($lang == "fr") {
                $precomment = $question->precomment_fr;
            }
            if ($lang == "both") {
                $precomment = "<i>" . $question->precomment . "</i><br>" . $question->precomment_fr;
            }
            $result.="<div class=\"question-precomment\">" . $precomment . "</div>";
        }
        //par defaut lang = enif ($lang == "en")
        $label = $question->label;
        if ($lang == "fr") {
            $label = $question->label_fr;
        }
        if ($lang == "both") {
            $label = "<i>" . $question->label . "</i><br>" . $question->label_fr;
        }
        $label.="<br><font color=\"blue\"><b><i>" . $question->id . "</i></b></font>";

        $result.="<div class=\"question-label\" >" . $label;
        if (isset($question->help)) {
            $result.=HelpDivComponent::getHtml("help-" . $question->id, $question->help);
        }
        $result.="</div>";
        $result.="<div class=\"question-input\">";

        //affichage de l input selon son type
        $idInput = "id=\"" . $idquestiongroup . "_" . $question->id . "\" name=\"Questionnaire[" . $idquestiongroup . "_" . $question->id . "]" . ($question->type == "checkbox" ? "[]" : "") . "\"";
        $valueInput = "";
        if ($question->type == "input") {
            $result.="<input type=\"text\" " . $idInput . " value=\"\"/>";
        }
        if ($question->type == "date") {
            $result.="<input type=\"date\" " . $idInput . " value=\"" . $valueInput . "\" placeholder=\"Format jj/mm/aaaa\"/>";
        }
        if ($question->type == "radio") {

            if ($lang == "fr" && $question->values_fr != "") {
                $values = $question->values_fr;
            } else {
                $values = $question->values;
            }
            $arvalue = split(",", $values);

            foreach ($arvalue as $value) {
                $result.="<input type=\"radio\" " . $idInput . " value=\"" . $value . "\" " . ($value == $valueInput ? 'checked' : '') . ">&nbsp;" . $value . "</input>&nbsp;";
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

                $result.="<input type=\"checkbox\" " . $idInput . " value=\"" . $value . "\" " . ($checked ? 'checked' : '') . ">&nbsp;" . $value . "</input><br>";
            }
        }
        if ($question->type == "text") {
            $result.="<textarea rows=\"4\" cols=\"100\" " . $idInput . " style=\"width: 220px; height: 70px;\" ></textarea>";
        }
        if ($question->type == "image") {
            $result.="<div style=\"width:128px;height:128px;\"> </div>";
        }
        if ($question->type == "list") {
            $values = $question->values;
            $arvalue = split(",", $values);
            $result.="<select " . $idInput . ">";
            $result.="<option  value=\"\"></option>";
            foreach ($arvalue as $value) {
                $result.="<option  value=\"" . $value . "\" " . ($valueInput == $value ? 'selected' : '') . ">" . $value . "</option>";
            }
            $result.="</select>";
        }
        //close question input
        //add link delete
        $imghtml = CHtml::image('images/cross.png');
        $result.="<div style=\"float:right;margin-left:5px;\">" . CHtml::link($imghtml, Yii::app()->createUrl('formulaire/deleteQuestion', array('idFormulaire' => $idMongoQuestionnaire, 'idQuestion' => $question->id))) . "</div>";
        $result.="</div>";

        //close row input
        $result.="</div>";
        return $result;
    }

}
