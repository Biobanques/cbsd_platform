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
class QuestionBlocTest extends PHPUnit_Framework_TestCase {

    public function testTypeQuestionBlocModel() {
        $criteria = new EMongoCriteria();
        $criteria->title = "Renseignements individuels";
        $questionBloc = QuestionBloc::model()->findAll($criteria);
        $this->assertInternalType('array', $questionBloc);
    }
    
    /**
     * testing method return array type
     */
    public function testTypeFunction() {
        $model = new QuestionBloc;
        $criteria = new EMongoCriteria;
        $criteria->title = "Renseignements individuels";
        $questionBloc = QuestionBloc::model()->find($criteria);
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
        $this->assertInternalType('object', $model->search());
        $this->assertInternalType('array', $model->getBlocsByTitle($questionBloc->title));
        $this->assertInternalType('array', $model->getAllBlocsTitles());
        $this->assertInternalType('array', $model->getAllTitlesBlocs());
    }
}
