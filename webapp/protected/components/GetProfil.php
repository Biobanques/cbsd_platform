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
class GetProfil
{

    public static function getHTML() {
//        $html = "<form class=\"navbar-form pull-left\" action=\"#\" method=\"POST\">
//                 <select id=\"profil\" name=\"profil\" style=\"width:150px; margin-top: -3px; margin-left: -25px;\" onchange=\"this.form.submit()\">
//                 <option value=\"\">----</option>";
//        $items = Yii::app()->user->getState("profil");
//        //  $items = User::model()->getArrayProfilFiltered();
//        foreach ($items as $item) {
//            $selected = '';
//            if (isset($_POST['profil']) && $_POST['profil'] == $item)
//                $selected = "selected";
//            $html .= "<option value=\"" . $item . "\"" . $selected . ">" . $item . "</option>";
//        }
//        $html .= "</select></form>";
        $profilsList = array();

        foreach (Yii::app()->user->getState('profil') as $profil) {
            $profilsList[$profil] = $profil;
        }
        $controler = Yii::app()->getController()->getId();
        $action = Yii::app()->getController()->getAction()->getId();
        $html = CHtml::form(Yii::app()->createUrl("$controler/$action"), "POST", array('class' => "navbar-form pull-left"));

        $html.=CHtml::dropDownList("activeProfil", Yii::app()->user->getState('activeProfil'), $profilsList, array('id' => "profil", "style" => "width:150px; margin-top: -3px; margin-left: -25px;", "onchange" => "this.form.submit()"));
        $html.= CHtml::endForm();
        return $html;
    }

}