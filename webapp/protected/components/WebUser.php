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

    /*public function isAuthorized($profil, $link) {
        $profil = $this->getState($profil);
        if ($profil == "administrateur")
            return true;
        else {
            return false;
        }
    }*/
    
    /**
     * return true if user is admin
     * @return boolean
     */
    public function isAdmin() {
        return $this->getState('profil', 'clinicien') == "administrateur";
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