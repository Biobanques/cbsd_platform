<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class QuestionGroupTest extends PHPUnit_Framework_TestCase {

    /**
     * testing method return array type
     */
    public function testTypeFunction() {
        $model = new QuestionGroup;
        $questionBlocForm = new QuestionBlocForm;
        $questionBlocForm->title = "Test";
        $questionBlocForm->questions = array();
        $questionBlocForm->parent_group = "Test";
        $questionBlocForm->id = "test";
        $questionBlocForm->title_fr = $questionBlocForm->title;
        $questionBloc = new QuestionBloc;
        $questionBloc->title = "Test";
        $questionBloc->questions = array();
        $questionBloc->parent_group = "Test";
        $questionBloc->title_fr = $questionBloc->title;
        
        $model->copy($questionBlocForm, $questionBloc);
        $this->assertFalse($model->deleteQuestion($model->id));
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
    }

}