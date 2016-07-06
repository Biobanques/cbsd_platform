<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QuestionBlocTest
 *
 * @author te
 */
class QuestionFormTest extends PHPUnit_Framework_TestCase {
    
    /**
     * testing method return array type
     */
    public function testTypeFunction() {
        $model = new QuestionForm;
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
        $this->assertInternalType('array', $model->getArrayTypes());
        $this->assertInternalType('array', $model->getArrayStyles());
    }
}
