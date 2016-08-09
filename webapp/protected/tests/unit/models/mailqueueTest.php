<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class mailqueueTest extends PHPUnit_Framework_TestCase {
    
    public function testTypeUserModel() {
        $mailqueue = User::model()->findAll();
        $this->assertInternalType('array', $mailqueue);
    }
    
    /**
     * testing method return array type
     */
    public function testTypeFunction() {
        $model = new mailqueue;
        $this->assertInternalType('object', $model->model());
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
        $this->assertInternalType('object', $model->search());
    } 
}