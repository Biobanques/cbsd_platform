<?php

class AnswerPDFRenderer
{
    public static $LINE_HEIGHT = 7;

    public static function renderAnswer($answer)
    {
        require_once(Yii::getPathOfAlias('application.vendor') . '/tcpdf/tcpdf.php');
// create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setPDFVersion('1.7');
// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Biobanques');
        $pdf->SetTitle($answer->name);
        $pdf->SetSubject($answer->name);
        $pdf->SetKeywords('Biobanques, PDF, cbsd, platform' . $answer->name);
// set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);




// set some language-dependent strings (optional)
        /* if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
          require_once(dirname(__FILE__) . '/lang/eng.php');
          $pdf->setLanguageArray($l);
          } */
// ---------------------------------------------------------
// IMPORTANT: disable font subsetting to allow users editing the document
        $pdf->setFontSubsetting(false);
// set font
        $pdf->SetFont('helvetica', '', 8, '', false);
// add a page
        $pdf->AddPage();
        //form default properties
        $pdf->setFormDefaultProp(array('lineWidth' => 1, 'borderStyle' => 'solid', 'fillColor' => "lightGray", 'strokeColor' => "gray"));

        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->Cell(0, 5, 'Biobanques CBSDPlatform ', 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(0, 5, $answer->name, 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(0, 5, "-", 0, 1, 'C');
        $pdf->Ln(10);
        $pdf->Cell(0, 5, $answer->name, 0, 1, 'C');
        $pdf->Ln(30);
        $pdf->SetFont('helvetica', 'N', 12);
        $dd = $answer->last_modified['date']; //mongo date
        $html = '<span>' . "<b>Description :</b> " . $answer->description . '<br /><b>Dernière modification : </b>' . date("d/m/Y H:i", strtotime($dd)) . '</span>';
        $pdf->writeHTMLCell(0, 0, '', '', $html, 1, 1, false, true, '', false);
        $pdf->Ln(10);

        $pdf = AnswerPDFRenderer::renderAnsweredPDF($pdf, $answer, "fr");

// reset pointer to the last page
        $pdf->lastPage();

// ---------------------------------------------------------
//Close and output PDF document
        $pdf->Output('biobanques_cbsdplatform_' . $answer->id . '.pdf', 'D');
    }

    /**
     * render in xhtml the answer to the pdf output
     */
    public static function renderAnsweredPDF($pdf, $answer, $lang) {
        foreach ($answer->answers_group as $answer_group) {
            if ($answer_group->parent_group == "") {
                // set a bookmark for the current position
                $pdf->AddPage();
                $pdf->Bookmark($answer_group->title_fr, 0, 0, '', 'B', array(0, 64, 128));
                $pdf = AnswerPDFRenderer::renderAnswerGroupPDF($pdf, $answer, $answer_group, $lang, false);
            }
        }
        return $pdf;
    }

    /**
     * render an answer group.
     * @param type $answer
     * @param type $answers_group
     * @param type $lang
     * @return string
     */
    public function renderAnswerGroupPDF($pdf, $answer, $group, $lang)
    {
        $pdf->Ln(10);
        //en par defaut
        $title = $group->title;
        if ($lang == "fr") {
            $title = $group->title_fr;
        }
        if ($lang == "both") {
            $title = "<i>" . $group->title . "</i> / " . $group->title_fr;
        }
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetFillColor(211, 211, 211);
        $pdf->Cell(0, 5, $title, 0, 2, 'L', true);
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', '', 12);
        if (isset($group->answers)) {
            foreach ($group->answers as $ans) {
                $pdf = AnswerPDFRenderer::renderAnswerPDF($pdf, $group->id, $ans, $lang);
            }
        }
        //add question groups that have parents for this group
        $groups = $answer->answers_group;
        foreach ($groups as $qg) {
            if ($qg->parent_group == $group->id) {
                $pdf = AnswerPDFRenderer::renderAnswerGroupPDF($pdf, $answer, $qg, $lang);
            }
        }
        return $pdf;
    }

    /*
     * render html the current answer.
     */

    public function renderAnswerPDF($pdf, $idanswergroup, $answer, $lang)
    {
        //par defaut lang = enif ($lang == "en")
        $label = $answer->label;
        if ($lang == "fr") {
            $label = $answer->label_fr;
        }
        if ($lang == "both") {
            $label = $answer->label;
        }
        if ($answer->style != "float:right") {
            $pdf->Ln(11);
        }
        $pdf->MultiCell(55, 10, $label, 1, 'L', 1, 0, '', '', true, 0, false, true, 0, 'T', true);
        //affichage de l input selon son type
        $id = $idanswergroup . "_" . $answer->id;
        if ($answer->type == "input" || $answer->type == "number" || $answer->type == "expression") {
            $pdf->MultiCell(120, 10, $answer->answer, 1, 'L', 0, 0, '', '', true, 0, false, true, 0, 'T', true);
        }
        if ($answer->type == "date") {
            $pdf->MultiCell(120, 10, date("d/m/Y", strtotime($answer->answer['date'])), 1, 'L', 0, 0, '', '', true, 0, false, true, 0, 'T', true);
        }
        if ($answer->type == "radio") {
            if ($lang == "fr" && $answer->values_fr != "") {
                $values = $answer->values_fr;
            } else {
                $values = $answer->values;
            }
            $arvalue = split(",", $values);
            foreach ($arvalue as $value) {
                $pdf->RadioButton($id, 5, array('readonly' => 'true'), array(), $value, $answer->answer == $value ? true : false);
                $pdf->Cell(20, 5, $value);
            }
        }
        if ($answer->type == "checkbox") {
            $pdf->Ln(10);
            $values = $answer->values;
            if ($lang == "fr" && isset($answer->values_fr)) {
                $values = $answer->values_fr;
            }
            $arvalue = split(",", $values);

            foreach ($arvalue as $value) {
                $pdf->Cell(20, 5, "");
                $cVal = false;
                if (isset($answer->answer) && in_array($value, $answer->answer)) {
                    $cVal = true;
                }
                $pdf->CheckBox($id . "_" . $value, 5, $cVal, array('readonly' => 'true'), array(), $value);
                $pdf->Cell(35, 5, $value);
                $pdf->Ln(10);
            }
        }
        if ($answer->type == "text") {
            $pdf->TextField($id, 60, 18, array('multiline' => true, 'lineWidth' => 0, 'borderStyle' => 'none', 'readonly' => 'true'), array('v' => $answer->answer, 'dv' => ''));
            $pdf->Ln(22);
        }
        if ($answer->type == "list") {
            $values = $answer->values;
            //make an array of associations keys-values ( here key=value)
            $arvalue = split(",", $values);
            //write content of the answer into a rectangle
            $pdf->MultiCell(120, 10, $answer->answer, 1, 'R', 0, 1, '', '', true);
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'I', 10);
            $pdf->MultiCell(55, 5, ">>Valeurs possibles:", 1, 'R', 1, 0, '', '', true);
            $pdf->MultiCell(120, 5, "", 0, 'R', 0, 1, '', '', true);
            foreach ($arvalue as $value) {
                $pdf->MultiCell(55, 5, "", 0, 'L', 0, 0, '', '', true);
                $pdf->MultiCell(120, 5, $value, 'LB', 'R', 0, 1, '', '', true, 1);
            }
            $pdf->SetFont('helvetica', '', 12);
            //the combo box doesn t work on chrome, foxit, but works on acrobat reader, aperçu ( mac)
            //$pdf->ComboBox($id, 30, 5, $arrValuesPDF, array(), array('V' => $answer->answer));
        }

        if ($answer->type == "array") {
            $pdf->Ln(6);
            $rows = $answer->rows;
            $arrows = split(",", $rows);
            $cols = $answer->columns;
            $arcols = split(",", $cols);
            foreach ($arcols as $col) {
                $pdf->Cell(35, 5, $col);
            }
            $pdf->Ln(6);
            foreach ($arrows as $row) {
                $pdf->Cell(35, 5, $row);
                foreach ($arcols as $col) {
                    $idunique = $idanswergroup . "_" . $answer->id . "_" . $row . "_" . $col;
                    $pdf->TextField($idunique, 50, 5);
                }
                $pdf->Ln(6);
            }
        }
        $pdf->Ln(11);
        return $pdf;
    }
}