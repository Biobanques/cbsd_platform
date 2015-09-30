<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
        const ERROR_INACTIVE=3;
        
	private $_id;
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$record=User::model()->findByAttributes(array('login'=>$this->username));
		if($record==null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($record->password!=$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
                elseif($record->statut=="inactif")
 			$this->errorCode=self::ERROR_INACTIVE;
		else{
			$this->errorCode=self::ERROR_NONE;
			$this->_id=$record->_id;
                        //on stocke le profil pour checker plus tard si admin
			$this->setState('profil', $record->profil);			
		}
		return $this->errorCode;
	}
	public function getId()
	{
		return $this->_id;
	}
}