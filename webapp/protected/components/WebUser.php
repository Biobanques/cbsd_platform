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
     * profils dans l'ordre par défaut : neuropathologiste, geneticien, clinicien, chercheur
     * @return active profil
     */
    public function getActiveProfil() {
        return $this->getState('activeProfil');
    }

    public function setActiveProfil($activeProfil) {
        if ($activeProfil == "") {
            $this->setState('activeProfil', $this->getState("defaultProfil"));
        } else
        if (in_array($activeProfil, $this->getState('profil'))) {
            $this->setState('activeProfil', $activeProfil);
        }
        $this->setState('activeProfil', $this->getState("defaultProfil"));
    }

}
?>