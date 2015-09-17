//questionnaire demence form
db.questionnaire.drop()
db.questionnaire.insert({
    "id": "demenceform",
    "name": "Demence form",
    "name_fr": "Formulaire Démence",
    "description": "Formulaire Démence",
    "last_modified": new Date(),
    "message_start": "Welcome to the deposit form for DNA/RNA, cells, fluids and tissues<br />(*)1 form per patient and per sampling date<br /> (*) 1 fiche par patient et par date de prélèvement",
    "message_end": "Thanks for your job",
    "references": "",
    "contributors": "<b>DNA-RNA /ADN-ARN</b><br /> Marie-Alexandra Alyanakian, APHP Necker; Jacques Bonnet, Institut Bergonié ;Marthe Colotte, Imagène; Sylvie Forlani, Banque d’ADN et de cellules Paris ; Jean-Marc Gerard, Qiagen ; Olivier Leroy, Trinean ; Philippe Lorimier, CRB Cancérologie – CHU Grenoble; Claire Mulot, St Peres-Epigeneter ; Sophie Tuffet, Imagène; Sabrina Turbant-Leclere, Banque de Cerveaux Hôpital Pitié Salpêtrière – GIE Neuro-CEB-Paris.<br><br>\n\
<b>Cell culture/ Culture cellulaire</b><br>Maud Chapart Leclert, Association Institut de Myologie ; Nathalie Denis, Eurobio ; Isabelle Grosjean, Inserm ; Thierry Larmonier, Genethon ; Nadia Piga, Bioméreux; Céline Schaeffer, CRB Ferdinand Cabanne – Dijon.<br><br>\n\
<b>Fluids/ Fluide</b><br>Grégory Huberty, Biobanque de Picardie ; Philippe Manivet, CRB GHV Lariboisière /APHP/Inserm942 ; Jane-Lise Samuel ; InsermU942.<br><br>\n\
<b>Tissue/Tissu</b><br>Christine Chaumeil, CRB CHNNO des 15/20 ; Charles Duyckaerts, Banques de Cerveaux  Hôpital Pitié Salpêtrière – GIE-Neuro-CEB- Paris ; Anne Gomez Brouchet, Biobanque CHU de Toulouse ; Sophie Prevot, Réseau CRB Paris Sud.<br><br>\n\
<b>Microbiology/Microbiologie</b><br>ChristineChaumeil,CRBduCHNOdesQuinze-VingtParis; équipe de Chantal BizetCRB Institut Pasteur–CRBIPParis,Anne Favel, I ; Villena, CRB Toxoplasma CHU Reims.",
    "questions_group":
            [{
                    "id": "demenceform",
                    "title": "Demence form",
                    "title_fr": "Formulaire Démence",
                    "questions": [{
                            "id": "anonymouseidentificationnumber",
                            "label": "N°anonymous identification",
                            "label_fr": "N°anonyme d’identification",
                            "type": "input",
                            "order": "1"
                        },
                        {
                            "id": "born",
                            "label": "Date of birth",
                            "label_fr": "Date de naissance",
                            "type": "input",
                            "order": "2",
                            "style": "float:right"
                        },
                        {
                            "id": "gender",
                            "label": "Gender",
                            "label_fr": "Genre",
                            "type": "radio",
                            "values": "M,F",
                            "order": "3"
                        },
                        {
                            "id": "birth",
                            "label": "Place of birth",
                            "label_fr": "Lieu de naissance",
                            "type": "input",
                            "order": "4",
                            "style": "float:right"
                        },
                        {
                            "id": "place",
                            "label": "Place of life",
                            "label_fr": "Lieu de vie",
                            "type": "radio",
                            "values": "Maison,EPHAD,Autre (à préciser)",
                            "order": "5"
                        },
                        {
                            "id": "lastjob",
                            "label": "Last job",
                            "label_fr": "Dernier métier",
                            "type": "checkbox",
                            "values": "Cultivateur-agriculteur,Artisan,Commerçant et chef d'entreprise,Cadres et professions intellectuelles supérieures,Professions intermédiaires,Employé,Ouvrier,Femme au foyer,Autres,Sans",
                            "order": "6"
                        },
                        {
                            "id": "stopactivity",
                            "label": "Date of stop activity",
                            "label_fr": "Date de l’arrêt de l’activité",
                            "type": "input",
                            "order": "7"
                        },
                        {
                            "id": "stopactivity1",
                            "label": "",
                            "label_fr": "",
                            "type": "radio",
                            "values": "Maladie,Retraite,Retraite anticipé",
                            "order": "8"
                        },
                        {
                            "id": "stopactivity2",
                            "label": "",
                            "label_fr": "",
                            "type": "radio",
                            "values": "Maladie,Autres,Non déterminé",
                            "order": "9"
                        },
                        {
                            "id": "trouble_memoire",
                            "label": "",
                            "label_fr": "Trouble de la mémoire épisodique",
                            "type": "radio",
                            "values": "Oui,Non",
                            "order": "10"
                        },
                        {
                            "id": "life",
                            "label": "",
                            "label_fr": "Entravant la vie quotidienne",
                            "type": "radio",
                            "values": "Oui,Non",
                            "order": "11"
                        },
                        {
                            "id": "six_months",
                            "label": "",
                            "label_fr": "Depuis plus de 6 mois",
                            "type": "radio",
                            "values": "Oui,Non",
                            "order": "12"
                        },
                        {
                            "id": "trouble_comportement",
                            "label": "",
                            "label_fr": "Troubles du comportement",
                            "type": "radio",
                            "values": "Oui,Non",
                            "order": "13"
                        },
                        {
                            "id": "aphasie",
                            "label": "",
                            "label_fr": "Aphasie",
                            "type": "radio",
                            "values": "Oui,Non",
                            "order": "14"
                        },
                        {
                            "id": "apraxie",
                            "label": "",
                            "label_fr": "Apraxie",
                            "type": "radio",
                            "values": "Oui,Non",
                            "order": "15"
                        },
                        {
                            "id": "agnosie_visuelle",
                            "label": "",
                            "label_fr": "Agnosie visuelle",
                            "type": "radio",
                            "values": "Oui,Non",
                            "order": "16"
                        }
                    ]
                },
                {
                    "id": "adl",
                    "title": "",
                    "title_fr": "ACTIVITES DE LA VIE QUOTIDIENNE (ADL)",
                    "parent_group": "demenceform",
                    "questions": [{
                            "id": "adl1",
                            "label": "",
                            "label_fr": "Ne reçoit aucune aide (rentre et sort seul de la baignoire si celle-ci est le moyen habituel de toilette.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "17"
                        },
                        {
                            "id": "adl2",
                            "label": "",
                            "label_fr": "Reçoit de l'aide pour laver certaines parties du corps (comme le dos ou une jambe).",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "18"
                        },
                        {
                            "id": "adl3",
                            "label": "",
                            "label_fr": "Reçoit de 1'aide pour laver plus d'une partie du corps.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "19"
                        },
                        {
                            "id": "adl4",
                            "label": "",
                            "label_fr": "Prend les vêtements et s'habille complètement sans aide.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "20"
                        },
                        {
                            "id": "adl5",
                            "label": "",
                            "label_fr": "Prend les habits et s'habille sans aide sauf pour les chaussures.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "21"
                        },
                        {
                            "id": "adl6",
                            "label": "",
                            "label_fr": "Reçoit de l'aide pour prendre les habits et/ou s'habiller ou reste partiellement ou totalement dévêtu.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "22"
                        },
                        {
                            "id": "adl7",
                            "label": "",
                            "label_fr": "Va aux toilettes, se nettoie et arrange ses vêtements sans aide (peut s'aider d'un support comme une canne, un déambulateur, une chaise roulante et peut utiliser un bassin ou une chaise percée).",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "23"
                        },
                        {
                            "id": "adl8",
                            "label": "",
                            "label_fr": "Reçoit de l'aide pour aller aux toilettes... ne va pas aux toilettes.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "24"
                        },
                        {
                            "id": "adl9",
                            "label": "",
                            "label_fr": "Se couche et se lève du lit aussi bien qu'il s'assoit ou se lève d'une chaise, sans aide (peut s'aider d'un support comme un déambulateur ou une canne).",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "25"
                        },
                        {
                            "id": "adl10",
                            "label": "",
                            "label_fr": "Se couche ou se lève avec aide.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "26"
                        },
                        {
                            "id": "adl11",
                            "label": "",
                            "label_fr": "Reste alité.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "27"
                        },
                        {
                            "id": "adl12",
                            "label": "",
                            "label_fr": "Contrôle parfaitement seul son élimination.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "28"
                        },
                        {
                            "id": "adl13",
                            "label": "",
                            "label_fr": "A quelques petits accidents ou est incotinent (urine ou selles).",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "29"
                        },
                        {
                            "id": "adl14",
                            "label": "",
                            "label_fr": "Mange sans aide.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "30"
                        },
                        {
                            "id": "adl15",
                            "label": "",
                            "label_fr": "Mange seul mais a besoin d'une aide pour couper la viande ou pour beurrer les tartines.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "31"
                        },
                        {
                            "id": "adl16",
                            "label": "",
                            "label_fr": "Reçoit une aide pour manger ou et nourrit partiellement ou totalement à l’aide d'une sonde ou de solutés intraveineux.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "32"
                        },
  
                    ]
                },
                {
                    "id": "iadl",
                    "title": "",
                    "title_fr": "IADL",
                    "parent_group": "demenceform",
                    "questions": [{
                            "id": "iadl1",
                            "label": "",
                            "label_fr": "Se sert normalement du téléphone.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "33"
                        },
                        {
                            "id": "iadl2",
                            "label": "",
                            "label_fr": "Compose quelques numéros très connus.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "34"
                        },
                        {
                            "id": "iadl3",
                            "label": "",
                            "label_fr": "N’utilise pas du tout le téléphone spontanément.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "35"
                        },
                        {
                            "id": "iadl4",
                            "label": "",
                            "label_fr": "Incapable d’utiliser le téléphone.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "36"
                        },
                        {
                            "id": "iadl5",
                            "label": "",
                            "label_fr": "Utilise les moyens de transports de façon indépendante ou conduit sa propre voiture.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "37"
                        },
                        {
                            "id": "iadl6",
                            "label": "",
                            "label_fr": "Organise ses déplacements en taxi ou n’utilise aucun moyen de transport public.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "38"
                        },
                        {
                            "id": "iadl7",
                            "label": "",
                            "label_fr": "Utilise les transports publics avec l’aide de quelqu’un.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "39"
                        },
                        {
                            "id": "iadl8",
                            "label": "",
                            "label_fr": "Déplacement limité en taxi ou en voiture avec l’aide de quelqu’un.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "40"
                        },
                        {
                            "id": "iadl9",
                            "label": "",
                            "label_fr": "Est responsable de la prise de ses médicaments (dose et rythmes corrects).",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "41"
                        },
                        {
                            "id": "iadl10",
                            "label": "",
                            "label_fr": "Est responsable de la prise de ses médicaments si les doses ont été préparées à l’avance.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "42"
                        },
                        {
                            "id": "iadl11",
                            "label": "",
                            "label_fr": "Est incapable de prendre seul ses médicaments même si ceux-ci ont été à l’avance.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "43"
                        },
                        {
                            "id": "iadl12",
                            "label": "",
                            "label_fr": "Non applicable, n’a jamais manipulé l’argent.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "44"
                        },
                        {
                            "id": "iadl13",
                            "label": "",
                            "label_fr": "Gère ses finances de façon autonome.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "45"
                        },
                        {
                            "id": "iadl14",
                            "label": "",
                            "label_fr": "Se débrouille pour les achats quotidiens, mais a besoin d’aide pour les opérations à la banque et les achats importants.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "46"
                        },
                        {
                            "id": "iadl15",
                            "label": "",
                            "label_fr": "Incapable de manipuler l’argent.",
                            "type": "list",
                            "values": "1,2,3",
                            "values_fr": "1,2,3",
                            "order": "47"
                        },
                    ]
                }
            ]

})
