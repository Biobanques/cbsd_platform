<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WebUserTest
 *
 * @author te
 */
class WebUserTest {
    
    /**
     * return user last name
     * @return last name
     */
    public function testGetNom()
    {
        $criteria = new EMongoCriteria;
        $criteria->login = "Bernard";
        $user = User::model()->find($criteria);
        $this->assertInternalType('object', $user);
    }

    /**
     * return user first name
     * @return first name
     */
    public function testGetPrenom()
    {
        $criteria = new EMongoCriteria;
        $criteria->login = "Bernard";
        $user = User::model()->find($criteria);
        $this->assertInternalType('object', $user);
    }

    /**
     * set the admin value
     * @param boolean $val
     */
    public function testSetAdmin()
    {

    }

    /**
     * return true if user is admin
     * @return boolean
     */
    public function testIsAdmin()
    {

    }

    /**
     * return true if user is clinicien
     * @return boolean
     */
    public function testIsClinicien()
    {

    }

    /**
     * return true if user is neuropathologiste
     * @return boolean
     */
    public function testIsNeuropathologiste()
    {

    }

    /**
     * return true if user is généticien
     * @return boolean
     */
    public function testIsGeneticien()
    {

    }

    /**
     * return true if user is chercheur
     * @return boolean
     */
    public function testIsChercheur()
    {

    }

    public function testGetUserProfil()
    {

    }

    /**
     * profils dans l'ordre par défaut : neuropathologiste, geneticien, clinicien, chercheur
     * @return active profil
     */
    public function testGetActiveProfil()
    {

    }

    public function testSetActifProfil()
    {

    }

    public function testSetActiveProfil()
    {

    }

    /**
     * ajouter un nouveau profil lors de l'inscription de l'utilisateur connecté 
     * @return array
     */
    public function testSetNewProfil()
    {

    }

    /**
     * affiche l'item "view" qui dépend du profil de l'utilisateur et de ses droits sur une fiche
     * @return boolean
     */
    public function testIsAuthorizedView()
    {

    }

    /**
     * affiche l'item "update" qui dépend du profil de l'utilisateur et de ses droits sur une fiche
     * @return boolean
     */
    public function testIsAuthorizedUpdate()
    {

    }

    /**
     * affiche l'item "delete" qui dépend du profil de l'utilisateur et de ses droits sur une fiche 
     * @return boolean
     */
    public function testIsAuthorizedDelete() {

    }

    /**
     * Droit "créer une fiche" qui dépend du profil de l'utilisateur et de ses droits sur une fiche 
     * @return boolean
     */
    public function testIsAuthorizedCreate() {
 
    }
}
