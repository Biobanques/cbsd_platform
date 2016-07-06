<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PatientFormTest extends PHPUnit_Framework_TestCase {
    
    /**
     * testing method return array type
     */
    public function testTypeFunction() {
        $model = new PatientForm;
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
        $dateTrue = "06/07/2016";
        $dateFalse = "06-07-2016";
        $this->assertTrue($model->dateFormat($dateTrue));
        $this->assertFalse($model->dateFormat($dateFalse));
        $this->assertInternalType('array', $model->getGenre());
        $this->assertInternalType('array', $model->getSource());
    }
}