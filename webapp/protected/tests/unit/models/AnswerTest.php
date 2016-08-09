<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AnswerTest extends PHPUnit_Framework_TestCase {
    
    /**
     * testing method return array type
     */
    public function testTypeFunction() {
        $model = $this->getAnswer();
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
        $this->assertInternalType('array', $model->attributeExportedLabels());
        $this->assertInternalType('array', $model->getComparaisonNumerique());
        $this->assertInternalType('array', $model->getComparaisonString());
        $this->assertInternalType('string', $model->getUserRecorderName());
        $this->assertInternalType('array', $model->getNomsFiches());
        $gene = new AnswerQuestion;
        $this->assertInternalType('object', $model->addGene(1, $gene));
        $this->assertInternalType('object', $model->addAnalyse(1, $gene));
        $this->assertInternalType('object', $model->addMutation(1, $gene));
        $this->assertInternalType('object', $model->addComment(1, $gene));
        $this->assertInternalType('array', $model->getAllQuestions());
        $this->assertInternalType('string', $model->getTypeQuestionByLabel("Adresse du patient"));
        $this->assertInternalType('array', $model->getAllDetailledQuestions());
        $this->assertInternalType('object', $model->findAllDetailledQuestionById("doctorname"));
        $this->assertInternalType('array', $model->getIdPatientFiches());
        $this->assertInternalType('array', $model->getNamesUsers());
        $this->assertInternalType('array', $model->getAllTypes());
    }
    
    public function getAnswer() {
        $model = new Answer;
        $model->creator = "test";
        $model->type = "clinique";
        $model->id = "testform";
        $model->id_patient = "1000968";
        $model->login = new MongoId();
        $model->name = "Test";
        $model->description = "Ceci est un formulaire de test.";
        $model->answers_group[] = "";
        $model->last_updated = "";
        $model->last_modified = "";
        
        return $model;
    }
}