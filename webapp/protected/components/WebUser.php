<?php

/**
 * classe WebUser pour ajouter le statut admin et/ou admin biobank a un user.
 * Permet de gerer simplement les roles du user
 * @author nicolas
 *
 */
class WebUser extends CWebUser {

    /**
     * set the admin value
     * @param boolean $val
     */
    public function setAdmin($val) {
        $admin = $val;
    }

    public function isAuthorized($user, $link) {
        // Droits administrateur : Gestion des utilisateurs, Gestion des formulaires, Gestion des fiches, Gestion des blocs, Log système
        if ($link == "user" || $link == "administration" || $link == "fiche" || $link == "questionBloc") {
            // on stocke les profils dans un tableau
            $profil =array();
            // on récupère l'utilisateur courant
            $criteria = new EMongoCriteria();
            $criteria->_id('==', $user);
            $profil = User::model()->findAll($criteria);
            foreach ($profil as $p) {
                foreach ($p->profil as $key=>$value)
                    $profil[] = $value;
            }
            if (in_array("administrateur", $profil))
                return true;
        }
        return false;
    }
    
    /**
     * return true if user is admin
     * @return boolean
     */
    public function isAdmin($user) {
        $profil =array();
        $criteria = new EMongoCriteria();
        $criteria->_id('==', $user);
        $profil = User::model()->findAll($criteria);
        foreach ($profil as $p) {
            foreach ($p->profil as $k=>$v)
                $profil[] = $v;
        }
        if (in_array("administrateur", $profil))
            return true;
    }

    /**
     * return true if user is clinicien
     * @return boolean
     */
    public function isClinicien() {
        return $this->getState('profil', 'clinicien') == "clinicien";
    }

    /**
     * return true if user is neuropathologiste
     * @return boolean
     */
    public function isNeuropathologiste() {
        return $this->getState('profil', 'clinicien') == "neuropathologiste";
    }

    /**
     * return true if user is généticien
     * @return boolean
     */
    public function isGeneticien() {
        return $this->getState('profil', 'clinicien') == "généticien";
    }
    
    /**
     * return true if user is chercheur
     * @return boolean
     */
    public function isChercheur() {
        return $this->getState('profil', 'clinicien') == "chercheur";
    }

}

?>