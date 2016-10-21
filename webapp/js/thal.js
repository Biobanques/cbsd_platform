/**
 * Phase de Thal
 * 
 * Présence d'Abeta dans le néocortex = 1 point
 * Abeta néocortex + hippocampe = 2 points
 * Abeta neocortex + hippo + N Gris centraux = 3 points
 * Abeta néocortex + hippocampe + Noyaux gris + mésencéphale = 4 points
 * Abeta comme précédemment ET dans le cervelet = 5 points
 * Abeta comme précédemment ET dans le cervelet = 6 points
 * Score = max(thal_score)
 */

var thal_score = [0, 0, 0, 0, 0];

$('#questionnaire-form input').on('change', function () {
    // Présence d'Abeta dans le néocortex
    if ($('input[name="Questionnaire[phase_thal_Thal1]"]:checked', '#questionnaire-form').val() == "Oui") {
        thal_score[0] = 1;
    } else
        thal_score[0] = 0;
    // Abeta néocortex + hippocampe
    if ($('input[name="Questionnaire[phase_thal_Thal2]"]:checked', '#questionnaire-form').val() == "Oui") {
        thal_score[1] = 2;
    } else {
        thal_score[1] = 0;
    }
    // Abeta neocortex + hippo + N Gris centraux
    if ($('input[name="Questionnaire[phase_thal_Thal3]"]:checked', '#questionnaire-form').val() == "Oui") {      
        thal_score[2] = 3;
    } else {
        thal_score[2] = 0;
    }
    // Abeta néocortex + hippocampe + Noyaux gris + mésencéphale
    if ($('input[name="Questionnaire[phase_thal_Thal4]"]:checked', '#questionnaire-form').val() == "Oui") {       
        thal_score[3] = 4;
    } else {
        thal_score[3] = 0;
    }
    // Abeta comme précédemment ET dans le cervelet
    if ($('input[name="Questionnaire[phase_thal_Thal5]"]:checked', '#questionnaire-form').val() == "Oui") {      
        thal_score[4] = 5;
    } else {
        thal_score[4] = 0;
    }
    // Score = max(thal_score)
    document.getElementById("phase_thal_thal_score").value = thal_score.reduce(function (p, v) {
        return (p > v ? p : v);
    })
});