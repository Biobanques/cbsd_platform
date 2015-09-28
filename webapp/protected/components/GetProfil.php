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
        $html = "<form class=\"navbar-form pull-left\" action=\"#\" method=\"POST\">
                 <select id=\"profil\" name=\"profil\" style=\"width:140px; margin-top: -3px; margin-left: -25px;\" onchange=\"this.form.submit()\">
                 <option value=\"\">----</option>";
        $items = User::model()->getArrayProfilFiltered();
        foreach($items as $item) {
            $selected = '';
           if(isset($_POST['profil']) && $_POST['profil']== $item) $selected = "selected";
           $html .= "<option value=\"". $item . "\"" . $selected .">". $item . "</option>";
        }
        $html .= "</select></form>";
        return $html;
    }
}
