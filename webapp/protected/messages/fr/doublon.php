<?php

/**
 *
 * French translations file for user items and messages
 */
return array(
    'infoTitle' => 'Comment gérer les doublons?',
    'infoContent' => 'Vous pouvez gérer les doublons en choisissant parmi ces trois possibilités:<br>'
    . CHtml::image(Yii::app()->request->baseUrl . '/images/validate.png', '', array("width" => "15px", "height" => "15px")) . ' <b>Accepter tout</b> (la nouvelle fiche remplacera la fiche actuelle)<br>'
    . CHtml::image(Yii::app()->request->baseUrl . '/images/annuler.png', '', array("width" => "15px", "height" => "15px")) . ' <b>Refuser tout</b> (la fiche actuelle sera conservée et la nouvelle fiche sera supprimée)<br>'
    . CHtml::image(Yii::app()->request->baseUrl . '/images/wait2.png', '', array("width" => "15px", "height" => "15px")) . ' <b>Passer en revue</b> (le choix n\'a pas encore été fait et la nouvelle fiche sera alors stockée dans une variable)'
);
