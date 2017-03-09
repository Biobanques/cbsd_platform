<?php

/**
 * classe WebUser pour ajouter le statut admin et/ou admin biobank a un user.
 * Permet de gerer simplement les roles du user
 * @author nicolas
 *
 */
class WebUser extends CWebUser
{

    /**
     * return user last name
     * @return last name
     */
    public function getNom()
    {
        $model = User::model()->findByPk(new MongoId(Yii::app()->user->id));
        return $model != null ? $model->nom : null;
    }

    /**
     * return user first name
     * @return first name
     */
    public function getPrenom()
    {
        $model = User::model()->findByPk(new MongoId(Yii::app()->user->id));
        return $model != null ? $model->prenom : null;
    }
    
    /**
     * return user first name and last name
     * @return first name + last name
     */
    public function getNomPrenom()
    {
        $model = User::model()->findByPk(new MongoId(Yii::app()->user->id));
        return $model != null ? ucfirst($model->prenom) . " " . strtoupper($model->nom) : null;
    }

    /**
     * set the admin value
     * @param boolean $val
     */
    public function setAdmin($val)
    {
        $admin = $val;
    }

    /**
     * return true if user is admin
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->getState('activeProfil') == "administrateur" ? true : false; 
    }

    /**
     * return true if user is clinicien
     * @return boolean
     */
    public function isClinicien()
    {
        return $this->getState('activeProfil') == "clinicien" ? true : false; 
    }

    /**
     * return true if user is neuropathologiste
     * @return boolean
     */
    public function isNeuropathologiste()
    {
        return $this->getState('activeProfil') == "neuropathologiste" ? true : false; 
    }

    /**
     * return true if user is généticien
     * @return boolean
     */
    public function isGeneticien()
    {
        return $this->getState('activeProfil') == "geneticien" ? true : false; 
    }

    /**
     * return true if user is chercheur
     * @return boolean
     */
    public function isChercheur()
    {
        return $this->getState('activeProfil') == "chercheur" ? true : false; 
    }
    
    /**
     * return true if user is Master
     * @return boolean
     */
    public function isMaster()
    {
        $profil = array("clinicienMaster", "neuroMaster", "geneticienMaster");
        return in_array($this->getState('activeProfil'), $profil) ? true : false;
    }

    public function getUserProfil()
    {
        $user = User::model()->findByPk(new MongoID(Yii::app()->user->id));
        return $user != null ? $user->profil : null;
    }

    /**
     * profils dans l'ordre par défaut : neuropathologiste, geneticien, clinicien, chercheur
     * @return active profil
     */
    public function getActiveProfil()
    {
        return $this->getState('activeProfil');
    }

    public function setActifProfil($activeProfil)
    {
        $this->setState('activeProfil', $activeProfil);
    }

    public function setActiveProfil($activeProfil)
    {
        if ($activeProfil == "") {
            $this->setState('activeProfil', $this->getState("defaultProfil"));
        } elseif (in_array($activeProfil, $this->getState('profil'))) {
            $this->setState('activeProfil', $activeProfil);
        }
        $this->setState('activeProfil', $this->getState("defaultProfil"));
    }

    /**
     * ajouter un nouveau profil lors de l'inscription de l'utilisateur connecté 
     * @return array
     */
    public function setNewProfil($newProfil)
    {
        $user = User::model()->findByPk(new MongoID(Yii::app()->user->id));
        $userProfil = array();
        array_push($user->profil, $newProfil);
        foreach ($user->profil as $key => $profil) {
            $userProfil[$profil] = $profil;
        }
        return $userProfil;
    }
    
    public function isAuthorizedViewPatientNavbar()
    {
        $profil = array("administrateur", "clinicien", "neuropathologiste", "geneticien");
        return in_array($this->getState('activeProfil'), $profil) ? true : false; 
    }

    public function isAuthorizedViewSearchNavbar()
    {
        $profil = array("administrateur", "administrateur de projet", "neuropathologiste", "geneticien", "chercheur", "clinicienMaster", "neuroMaster", "geneticienMaster");
        return in_array($this->getState('activeProfil'), $profil) ? true : false; 
    }
    
    public function isAuthorizedViewAdminNavbar()
    {
        $profil = array("administrateur");
        return in_array($this->getState('activeProfil'), $profil) ? true : false; 
    }

    /**
     * retourne vrai si l'utilisateur peut voir la liste des fiches (clinique, neuropathologique, génétique) en fonction des droits
     * @return boolean
     */
    public function isAuthorizedView($profil, $fiche)
    {
        $criteria = new EMongoCriteria();
        $criteria->profil = $profil;
        $criteria->type = $fiche;
        $droit = Droits::model()->find($criteria);
        return $droit != null ? in_array("view", $droit->role) : false;
    }
    
    /**
     * Affiche l'item "view" sur une fiche en fonction des droits de l'utilisateur
     * @return boolean
     */
    public function isAuthorizedViewFiche($user, $profil, $fiche)
    {
        $criteria = new EMongoCriteria();
        $criteria->profil = $profil;
        $criteria->type = $fiche;
        $droit = Droits::model()->find($criteria);
        if ($droit != null) {
            if ($fiche == "clinique" && Yii::app()->user->id == $user) {
                return true;
            } else {
                return in_array("view", $droit->role);
            }
        } else {
            return false;
        }
    }

    /**
     * affiche l'item "update" qui dépend du profil de l'utilisateur et de ses droits sur une fiche
     * @return boolean
     */
    public function isAuthorizedUpdate($profil, $fiche)
    {
        $criteria = new EMongoCriteria();
        $criteria->profil = $profil;
        $criteria->type = $fiche;
        $droit = Droits::model()->find($criteria);
        return $droit != null ? in_array("update", $droit->role) : false;
    }

    /**
     * affiche l'item "delete" qui dépend du profil de l'utilisateur et de ses droits sur une fiche 
     * @return boolean
     */
    public function isAuthorizedDelete($profil, $fiche) {
        $criteria = new EMongoCriteria();
        $criteria->profil = $profil;
        $criteria->type = $fiche;
        $droit = Droits::model()->find($criteria);
        return $droit != null ? in_array("delete", $droit->role) : false;
    }

    /**
     * Droit "créer une fiche" qui dépend du profil de l'utilisateur et de ses droits sur une fiche 
     * @return boolean
     */
    public function isAuthorizedCreate($profil, $fiche) {
        $criteria = new EMongoCriteria();
        $criteria->profil = $profil;
        $criteria->type = $fiche;
        $droit = Droits::model()->find($criteria);
        return $droit != null ? in_array("create", $droit->role) : false;
    }

}

?>
