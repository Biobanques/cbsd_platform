var iadl_score = [0, 0, 0, 0]; // [telephone, transport, médicaments, argent]

var tabs_iadl = {
    telephone: 'iadl_iadl1',
    transport: 'iadl_iadl2',
    medicaments: 'iadl_iadl3',
    argent: 'iadl_iadl4',
    score: 'iadl_iadl_score'
};

/**
 * Le score est mis à jour automatiquement en fonction des choix sélectionnés dans les listes déroulantes.
 */
$(document).change(function () {
    getValueIadl();
    iadlScore(iadl_score);
});

function getValueIadl() {
    var telephone = document.getElementById(tabs_iadl.telephone);
    var transport = document.getElementById(tabs_iadl.transport);
    var medicaments = document.getElementById(tabs_iadl.medicaments);
    var argent = document.getElementById(tabs_iadl.argent);

    iadl_score = getAnswerIadl(telephone.options[telephone.selectedIndex].value, tabs_iadl.telephone);
    iadl_score = getAnswerIadl(transport.options[transport.selectedIndex].value, tabs_iadl.transport);
    iadl_score = getAnswerIadl(medicaments.options[medicaments.selectedIndex].value, tabs_iadl.medicaments);
    iadl_score = getAnswerIadl(argent.options[argent.selectedIndex].value, tabs_iadl.argent);

    return iadl_score;
}

function getAnswerIadl(value, id) {
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
                case tabs_iadl.telephone:
                    iadl_score[0] = 0;
                    break;
                case tabs_iadl.transport:
                    iadl_score[1] = 0;
                    break;
                case tabs_iadl.medicaments:
                    iadl_score[2] = 0;
                    break;
                case tabs_iadl.argent:
                    iadl_score[3] = 0;
                    break;
            }
            answer = iadl_score
        }
    };
    (answers[value] || answers["default"])();
    return answer;
}

/**
 * Calcule le score IADL en fonction des choix sélectionnés dans les listes déroulantes.
 */
function iadlScore(score) {
    document.getElementById(tabs_iadl.score).value = score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
}