<style>
    td {
        text-align: center;
        vertical-align: middle;
    }
    table tr td
    {
        padding: 10px !important;
    }
</style>

<h1>Gestion des doublons</h1>
<div class="info">
    <div class="title"><?php echo Yii::t('doublon', 'infoTitle') ?></div>
    <div class="content"><?php echo Yii::t('doublon', 'infoContent') ?></div>
</div>
<?php if ($modelAnswerBis != null) { ?>
<div class ="row">
    <div class="col-lg-5">

        <?php
        echo "<table border=1><h3 align=\"center\">Fiche actuelle</h3>";
        foreach ($modelAnswer as $k => $v) {
            if ($k != "_id") {
                if (isset($modelAnswer->$k) && isset($modelAnswerBis->$k)) {
                    if ($modelAnswer->$k == $modelAnswerBis->$k) {
                        echo "<tr><td align=\"center\"><b>" . $k . "</b></td><td>" . $v . "</td></tr>";
                    } else {
                        echo "<tr><td align=\"center\"><b><font color=\"red\">" . $k . "</font></b></td><td><font color=\"red\">" . $v . "</font></td></tr>";
                    }
                } elseif (isset($modelAnswer->$k) && !isset($modelAnswerBis->$k) || !isset($modelAnswer->$k) && isset($modelAnswerBis->$k)) {
                    echo "<tr><td align=\"center\"><b><font color=\"red\">" . $k . "</font></b></td><td><font color=\"red\">" . $v . "</font></td></tr>";
                } else {
                    echo "<tr><td align=\"center\"><b><font color=\"red\">" . $k . "</font></b></td><td><font color=\"red\">" . $v . "</font></td></tr>";
                }
            }
        }
        echo "</table>";
        ?>
    </div>
    <div class="col-lg-2">
        <table>
            <?php echo "<tr><td>" . CHtml::link(CHtml::image(Yii::app()->request->baseUrl . '/images/validate.png', '', array("width" => "50px", "height" => "50px")), Yii::app()->createUrl("fileImport/adminDoublon", array('acceptAll' => $modelAnswerBis->id_cbsd))) . "</td></tr>"; ?>
            <?php echo "<tr><td>" . CHtml::link(CHtml::image(Yii::app()->request->baseUrl . '/images/annuler.png', '', array("width" => "50px", "height" => "50px")), Yii::app()->createUrl("fileImport/adminDoublon", array('refuseAll' => $modelAnswerBis->id_cbsd))) . "</td></tr>"; ?>
            <?php echo "<tr><td>" . CHtml::link(CHtml::image(Yii::app()->request->baseUrl . '/images/wait2.png', '', array("width" => "50px", "height" => "50px")), Yii::app()->createUrl("fileImport/adminDoublon", array('next' => $modelAnswerBis->id_cbsd))) . "</td></tr>"; ?>
        </table>
    </div>
    <div class="col-lg-5">
        <?php
        echo "<table border=1><h3 align=\"center\">Nouvelle fiche</h3>";
        foreach ($modelAnswerBis as $k => $v) {
            if ($k != "_id") {
                if (isset($modelAnswerBis->$k) && isset($modelAnswer->$k)) {
                    if ($modelAnswerBis->$k == $modelAnswer->$k) {
                        echo "<tr><td align=\"center\"><b>" . $k . "</b></td><td>" . $v . "</td></tr>";
                    } else {
                        echo "<tr><td align=\"center\"><b><font color=\"red\">" . $k . "</font></b></td><td><font color=\"red\">" . $v . "</font></td></tr>";
                    }
                } elseif (isset($modelAnswerBis->$k) && !isset($modelAnswer->$k) || !isset($modelAnswerBis->$k) && isset($modelAnswer->$k)) {
                    echo "<tr><td align=\"center\"><b><font color=\"red\">" . $k . "</font></b></td><td><font color=\"red\">" . $v . "</font></td></tr>";
                } else {
                    echo "<tr><td align=\"center\"><b><font color=\"red\">" . $k . "</font></b></td><td><font color=\"red\">" . $v . "</font></td></tr>";
                }
            }
        }
        echo "</table>";
        ?>
    </div>
</div>
<div class="row">
    <?php
    echo "<h3 style=\"text-align:center;\" >Prélèvement Tissue Tranche</h3>";
$modelTranche = new Tranche;
$criteria = new EMongoCriteria();
$criteria->id_donor = $modelAnswer->id_donor;
$dataProvider = new EMongoDocumentDataProvider('Tranche', array('criteria' => $criteria));
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array('header' => $modelTranche->attributeLabels()["presenceCession"], 'name' => 'presenceCession'),
        array('header' => $modelTranche->attributeLabels()["hemisphere"], 'name' => 'hemisphere'),
        array('header' => $modelTranche->attributeLabels()["idPrelevement"], 'name' => 'idPrelevement'),
        array('header' => $modelTranche->attributeLabels()["nameSamplesTissue"], 'name' => 'nameSamplesTissue'),
        array('header' => $modelTranche->attributeLabels()["originSamplesTissue"], 'name' => 'originSamplesTissue'),
        array('header' => $modelTranche->attributeLabels()["prelevee"], 'name' => 'prelevee'),
        array('header' => $modelTranche->attributeLabels()["nAnonymat"], 'name' => 'nAnonymat'),
        array('header' => $modelTranche->attributeLabels()["qualite"], 'name' => 'qualite'),
        array('header' => $modelTranche->attributeLabels()["quantityAvailable"], 'name' => 'quantityAvailable'),
        array('header' => $modelTranche->attributeLabels()["storageConditions"], 'name' => 'storageConditions')
    ),
));
?>
</div>
<div class="row">
    <?php
    echo "<h3 style=\"text-align:center;\" >Prélèvement Tissue Tranche BIS</h3>";
$modelTranche = new Tranche;
$criteria = new EMongoCriteria();
$criteria->id_donor = $modelAnswerBis->id_donor;
$dataProvider = new EMongoDocumentDataProvider('TrancheBis', array('criteria' => $criteria));
?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array('header' => $modelTranche->attributeLabels()["presenceCession"], 'name' => 'presenceCession'),
        array('header' => $modelTranche->attributeLabels()["hemisphere"], 'name' => 'hemisphere'),
        array('header' => $modelTranche->attributeLabels()["idPrelevement"], 'name' => 'idPrelevement'),
        array('header' => $modelTranche->attributeLabels()["nameSamplesTissue"], 'name' => 'nameSamplesTissue'),
        array('header' => $modelTranche->attributeLabels()["originSamplesTissue"], 'name' => 'originSamplesTissue'),
        array('header' => $modelTranche->attributeLabels()["prelevee"], 'name' => 'prelevee'),
        array('header' => $modelTranche->attributeLabels()["nAnonymat"], 'name' => 'nAnonymat'),
        array('header' => $modelTranche->attributeLabels()["qualite"], 'name' => 'qualite'),
        array('header' => $modelTranche->attributeLabels()["quantityAvailable"], 'name' => 'quantityAvailable'),
        array('header' => $modelTranche->attributeLabels()["storageConditions"], 'name' => 'storageConditions')
    ),
));
?>
</div>
<?php } else { echo "<h3> Pas de doublons.</h3>"; }