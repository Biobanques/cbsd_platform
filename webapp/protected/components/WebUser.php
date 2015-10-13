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
    
    /**
     * return true if user is admin
     * @return boolean
     */
    public function isAdmin() {
        return in_array("administrateur", $this->getState('profil'));
    }

    /**
     * return true if user is clinicien
     * @return boolean
     */
    public function isClinicien() {
        return in_array("clinicien", $this->getState('profil'));
    }

    /**
     * return true if user is neuropathologiste
     * @return boolean
     */
    public function isNeuropathologiste() {
        return in_array("neuropathologiste", $this->getState('profil'));
    }

    /**
     * return true if user is généticien
     * @return boolean
     */
    public function isGeneticien() {
        return in_array("geneticien", $this->getState('profil'));
    }
    
    /**
     * return true if user is chercheur
     * @return boolean
     */
    public function isChercheur() {
        return (in_array("chercheur", $this->getState('profil')));
    }

    /**
     * profils : clinicien, geneticien, neuropathologiste, chercheur
     * @return active profil
     */
    public function getActiveProfil() {
        if ($this->isClinicien())
            return "clinicien";
        else if ($this->isNeuropathologiste())
            return "neuropathologiste";
        else if ($this->isGeneticien())
            return "geneticien";
        else if ($this->isChercheur())
            return "chercheur";
    }
    
}

?>