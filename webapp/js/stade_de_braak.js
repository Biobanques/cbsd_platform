/**
 * Alzheimer atteinte du cortex trans-entorhinal = 1 point
 * Alzheimer atteinte du cortex entorhinal = 2 points
 * Alzheimer atteinte du cortex temporo-occipital = 3 points
 * Alzheimer atteinte du cortex temporal moyen / subiculum = 4 points
 * Alzheimer atteinte du cortex parastrié = 5 points
 * Alzheimer atteinte du cortex strié (couche V) = 6 points
 * Score = max(braak_score)
 */

var braak_score = [0, 0, 0, 0, 0, 0];

$('#questionnaire-form input').on('change', function () {
    // Alzheimer atteinte du cortex trans-entorhinal
    if ($('input[name="Questionnaire[braak_braak1]"]:checked', '#questionnaire-form').val() == "Oui")
        braak_score[0] = 1;
    else
        braak_score[0] = 0;
    // Alzheimer atteinte du cortex entorhinal
    if ($('input[name="Questionnaire[braak_braak2]"]:checked', '#questionnaire-form').val() == "Oui")
        braak_score[1] = 2;
    else
        braak_score[1] = 0;
    // Alzheimer atteinte du cortex temporo-occipital
    if ($('input[name="Questionnaire[braak_braak3]"]:checked', '#questionnaire-form').val() == "Oui")
        braak_score[2] = 3;
    else
        braak_score[2] = 0;
    // Alzheimer atteinte du cortex temporal moyen / subiculum
    if ($('input[name="Questionnaire[braak_braak4]"]:checked', '#questionnaire-form').val() == "Oui")
        braak_score[3] = 4;
    else
        braak_score[3] = 0;
    // Alzheimer atteinte du cortex parastrié
    if ($('input[name="Questionnaire[braak_braak5]"]:checked', '#questionnaire-form').val() == "Oui")
        braak_score[4] = 5;
    else
        braak_score[4] = 0;
    // Alzheimer atteinte du cortex strié (couche V)
    if ($('input[name="Questionnaire[braak_braak6]"]:checked', '#questionnaire-form').val() == "Oui")
        braak_score[5] = 6;
    else
        braak_score[5] = 0;
    // Score = max(braak_score)
    document.getElementById("braak_braak_score").value = braak_score.reduce(function (p, v) {
        return (p > v ? p : v);
    })
});