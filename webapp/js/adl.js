var adl_score = [0, 0, 0, 0, 0, 0]; // [soins, habillage, toilettes, déplacements, continence, alimentation]

var tabs_adl = {
    soins: 'adl_adl1',
    habillage: 'adl_adl2',
    toilettes: 'adl_adl3',
    deplacements: 'adl_adl4',
    continence: 'adl_adl5',
    alimentation: 'adl_adl6',
    score: 'adl_adl_score'
};

/**
 * Le score est mis à jour automatiquement en fonction des choix sélectionnés dans les listes déroulantes.
 */
$(document).change(function () {
    getValueAdl();
    adlScore(adl_score);
});

function getValueAdl() {
    var soins = document.getElementById(tabs_adl.soins);
    var habillage = document.getElementById(tabs_adl.habillage);
    var toilettes = document.getElementById(tabs_adl.toilettes);
    var deplacements = document.getElementById(tabs_adl.deplacements);
    var continence = document.getElementById(tabs_adl.continence);
    var alimentation = document.getElementById(tabs_adl.alimentation);

    adl_score = getAnswer(soins.options[soins.selectedIndex].value, tabs_adl.soins);
    adl_score = getAnswer(habillage.options[habillage.selectedIndex].value, tabs_adl.habillage);
    adl_score = getAnswer(toilettes.options[toilettes.selectedIndex].value, tabs_adl.toilettes);
    adl_score = getAnswer(deplacements.options[deplacements.selectedIndex].value, tabs_adl.deplacements);
    adl_score = getAnswer(continence.options[continence.selectedIndex].value, tabs_adl.continence);
    adl_score = getAnswer(alimentation.options[alimentation.selectedIndex].value, tabs_adl.alimentation);

    return adl_score;
}

function getAnswer(value, id) {
    var answer;
    var answers = {
        "Ne reçoit aucune aide (rentre et sort seul de la baignoire si celle-ci est le moyen habituel de toilette)": function () {
            adl_score[0] = 1;
            answer = adl_score
        },
        "Reçoit de l'aide pour laver certaines parties du corps (comme le dos ou une jambe)": function () {
            adl_score[0] = 1;
            answer = adl_score
        },
        "Prend les vêtements et s'habille complètement sans aide": function () {
            adl_score[1] = 1;
            answer = adl_score
        },
        "Prend les habits et s'habille sans aide sauf pour les chaussures": function () {
            adl_score[1] = 1;
            answer = adl_score
        },
        "Va aux toilettes; se nettoie et arrange ses vêtements sans aide (peut s'aider d'un support comme une canne; un déambulateur; une chaise roulante et peut utiliser un bassin ou une chaise percée)": function () {
            adl_score[2] = 1;
            answer = adl_score
        },
        "Se couche et se lève du lit aussi bien qu'il s'assoit ou se lève d'une chaise sans aide (peut s'aider d'un support comme un déambulateur ou une canne)": function () {
            adl_score[3] = 1;
            answer = adl_score
        },
        "Contrôle parfaitement seul son élimination": function () {
            adl_score[4] = 1;
            answer = adl_score
        },
        "Mange sans aide": function () {
            adl_score[5] = 1;
            answer = adl_score
        },
        "default": function () {
            switch (id) {
                case tabs_adl.soins:
                    adl_score[0] = 0;
                    break;
                case tabs_adl.habillage:
                    adl_score[1] = 0;
                    break;
                case tabs_adl.toilettes:
                    adl_score[2] = 0;
                    break;
                case tabs_adl.deplacements:
                    adl_score[3] = 0;
                    break;
                case tabs_adl.continence:
                    adl_score[3] = 0;
                    break;
                case tabs_adl.alimentation:
                    adl_score[3] = 0;
                    break;
            }
            answer = adl_score
        }
    };
    (answers[value] || answers["default"])();
    return answer;
}

/**
 * Calcule le score ADL en fonction des choix sélectionnés dans les listes déroulantes.
 */
function adlScore(score) {
    document.getElementById(tabs_adl.score).value = score.reduce(function (valeurPrecedente, valeurCourante) {
        return valeurPrecedente + valeurCourante;
    });
}