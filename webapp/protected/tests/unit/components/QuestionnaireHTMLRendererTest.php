<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class QuestionnaireHTMLRendererTest extends PHPUnit_Framework_TestCase
{
    /**
     * testing method to check if sendMail is correct.
     */
    public function testRenderTabbedGroup() {
        $questionnaire = new Questionnaire;
        $questionGroup = new QuestionGroup;
        $question = new Question;
        $questionnaire->creator = "admin";
        $questionnaire->type = "clinique";
        $questionnaire->id = "testform";
        $questionnaire->name = "Test";
        $questionnaire->description = "Ceci est un test.";
        
        $questionGroup->id = "testgroup";
        $questionGroup->title = "onglet 1";
        $questionGroup->title_fr = $questionGroup->title;
        
        $question->id = "testquestion";
        $question->label = "question 1";
        $question->label_fr = $question->label;
        $question->type = "radio";
        $question->style = "float:right";
        $question->values = "";
        $question->values_fr = "Oui, Non";
        $question->help = "Ceci est un message d'aide";
        $question->precomment = "Titre";
        $question->precomment_fr = "Titre";
        
        $questionGroup->questions[] = $question;
        $questionnaire->questions_group[] = $questionGroup;
        
        $this->assertInternalType('object', $questionnaire);
        $this->assertInternalType('object', $questionGroup);
        $this->assertInternalType('object', $question);
        
        // TODO
        
        
    }

}