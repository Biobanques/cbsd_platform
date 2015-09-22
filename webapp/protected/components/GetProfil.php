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
        $html = "";
        $items = User::model()->getArrayProfilFiltered();
        foreach($items as $item) {
           $html .= "<option value=\"". $item . "\">". $item . "</option>";
        }
        return $html;
    }
}
