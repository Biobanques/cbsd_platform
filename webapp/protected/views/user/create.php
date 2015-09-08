<?php
if (!Yii::app()->user->isGuest) {
    $this->breadcrumbs=array(
            'Gestion des utilisateurs'=>array('index'),
            'Créer un utilisateur',
    );
} else {
    $this->breadcrumbs=array(
        'S\'inscrire',
    );
}

if (!Yii::app()->user->isGuest) {
    $this->menu=array(
            array('label'=>'List User', 'url'=>array('index')),
            array('label'=>'Manage User', 'url'=>array('admin')),
    );
}

if (!Yii::app()->user->isGuest)
    echo "<h1>Créer un utilisateur</h1>";
else
    echo "<h1>S'inscrire</h1>"
    . "<h5>L'inscription au site cbsdforms comporte une étape de validation de votre compte.<br />
Votre inscription sera effective une fois votre compte validé par l'administrateur de la plate-forme. <br />
Le délai maximal de validation des comptes est de 24h.</h5>";

echo $this->renderPartial('_form', array('model'=>$model));
?>