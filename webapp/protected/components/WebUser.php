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
        return $model->nom;
    }

    /**
     * return user first name
     * @return first name
     */
    public function getPrenom()
    {
        $model = User::model()->findByPk(new MongoId(Yii::app()->user->id));
        return $model->prenom;
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
        return $this->getState('profil') != null ? in_array("administrateur", $this->getState('profil')) : false;
    }

    /**
     * return true if user is clinicien
     * @return boolean
     */
    public function isClinicien()
    {
        return in_array("clinicien", $this->getState('profil'));
    }

    /**
     * return true if user is neuropathologiste
     * @return boolean
     */
    public function isNeuropathologiste()
    {
        return in_array("neuropathologiste", $this->getState('profil'));
    }

    /**
     * return true if user is généticien
     * @return boolean
     */
    public function isGeneticien()
    {
        return in_array("geneticien", $this->getState('profil'));
    }

    /**
     * return true if user is chercheur
     * @return boolean
     */
    public function isChercheur()
    {
        return (in_array("chercheur", $this->getState('profil')));
    }

    public function getUserProfil()
    {
        $user = User::model()->findByPk(new MongoID(Yii::app()->user->id));
        return $user->profil;
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

    /**
     * affiche l'item "view" qui dépend du profil de l'utilisateur et de ses droits sur une fiche
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
