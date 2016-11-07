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

    public static function getHTML()
    {
        $profilsList = array();

        foreach (Yii::app()->user->getState('profil') as $profil) {
            $profilsList[$profil] = Yii::t('common', $profil);
            asort($profilsList);
        }
        if (!Yii::app()->user->isAdmin() && array_merge($profilsList, array("administrator" => Yii::t('common', 'administrator'))) != User::model()->getArrayProfil()) {
            $profilsList['newProfil'] = "Demander un nouveau profil";
        }
        $controler = Yii::app()->getController()->getId();
        $action = Yii::app()->getController()->getAction()->getId();
        $html = CHtml::form(Yii::app()->createUrl("$controler/$action"), "POST", array('class' => "navbar-form pull-left"));

        $html.=CHtml::dropDownList("activeProfil", Yii::app()->user->getState('activeProfil'), $profilsList, array('id' => "profil", "style" => "width:150px; margin-top: -3px; margin-left: -25px;", "onchange" => "this.form.submit()"));
        $html.= CHtml::endForm();
        return $html;
    }
}