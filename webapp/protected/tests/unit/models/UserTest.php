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
        $criteria = new EMongoCriteria;
        $criteria->login = "Bernard";
        $user = User::model()->find($criteria);
        $this->assertInternalType('array', $model->getAllUsersLastnames());
        $this->assertInternalType('array', $model->getAllUsersFirstnames());
        $this->assertInternalType('array', $model->getAllUsersLogin());
        $this->assertInternalType('array', $model->getAllUsersEmail());
        $this->assertInternalType('array', $model->getAllUsersByLogin($user));
        $this->assertInternalType('array', $model->getArrayProfil());
        $this->assertInternalType('object', $model->getArrayProfilSorted());
        $this->assertInternalType('array', $model->getArrayProfilFiltered());
        $this->assertInternalType('array', $model->getArrayAvailableProfil($user->_id));
        $this->assertInternalType('array', $model->getArrayCentre());
    } 
}
