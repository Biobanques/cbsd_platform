<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GetProfil
 *
 * @author te
 */
class GetProfil {
    public static function getHTML(){
        $html = "<form class=\"navbar-form pull-left\">
                 <select id=\"profil\" style=\"width:150px; margin-top: -3px; margin-left: -25px;\" onchange=\"checkProfil()\">
                 <option value=\"\">----</option>";
        $items = User::model()->getArrayProfilFiltered();
        foreach($items as $item) {
           $html .= "<option value=\"". $item . "\">". $item . "</option>";
        }
        $html .= "</select></form>";
        return $html;
    }
}
