//questionnaire demence form
db.questionnaire.drop()
db.questionnaire.insert({
    "id": "demenceform",
    "name": "Démence",
    "name_fr": "Démence",
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
                    "questions": [
                        {
                            "id": "born",
                            "label": "Date of birth",
                            "label_fr": "Date de naissance",
                            "type": "input",
                            "style": "float:right"
                        },
                        {
                            "id": "gender",
                            "label": "Gender",
                            "label_fr": "Genre",
                            "type": "radio",
                            "values": "M,F"
                        },
                        {
                            "id": "birth",
                            "label": "Place of birth",
                            "label_fr": "Lieu de naissance",
                            "type": "input",
                            "style": "float:right"
                        },
                        {
                            "id": "place",
                            "label": "Place of life",
                            "label_fr": "Lieu de vie",
                            "type": "radio",
                            "values": "Maison,EPHAD,Autre (à préciser)"
                        },
                        {
                            "id": "lastjob",
                            "label": "Last job",
                            "label_fr": "Dernier métier",
                            "type": "checkbox",
                            "values": "Cultivateur-agriculteur,Artisan,Commerçant et chef d'entreprise,Cadres et professions intellectuelles supérieures,Professions intermédiaires,Employé,Ouvrier,Femme au foyer,Autres,Sans"
                        },
                        {
                            "id": "stopactivity",
                            "label": "Date of stop activity",
                            "label_fr": "Date de l’arrêt de l’activité",
                            "type": "input"
                        },
                        {
                            "id": "stopactivity1",
                            "label": "",
                            "label_fr": "",
                            "type": "radio",
                            "values": "Maladie,Retraite,Retraite anticipé"
                        },
                        {
                            "id": "stopactivity2",
                            "label": "",
                            "label_fr": "",
                            "type": "radio",
                            "values": "Maladie,Autres,Non déterminé"
                        },
                        {
                            "id": "trouble_memoire",
                            "label": "",
                            "label_fr": "Trouble de la mémoire épisodique",
                            "type": "radio",
                            "values": "Oui,Non"
                        },
                        {
                            "id": "life",
                            "label": "",
                            "label_fr": "Entravant la vie quotidienne",
                            "type": "radio",
                            "values": "Oui,Non"
                        },
                        {
                            "id": "six_months",
                            "label": "",
                            "label_fr": "Depuis plus de 6 mois",
                            "type": "radio",
                            "values": "Oui,Non"
                        },
                        {
                            "id": "trouble_comportement",
                            "label": "",
                            "label_fr": "Troubles du comportement",
                            "type": "radio",
                            "values": "Oui,Non"
                        },
                        {
                            "id": "aphasie",
                            "label": "",
                            "label_fr": "Aphasie",
                            "type": "radio",
                            "values": "Oui,Non"
                        },
                        {
                            "id": "apraxie",
                            "label": "",
                            "label_fr": "Apraxie",
                            "type": "radio",
                            "values": "Oui,Non"
                        },
                        {
                            "id": "agnosie_visuelle",
                            "label": "",
                            "label_fr": "Agnosie visuelle",
                            "type": "radio",
                            "values": "Oui,Non"
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
                            "label": "SOINS PERSONNELS (lavabo baignoire ou douche)",
                            "label_fr": "SOINS PERSONNELS (lavabo baignoire ou douche)",
                            "type": "list",
                            "values": "Ne reçoit aucune aide (rentre et sort seul de la baignoire si celle-ci est le moyen habituel de toilette,Reçoit de l'aide pour laver certaines parties du corps (comme le dos ou une jambe),Reçoit de l'aide pour laver plus d'une partie du corps",
                            "values_fr": "Ne reçoit aucune aide (rentre et sort seul de la baignoire si celle-ci est le moyen habituel de toilette,Reçoit de l'aide pour laver certaines parties du corps (comme le dos ou une jambe),Reçoit de l'aide pour laver plus d'une partie du corps"
                        },
                        {
                            "id": "adl2",
                            "label": "HABILLAGE (prend les habits de l'armoire et des tiroirs c'est-à-dire sous-vêtements, vêtements de dessus et sait manipuler les fermetures incluant les bretelles)",
                            "label_fr": "HABILLAGE (prend les habits de l'armoire et des tiroirs c'est-à-dire sous-vêtements, vêtements de dessus et sait manipuler les fermetures incluant les bretelles)",
                            "type": "list",
                            "values": "Prend les vêtements et s'habille complètement sans aide,Prend les habits et s'habille sans aide sauf pour les chaussures,Reçoit de l'aide pour prendre les habits et/ou s'habiller ou reste partiellement ou totalement dévêtu",
                            "values_fr": "Prend les vêtements et s'habille complètement sans aide,Prend les habits et s'habille sans aide sauf pour les chaussures,Reçoit de l'aide pour prendre les habits et/ou s'habiller ou reste partiellement ou totalement dévêtu"
                        },
                        {
                            "id": "adl3",
                            "label": "ALLER AUX TOILETTES (va aux toilettes, se nettoie ensuite et arrange ses vêtements)",
                            "label_fr": "ALLER AUX TOILETTES (va aux toilettes, se nettoie ensuite et arrange ses vêtements)",
                            "type": "list",
                            "values": "Va aux toilettes Se nettoie et arrange ses vêtements sans aide (peut s'aider d'un support comme une canne un déambulateur une chaise roulante et peut utiliser un bassin ou une chaise percée), Reçoit de l'aide pour aller aux toilettes... ne va pas aux toilettes",
                            "values_fr": "Va aux toilettes Se nettoie et arrange ses vêtements sans aide (peut s'aider d'un support comme une canne un déambulateur une chaise roulante et peut utiliser un bassin ou une chaise percée),Reçoit de l'aide pour aller aux toilettes... ne va pas aux toilettes"
                        },
                        {
                            "id": "adl4",
                            "label": "DEPLACEMENTS",
                            "label_fr": "DEPLACEMENTS",
                            "type": "list",
                            "values": "Se couche et se lève du lit aussi bien qu'il s'assoit ou se lève d'une chaise sans aide (peut s'aider d'un support comme un déambulateur ou une canne),Se couche ou se lève avec aide ,Reste alité",
                            "values_fr": "Se couche et se lève du lit aussi bien qu'il s'assoit ou se lève d'une chaise sans aide (peut s'aider d'un support comme un déambulateur ou une canne),Se couche ou se lève avec aide ,Reste alité"
                        },
                        {
                            "id": "adl5",
                            "label": "CONTINENCE",
                            "label_fr": "CONTINENCE",
                            "type": "list",
                            "values": "Contrôle parfaitement seul son élimination,A quelques petits accidents ou est incotinent (urine ou selles)",
                            "values_fr": "Contrôle parfaitement seul son élimination,A quelques petits accidents ou est incotinent (urine ou selles)"
                        },
                        {
                            "id": "adl6",
                            "label": "ALIMENTATION",
                            "label_fr": "ALIMENTATION",
                            "type": "list",
                            "values": "Mange sans aide,Mange seul mais a besoin d'une aide pour couper la viande ou pour beurrer les tartines,Reçoit une aide pour manger ou et nourrit partiellement ou totalement à l’aide d'une sonde ou de solutés intraveineux",
                            "values_fr": "Mange sans aide,Mange seul mais a besoin d'une aide pour couper la viande ou pour beurrer les tartines,Reçoit une aide pour manger ou et nourrit partiellement ou totalement à l’aide d'une sonde ou de solutés intraveineux"
                        }
                    ]
                },
                {
                    "id": "iadl",
                    "title": "",
                    "title_fr": "IADL",
                    "parent_group": "demenceform",
                    "questions": [{
                            "id": "iadl1",
                            "label": "Aptitude à utiliser le téléphone",
                            "label_fr": "Aptitude à utiliser le téléphone",
                            "type": "list",
                            "values": "Se sert normalement du téléphone,Compose quelques numéros très connus,N’utilise pas du tout le téléphone spontanément,Incapable d’utiliser le téléphone",
                            "values_fr": "Se sert normalement du téléphone,Compose quelques numéros très connus,N’utilise pas du tout le téléphone spontanément,Incapable d’utiliser le téléphone"
                        },
                        {
                            "id": "iadl2",
                            "label": "Moyens de transport",
                            "label_fr": "Moyens de transport",
                            "type": "list",
                            "values": "Utilise les moyens de transports de façon indépendante ou conduit sa propre voiture,Organise ses déplacements en taxi ou n’utilise aucun moyen de transport public,Utilise les transports publics avec l’aide de quelqu’un,Déplacement limité en taxi ou en voiture avec l’aide de quelqu’un",
                            "values_fr": "Utilise les moyens de transports de façon indépendante ou conduit sa propre voiture,Organise ses déplacements en taxi ou n’utilise aucun moyen de transport public,Utilise les transports publics avec l’aide de quelqu’un,Déplacement limité en taxi ou en voiture avec l’aide de quelqu’un"
                        },
                        {
                            "id": "iadl3",
                            "label": "Responsabilité à l’égard de son traitement",
                            "label_fr": "Responsabilité à l’égard de son traitement",
                            "type": "list",
                            "values": "Est responsable de la prise de ses médicaments (dose et rythmes corrects),Est responsable de la prise de ses médicaments si les doses ont été préparées à l’avance,Est incapable de prendre seul ses médicaments même si ceux-ci ont été à l’avance",
                            "values_fr": "Est responsable de la prise de ses médicaments (dose et rythmes corrects),Est responsable de la prise de ses médicaments si les doses ont été préparées à l’avance,Est incapable de prendre seul ses médicaments même si ceux-ci ont été à l’avance"
                        },
                        {
                            "id": "iadl4",
                            "label": "Aptitude à manipuler l’argent",
                            "label_fr": "Aptitude à manipuler l’argent",
                            "type": "list",
                            "values": "Non applicable N’a jamais manipulé l’argent,Gère ses finances de façon autonome,Se débrouille pour les achats quotidiens  mais a besoin d’aide pour les opérations à la banque et les achats importants,Incapable de manipuler l’argent",
                            "values_fr": "Non applicable N’a jamais manipulé l’argent,Gère ses finances de façon autonome,Se débrouille pour les achats quotidiens  mais a besoin d’aide pour les opérations à la banque et les achats importants,Incapable de manipuler l’argent"
                        }
                    ]
                }
            ]

})
