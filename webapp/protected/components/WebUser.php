<?php
/**
 * classe WebUser pour ajouter le statut admin et/ou admin biobank a un user.
 * Permet de gerer simplement les roles du user
 * @author nicolas
 *
 */
class WebUser extends CWebUser{
 private $_user;
 private $admin=false;
 
 private $cbsdformsAdmin=false;
 
 /**
  * set the admin value
  * @param boolean $val
  */
 public function setAdmin($val){
 	$admin=$val;
 }
 /**
  * return true if user is admin
  * @return boolean
  */
 public function isAdmin(){
  return $this->getState('profil','0')==1;
 }
 
  /**
  * return true if user is clinicien
  * @return boolean
  */
 public function isClinicien(){
  return $this->getState('profil','0')==0;
 }
 
  /**
  * return true if user is neuropathologiste
  * @return boolean
  */
 public function isNeuropathologiste(){
  return $this->getState('profil','0')==2;
 }
 
  /**
  * return true if user is généticien
  * @return boolean
  */
 public function isGeneticien(){
  return $this->getState('profil','0')==3;
 }

 //get the logged user
 public function getUser(){
  return $this->_user;
 }
}
?>