<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Question
 *
 * @author te
 */
class QuestionTest extends PHPUnit_Framework_TestCase {
    
    /**
     * testing method return array type
     */
    public function testTypeFunction() {
        $model = new Question;
        $questionForm = new QuestionForm;
        $questionForm->id = "q1";
        $questionForm->label = "Question 1";
        $questionForm->type = "input";
        $questionForm->style = "";
        $questionForm->values = "";
        $questionForm->precomment = "";
        $questionForm->precomment_fr = $questionForm->precomment;
        $questionForm->help = "";
        $model->setAttributesByQuestionForm($questionForm);
        $this->assertInternalType('object', $model->model());
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
        $this->assertInternalType('object', $model);
    }
}