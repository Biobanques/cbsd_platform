//questionnaire alzheimer form
db.questionnaire.insert({
    "id": "alzheimerform",
    "name": "Alzheimer",
    "name_fr": "neuropathologique Alzheimer",
    "description": "neuropathologique Alzheimer",
    "last_modified": new Date(),
    "message_start": "",
    "message_end": "Thanks for your job",
    "references": "",
    "contributors": "",
    "questions_group":
            [{
                    "id" : "alzheimerform",
                    "title" : "",
                    "title_fr" : "Renseignements individuels",
                    "questions" : [ 
                            {
                                "id" : "patientaddress",
                                "label" : "Adresse du patient",
                                "label_fr" : "Adresse du patient",
                                "type" : "input"
                            },
                            {
                                "id" : "examdate",
                                "label" : "Date de l'examen",
                                "label_fr" : "Date de l'examen",
                                "type" : "input",
                                "style" : "float:right"
                            },
                            {
                                "id" : "doctorname",
                                "label" : "Nom du médecin",
                                "label_fr" : "Nom du médecin",
                                "type" : "input"
                            }
                    ]
                },
                {
                    "id" : "alzhparkform",
                    "title" : "",
                    "title_fr" : "Formulaire neuropathologique Alzheimer / Parkinson",
                    "parent_group": "alzheimerform",
                    "questions": [
                            {
                                "id" : "transentorhinal",
                                "label" : "Stade de Braak Alzheimer atteinte du cortex trans-entorhinal",
                                "label_fr" : "Stade de Braak Alzheimer atteinte du cortex trans-entorhinal",
                                "type" : "radio",
                                "values" : "Oui,Non"
                            },
                            {
                                "id" : "entorhinal",
                                "label" : "Stade de Braak Alzheimer atteinte du cortex entorhinal",
                                "label_fr" : "Stade de Braak Alzheimer atteinte du cortex entorhinal",
                                "type" : "radio",
                                "values" : "Oui,Non"
                            },
                            {
                                "id" : "temporooccipital",
                                "label" : "Stade de Braak Alzheimer atteinte du cortex temporo-occipital",
                                "label_fr" : "Stade de Braak Alzheimer atteinte du cortex temporo-occipital",
                                "type" : "radio",
                                "values" : "Oui,Non"
                            },
                            {
                                "id" : "temporalmoyen",
                                "label" : "Stade de Braak Alzheimer atteinte du cortex temporal moyen / subiculum",
                                "label_fr" : "Stade de Braak Alzheimer atteinte du cortex temporal moyen / subiculum",
                                "type" : "radio",
                                "values" : "Oui,Non"
                            },
                            {
                                "id" : "parastrié",
                                "label" : "Stade de Braak Alzheimer atteinte du cortex parastrié",
                                "label_fr" : "Stade de Braak Alzheimer atteinte du cortex parastrié",
                                "type" : "radio",
                                "values" : "Oui,Non"
                            },
                            {
                                "id" : "strie",
                                "label" : "Stade de Braak Alzheimer atteinte du cortex strié (couche V)",
                                "label_fr" : "Stade de Braak Alzheimer atteinte du cortex strié (couche V)",
                                "type" : "radio",
                                "values" : "Oui,Non"
                            }
                    ]
                },
                {
                    "id" : "other",
                    "title" : "",
                    "title_fr" : "Autres",
                    "parent_group": "alzheimerform",
                    "questions": [
                            {
                                "id" : "grains",
                                "label" : "Présence de grains",
                                "label_fr" : "Présence de grains",
                                "type" : "list",
                                "values" : "Dans l’amygdale,Dans l’hippocampe"
                            },
                            {
                                "id" : "angiopathie",
                                "label" : "Présence d’une angiopathie amyloïde",
                                "label_fr" : "Présence d’une angiopathie amyloïde",
                                "type" : "list",
                                "values" : "Oui (Atteinte capillaire),Oui (Sans atteinte capillaire), Non"
                            },
                            {
                                "id" : "lewy",
                                "label" : "Présence de corps de Lewy",
                                "label_fr" : "Présence de corps de Lewy",
                                "type" : "list",
                                "values" : "Uniquement dans l’amygdale,Uniquement dans le tronc cérébral,Dans le tronc cérébral, le noyau basal de Meynert et le cortex limbique,Dans le néocortex"
                            }
                    ]
                }
            ]

})
