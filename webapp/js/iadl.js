var iadl_score = [0, 0, 0, 0]; // [telephone, transport, médicaments, argent]

/**
 * Cas où on met à jour la fiche, pour éviter que le score IADL se réinitialise à 0
 * lorsque l'on modifie une valeur dans une liste déroulante.
 */
$('#iadl_iadl1').ready(function () {
    var telephone = document.getElementById("iadl_iadl1");
    var valeurTelephone = telephone.options[telephone.selectedIndex].value;

    switch (valeurTelephone) {
        case "Se sert normalement du téléphone":
            iadl_score[0] = 1;
            break;
        default:
            iadl_score[0] = 0;
    }

    document.getElementById("iadl_score").value = iadl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});

$('#iadl_iadl2').ready(function () {
    var transport = document.getElementById("iadl_iadl2");
    var valeurTransport = transport.options[transport.selectedIndex].value;
    switch (valeurTransport) {
        case "Utilise les moyens de transports de façon indépendante ou conduit sa propre voiture":
        case "Organise ses déplacements en taxi ou n’utilise aucun moyen de transport public":
        case "Utilise les transports publics avec l’aide de quelqu’un":
            iadl_score[1] = 1;
            break;
        default:
            iadl_score[1] = 0;
    }
    document.getElementById("iadl_score").value = iadl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
    ;
});

$('#iadl_iadl3').ready(function () {
    var medicaments = document.getElementById("iadl_iadl3");
    var valeurMedicaments = medicaments.options[medicaments.selectedIndex].value;
    switch (valeurMedicaments) {
        case "Est responsable de la prise de ses médicaments (dose et rythmes corrects)":
            iadl_score[2] = 1;
            break;
        default:
            iadl_score[2] = 0;
    }
    document.getElementById("iadl_score").value = iadl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
    ;
});

$('#iadl_iadl4').ready(function () {
    var argent = document.getElementById("iadl_iadl4");
    var valeurArgent = argent.options[argent.selectedIndex].value;
    switch (valeurArgent) {
        case "Gère ses finances de façon autonome":
        case "Se débrouille pour les achats quotidiens mais a besoin d’aide pour les opérations à la banque et les achats importants":
            iadl_score[3] = 1;
            break;
        default:
            iadl_score[3] = 0;
    }
    document.getElementById("iadl_score").value = iadl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});

/**
 * Le score est mis à jour automatiquement en fonction des choix sélectionnés dans les listes déroulantes.
 */
$('#iadl_iadl1').change(function () {
    var telephone = document.getElementById("iadl_iadl1");
    var valeurTelephone = telephone.options[telephone.selectedIndex].value;

    switch (valeurTelephone) {
        case "Se sert normalement du téléphone":
            iadl_score[0] = 1;
            break;
        default:
            iadl_score[0] = 0;
    }

    document.getElementById("iadl_score").value = iadl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});

$('#iadl_iadl2').change(function () {
    var transport = document.getElementById("iadl_iadl2");
    var valeurTransport = transport.options[transport.selectedIndex].value;
    switch (valeurTransport) {
        case "Utilise les moyens de transports de façon indépendante ou conduit sa propre voiture":
        case "Organise ses déplacements en taxi ou n’utilise aucun moyen de transport public":
        case "Utilise les transports publics avec l’aide de quelqu’un":
            iadl_score[1] = 1;
            break;
        default:
            iadl_score[1] = 0;
    }
    document.getElementById("iadl_score").value = iadl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
    ;
});

$('#iadl_iadl3').change(function () {
    var medicaments = document.getElementById("iadl_iadl3");
    var valeurMedicaments = medicaments.options[medicaments.selectedIndex].value;
    switch (valeurMedicaments) {
        case "Est responsable de la prise de ses médicaments (dose et rythmes corrects)":
            iadl_score[2] = 1;
            break;
        default:
            iadl_score[2] = 0;
    }
    document.getElementById("iadl_score").value = iadl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
    ;
});

$('#iadl_iadl4').change(function () {
    var argent = document.getElementById("iadl_iadl4");
    var valeurArgent = argent.options[argent.selectedIndex].value;
    switch (valeurArgent) {
        case "Gère ses finances de façon autonome":
        case "Se débrouille pour les achats quotidiens mais a besoin d’aide pour les opérations à la banque et les achats importants":
            iadl_score[3] = 1;
            break;
        default:
            iadl_score[3] = 0;
    }
    document.getElementById("iadl_score").value = iadl_score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
});