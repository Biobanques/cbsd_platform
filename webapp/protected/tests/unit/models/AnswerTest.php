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
        $model = new Answer;
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
        $this->assertInternalType('array', $model->attributeExportedLabels());
        $this->assertInternalType('array', $model->getComparaisonNumerique());
        $this->assertInternalType('array', $model->getComparaisonString());
    }
}