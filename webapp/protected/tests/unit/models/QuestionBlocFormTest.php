<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class QuestionBlocFormTest extends PHPUnit_Framework_TestCase {
    
    /**
     * testing method return array type
     */
    public function testTypeFunction() {
        $model = new QuestionBlocForm;
        $criteria = new EMongoCriteria;
        $criteria->id = "demenceform";
        $questionnaire = Questionnaire::model()->find($criteria);
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
        $this->assertFalse($model->validatewithId($questionnaire));
    }
}