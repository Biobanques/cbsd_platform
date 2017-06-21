<?php

/**
 * unit test class to test User model
 * @author bernard te
 *
 */

class UserTest extends PHPUnit_Framework_TestCase {
    
    public function testTypeUserModel() {
        $criteria = new EMongoCriteria();
        $criteria->login = "Bernard";
        $userLogin = User::model()->findAll($criteria);
        $this->assertInternalType('array', $userLogin);
    }
    
    /**
     * testing method return array type
     */
    public function testTypeFunction() {
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
        
        $criteria = new EMongoCriteria;
        $criteria->login = "Bernard";
        $user = User::model()->find($criteria);
        $this->assertInternalType('object', $model->model());
        $this->assertInternalType('array', $model->rules());
        $this->assertInternalType('array', $model->attributeLabels());
        $this->assertInternalType('array', $model->getAllUsersLastnames());
        $this->assertInternalType('array', $model->getAllUsersFirstnames());
        $this->assertInternalType('array', $model->getAllUsersLogin());
        $this->assertInternalType('array', $model->getAllUsersEmail());
        $this->assertInternalType('array', $model->getAllUsersByLogin($model));
        $model->profil = "clinicien";
        $this->assertInternalType('string', $model->getProfil());
        $model->profil = "";
        $this->assertInternalType('string', $model->getProfil());
        $this->assertInternalType('array', $model->getArrayProfil());
        $this->assertInternalType('object', $model->getArrayProfilSorted());
        $this->assertInternalType('array', $model->getArrayProfilFiltered());
        $this->assertInternalType('array', $model->getArrayAvailableProfil($user->_id));
        $this->assertInternalType('array', CommonTools::getAllReferenceCenter());
        $this->assertNull($model->passwordValidator());
        $this->assertNull($model->telValidator());
        $this->assertNull($model->gsmValidator());
        $this->assertNull($model->alphaOnly());
        $this->assertNull($model->alphaNumericOnly());
        $this->assertNull($model->addressValidator());
        $this->assertNull($model->centreValidator());
        $model->profil = array();
        $model->profil[] = "administrateur";
        $this->assertInternalType('string', $model->getDefaultProfil());
        $model->profil = array();
        $model->profil[] = "neuropathologiste";
        $this->assertInternalType('string', $model->getDefaultProfil());
        $model->profil = array();
        $model->profil[] = "geneticien";
        $this->assertInternalType('string', $model->getDefaultProfil());
        $model->profil = array();
        $model->profil[] = "clinicien";
        $this->assertInternalType('string', $model->getDefaultProfil());
        $model->profil = array();
        $model->profil[] = "chercheur";
        $this->assertInternalType('string', $model->getDefaultProfil());
    } 
}
