<h1>Utilisateur <?php echo $model->login; ?></h1>

<div class="row">
    <div class="col-lg-12">
        <?php
        $this->widget('zii.widgets.CDetailView', array(
            'data' => $model,
            'attributes' => array(
                'login',
                array(
                    'name' => 'profil',
                    'type' => 'text',
                    'value' => implode(", ", $model->profil)
                ),
                'nom',
                'prenom',
                'email',
                'telephone',
                'gsm',
                'address',
                'centre',
                '_id',
            ),
        ));
        ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div style="display:inline; margin:40%; width: 100px; ">
            <?php
            echo CHtml::link('Retour', array('user/admin'), array('class' => 'btn btn-primary'));
            ?>
        </div>
    </div>
</div>