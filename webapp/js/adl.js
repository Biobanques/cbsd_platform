var adl_score = [0, 0, 0, 0, 0, 0]; // [soins, habillage, toilettes, déplacements, continence, alimentation]

/**
 * Cas où on met à jour la fiche, pour éviter que le score ADL se réinitialise à 0
 * lorsque l'on modifie une valeur dans une liste déroulante.
 */
$('#adl_adl1').ready(function () {
    var soins = document.getElementById("adl_adl1");
    var valeurSoins = soins.options[soins.selectedIndex].value;

    switch (valeurSoins) {
        case "Ne reçoit aucune aide (rentre et sort seul de la baignoire si celle-ci est le moyen habituel de toilette)":
        case "Reçoit de l'aide pour laver certaines parties du corps (comme le dos ou une jambe)":
            adl_score[0] = 1;
            break;
        default:
            adl_score[0] = 0;
    }

    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});

$('#adl_adl2').ready(function () {
    var habillage = document.getElementById("adl_adl2");
    var valeurHabillage = habillage.options[habillage.selectedIndex].value;
    switch (valeurHabillage) {
        case "Prend les vêtements et s'habille complètement sans aide":
        case "Prend les habits et s'habille sans aide sauf pour les chaussures":
            adl_score[1] = 1;
            break;
        default:
            adl_score[1] = 0;
    }
    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
    ;
});

$('#adl_adl3').ready(function () {
    var toilettes = document.getElementById("adl_adl3");
    var valeurToilettes = toilettes.options[toilettes.selectedIndex].value;
    switch (valeurToilettes) {
        case "Va aux toilettes; se nettoie et arrange ses vêtements sans aide (peut s'aider d'un support comme une canne; un déambulateur; une chaise roulante et peut utiliser un bassin ou une chaise percée)":
            adl_score[2] = 1;
            break;
        default:
            adl_score[2] = 0;
    }
    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
    ;
});

$('#adl_adl4').ready(function () {
    var deplacements = document.getElementById("adl_adl4");
    var valeurDeplacements = deplacements.options[deplacements.selectedIndex].value;
    switch (valeurDeplacements) {
        case "Se couche et se lève du lit aussi bien qu'il s'assoit ou se lève d'une chaise sans aide (peut s'aider d'un support comme un déambulateur ou une canne)":
            adl_score[3] = 1;
            break;
        default:
            adl_score[3] = 0;
    }
    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});

$('#adl_adl5').ready(function () {
    var continence = document.getElementById("adl_adl5");
    var valeurContinence = continence.options[continence.selectedIndex].value;
    switch (valeurContinence) {
        case "Contrôle parfaitement seul son élimination":
            adl_score[4] = 1;
            break;
        default:
            adl_score[4] = 0;
    }
    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});

$('#adl_adl6').ready(function () {
    var alimentation = document.getElementById("adl_adl6");
    var valeurAlimentation = alimentation.options[alimentation.selectedIndex].value;
    switch (valeurAlimentation) {
        case "Mange sans aide":
            adl_score[5] = 1;
            break;
        default:
            adl_score[5] = 0;
    }
    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});

/**
 * Le score ADL est mis à jour automatiquement en fonction des choix sélectionnés dans les listes déroulantes.
 */
$('#adl_adl1').change(function () {
    var soins = document.getElementById("adl_adl1");
    var valeurSoins = soins.options[soins.selectedIndex].value;

    switch (valeurSoins) {
        case "Ne reçoit aucune aide (rentre et sort seul de la baignoire si celle-ci est le moyen habituel de toilette)":
        case "Reçoit de l'aide pour laver certaines parties du corps (comme le dos ou une jambe)":
            adl_score[0] = 1;
            break;
        default:
            adl_score[0] = 0;
    }

    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});

$('#adl_adl2').change(function () {
    var habillage = document.getElementById("adl_adl2");
    var valeurHabillage = habillage.options[habillage.selectedIndex].value;
    switch (valeurHabillage) {
        case "Prend les vêtements et s'habille complètement sans aide":
        case "Prend les habits et s'habille sans aide sauf pour les chaussures":
            adl_score[1] = 1;
            break;
        default:
            adl_score[1] = 0;
    }
    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
    ;
});

$('#adl_adl3').change(function () {
    var toilettes = document.getElementById("adl_adl3");
    var valeurToilettes = toilettes.options[toilettes.selectedIndex].value;
    switch (valeurToilettes) {
        case "Va aux toilettes; se nettoie et arrange ses vêtements sans aide (peut s'aider d'un support comme une canne; un déambulateur; une chaise roulante et peut utiliser un bassin ou une chaise percée)":
            adl_score[2] = 1;
            break;
        default:
            adl_score[2] = 0;
    }
    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
    ;
});

$('#adl_adl4').change(function () {
    var deplacements = document.getElementById("adl_adl4");
    var valeurDeplacements = deplacements.options[deplacements.selectedIndex].value;
    switch (valeurDeplacements) {
        case "Se couche et se lève du lit aussi bien qu'il s'assoit ou se lève d'une chaise sans aide (peut s'aider d'un support comme un déambulateur ou une canne)":
            adl_score[3] = 1;
            break;
        default:
            adl_score[3] = 0;
    }
    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});

$('#adl_adl5').change(function () {
    var continence = document.getElementById("adl_adl5");
    var valeurContinence = continence.options[continence.selectedIndex].value;
    switch (valeurContinence) {
        case "Contrôle parfaitement seul son élimination":
            adl_score[4] = 1;
            break;
        default:
            adl_score[4] = 0;
    }
    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});

$('#adl_adl6').change(function () {
    var alimentation = document.getElementById("adl_adl6");
    var valeurAlimentation = alimentation.options[alimentation.selectedIndex].value;
    switch (valeurAlimentation) {
        case "Mange sans aide":
            adl_score[5] = 1;
            break;
        default:
            adl_score[5] = 0;
    }
    document.getElementById("adl_score").value = adl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});