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
  * set the cbsdformsAdmin value
  * @param boolean $val
  */
 
 public function SetBiobankAdmin($val){
 	$cbsdformsAdmin=$val;
 }
 
 /**
  * return true if user is biobank admin
  * @return boolean
  */
 public function isBiobankAdmin(){
 	return $this->getState('cbsdforms_id')!=null;
 }
 //get the logged user
 public function getUser(){
  return $this->_user;
 }
}
?>