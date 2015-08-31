//questionnaire deposit form
db.questionnaire.drop()
db.questionnaire.insert({
    "id": "parkinsonform",
    "name": "Parkinson form",
    "name_fr":"Formulaire Parkinson",
    "description": "Formulaire Parkinson",
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
                    "id": "parkinsonform",
                    "title": "Parkinson form",
                    "title_fr": "Formulaire Parkinson",
                    "questions": [{
                            "id": "anonymouseidentificationnumber",
                            "label": "N°anonymous identification",
                            "label_fr": "N°anonyme d’identification",
                            "type": "input",
                            "order": "1"
                        },
                        {
                            "id": "age",
                            "label": "Age",
                            "label_fr": "Age",
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
                        }
                    ]
             }]

})
