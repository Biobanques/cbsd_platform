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
class RecoverPwdFormTest extends PHPUnit_Framework_TestCase {

    /**
     * testing method return array type
     */
    public function testTypeFunction() {
        $model = new RecoverPwdForm;
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
        $this->assertInternalType('array', $model->validateFields());
    }

    /**
     * testing method
     */
    public function testValidateFields() {
        $model = new RecoverPwdForm;
        $this->assertEmpty($model->login);
        $this->assertEmpty($model->email);
        $model->login = "Test";
        $model->email = "test@test.fr";
        $this->assertNotEmpty($model->login);
        $this->assertNotEmpty($model->email);
        $user = $this->getUser();
        $this->assertNotNull($user);
    }
    
    public function getUser() {
        $model = new User;
        $model->login = "test";
        $model->password = "test2016";
        $model->profil = "clinicien";
        $model->nom = "testNom";
        $model->prenom = "testPrénom";
        $model->email = "test@gmail.com";
        $model->telephone = "0123456789";
        $model->address = "5 rue Marat";
        $model->centre = "Paris";
        
        return $model;
    }
}
