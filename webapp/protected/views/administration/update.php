<div class="panel panel-primary">
    <div class="panel-heading"><h4 align="center">Mise à jour des droits du profil <?php echo $model->profil; ?> pour les fiches <?php echo $model->type; ?>s</h4></div>
    <div class="panel-body">
        <?php

            echo $this->renderPartial('_form', array('model' => $model));
        ?>
    </div>
</div>