<h1>Mise à jour des droits du profil <?php echo $model->profil; ?> pour les fiches <?php echo $model->type; ?>s</h1>


<?php
if ($model->type == "clinique")
    echo $this->renderPartial('_form_clinique', array('model' => $model));
if ($model->type == "neuropathologique")
    echo $this->renderPartial('_form_neuropath', array('model' => $model));
if ($model->type == "genetique")
    echo $this->renderPartial('_form_gene', array('model' => $model));
?>