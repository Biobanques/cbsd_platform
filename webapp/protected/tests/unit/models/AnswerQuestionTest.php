<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnswerQuestionTest
 *
 * @author te
 */
class AnswerQuestionTest extends PHPUnit_Framework_TestCase {

    /**
     * testing method return array type
     */
    public function testTypeFunction() {
        $model = new AnswerQuestion;
        $model->answer = "Hello World !";
        $question = new Question;
        $question->id = "q1";
        $question->label = "Question 1";
        $question->label_fr = $question->label;
        $question->type = "input";
        $question->style = "";
        $question->values = "";
        $question->values_fr = $question->values;
        $question->precomment = "";
        $question->precomment_fr = $question->precomment;
        $model->copy($question);
        $model->setAnswer("Test");
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
        $this->assertInternalType('object', $model);
        $this->assertInternalType('string', $model->getLiteralAnswer());
    }

}
