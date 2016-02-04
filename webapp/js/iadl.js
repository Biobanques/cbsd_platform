var iadl_score = [0, 0, 0, 0]; // [telephone, transport, médicaments, argent]

var tabs = {
    telephone: 'iadl_iadl1',
    transport: 'iadl_iadl2',
    medicaments: 'iadl_iadl3',
    argent: 'iadl_iadl4',
    score: 'iadl_score'
};

/**
 * Cas où on met à jour la fiche, pour éviter que le score IADL se réinitialise à 0
 * lorsque l'on modifie une valeur dans une liste déroulante.
 */
$(document).ready(function () {
    getValue();
    iadlScore(iadl_score);
});

/**
 * Le score est mis à jour automatiquement en fonction des choix sélectionnés dans les listes déroulantes.
 */
$(document).change(function () {
    getValue();
    iadlScore(iadl_score);
});

/**
 * Le score est mis à jour automatiquement en fonction des choix sélectionnés dans les listes déroulantes.
 */
function getAnswer(value, id) {
    var answer;
    var answers = {
        "Se sert normalement du téléphone": function () {
            iadl_score[0] = 1;
            answer = iadl_score
        },
        "Utilise les moyens de transports de façon indépendante ou conduit sa propre voiture": function () {
            iadl_score[1] = 1;
            answer = iadl_score
        },
        "Organise ses déplacements en taxi ou n’utilise aucun moyen de transport public": function () {
            iadl_score[1] = 1;
            answer = iadl_score
        },
        "Utilise les transports publics avec l’aide de quelqu’un": function () {
            iadl_score[1] = 1;
            answer = iadl_score
        },
        "Est responsable de la prise de ses médicaments (dose et rythmes corrects)": function () {
            iadl_score[2] = 1;
            answer = iadl_score
        },
        "Gère ses finances de façon autonome": function () {
            iadl_score[3] = 1;
            answer = iadl_score
        },
        "Se débrouille pour les achats quotidiens mais a besoin d’aide pour les opérations à la banque et les achats importants": function () {
            iadl_score[3] = 1;
            answer = iadl_score
        },
        "default": function () {
            switch (id) {
                case tabs.telephone:
                    iadl_score[0] = 0;
                    break;
                case tabs.transport:
                    iadl_score[1] = 0;
                    break;
                case tabs.medicaments:
                    iadl_score[2] = 0;
                    break;
                case tabs.argent:
                    iadl_score[3] = 0;
                    break;
            }
            answer = iadl_score
        }
    };
    (answers[value] || answers["default"])();
    return answer;
}

function getValue() {
    var telephone = document.getElementById(tabs.telephone);
    var transport = document.getElementById(tabs.transport);
    var medicaments = document.getElementById(tabs.medicaments);
    var argent = document.getElementById(tabs.argent);

    iadl_score = getAnswer(telephone.options[telephone.selectedIndex].value, tabs.telephone);
    iadl_score = getAnswer(transport.options[transport.selectedIndex].value, tabs.transport);
    iadl_score = getAnswer(medicaments.options[medicaments.selectedIndex].value, tabs.medicaments);
    iadl_score = getAnswer(argent.options[argent.selectedIndex].value, tabs.argent);

    return iadl_score;
}

/**
 * Calcule le score IADL en fonction des choix sélectionnés dans les listes déroulantes.
 */
function iadlScore(score) {
    document.getElementById(tabs.score).value = score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
}