//questionnaire demence form
db.questionnaire.insert({
    "id": "parkinsonform",
    "name": "Parkinson form",
    "name_fr": "Formulaire Parkinson",
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
            "id" : "parkinsonform",
            "title" : "",
            "title_fr" : "I - RENSEIGNEMENTS INDIVIDUELS",
            "questions" : [ 
                {
                    "id" : "1a",
                    "label" : "Form number",
                    "label_fr" : "N° de fiche",
                    "type" : "input"
                }, 
                {
                    "id" : "1b",
                    "label" : "Name of the doctor",
                    "label_fr" : "Nom du médecin",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "1c",
                    "label" : "Propositus",
                    "label_fr" : "Propositus",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "1d",
                    "label" : "Reiew date",
                    "label_fr" : "Date examen",
                    "type" : "input",
                    "style" : "float:right"
                }
            ]
        }, 
        {
            "id" : "diagnostic",
            "title" : "",
            "title_fr" : "II - ELEMENTS DE DIAGNOSTIC",
            "questions" : [  
                {
                    "id" : "3a1",
                    "label" : "Ophtalmoplégie",
                    "label_fr" : "Ophtalmoplégie",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3a2",
                    "label" : "Syndrome pyramidal franc",
                    "label_fr" : "Syndrome pyramidal franc",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3a3",
                    "label" : "Syndrome cérébelleux",
                    "label_fr" : "Syndrome cérébelleux",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3a4",
                    "label" : "Apraxie",
                    "label_fr" : "Apraxie",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3a5",
                    "label" : "Instabilité posturale sévère et précoce",
                    "label_fr" : "Instabilité posturale sévère et précoce",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3a6",
                    "label" : "Incontinence précoce (<1 an)",
                    "label_fr" : "Incontinence précoce (<1 an)",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3a7",
                    "label" : "MMS <24/30 (<2ans)",
                    "label_fr" : "MMS <24/30 (<2ans)",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3b1",
                    "label" : "Prise de neuroleptiques datant de - de 6 mois",
                    "label_fr" : "Prise de neuroleptiques datant de - de 6 mois",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3b1a",
                    "label" : "Date",
                    "label_fr" : "Date",
                    "type" : "input"
                }, 
                {
                    "id" : "3b1b",
                    "label" : "Durée ",
                    "label_fr" : "Durée ",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "3b1c",
                    "label" : "Type ",
                    "label_fr" : "Type ",
                    "type" : "input"
                }, 
                {
                    "id" : "3b1d",
                    "label" : "Dose ",
                    "label_fr" : "Dose ",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "3b2",
                    "label" : "AVC",
                    "label_fr" : "AVC",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3b3",
                    "label" : "Encéphalite",
                    "label_fr" : "Encéphalite",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3b4",
                    "label" : "Intoxication (au CO, Mn)",
                    "label_fr" : "Intoxication (au CO, Mn)",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3b5",
                    "label" : "Autre",
                    "label_fr" : "Autre",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "3b51",
                    "label" : "préciser",
                    "label_fr" : "préciser",
                    "type" : "input",
                    "style" : "float:right"
                },
                {
                    "id" : "3c1",
                    "label" : "Scanner et / ou IRMcérébrale",
                    "label_fr" : "Scanner et / ou IRMcérébrale",
                    "type" : "radio",
                    "values" : "Non fait,Normal,Anormal"
                }, 
                {
                    "id" : "3c1a",
                    "label" : "Si anormal, préciser les lésions",
                    "label_fr" : "Si anormal, préciser les lésions",
                    "type" : "input"
                }, 
                {
                    "id" : "3c2a",
                    "label" : "Cuprémie",
                    "label_fr" : "Cuprémie",
                    "type" : "radio",
                    "values" : "Non fait,Normale,Anormale"
                }, 
                {
                    "id" : "3c2b",
                    "label" : "Cuprurie",
                    "label_fr" : "Cuprurie",
                    "type" : "radio",
                    "values" : "Non fait,Normale,Anormale"
                }, 
                {
                    "id" : "3c2c",
                    "label" : "Céruléoplasmine",
                    "label_fr" : "Céruléoplasmine",
                    "type" : "radio",
                    "values" : "Non fait,Normale,Diminuée"
                }, 
                {
                    "id" : "3c2d",
                    "label" : "Recherche d’anneau de Kayser Fleisher",
                    "label_fr" : "Recherche d’anneau de Kayser Fleisher",
                    "type" : "radio",
                    "values" : "Non fait,Absente,Présente"
                }, 
                {
                    "id" : "3c3",
                    "label" : "Recherche d’acanthocytes",
                    "label_fr" : "Recherche d’acanthocytes",
                    "type" : "radio",
                    "values" : "Non fait,Négative,Positive"
                }
            ],
            "parent_group" : "parkinsonform"
        },
        {
            "id" : "information",
            "title" : "",
            "title_fr" : "IV - RENSEIGNEMENTS COMPLEMENTAIRES ",
            "questions" : [
                {
                    "id" : "aaa",
                    "label" : "Recherche d’acanthocytes",
                    "label_fr" : "Recherche d’acanthocytes",
                    "type" : "radio",
                    "values" : "Non fait,Négative,Positive"
                }, 
                {
                    "id" : "4a",
                    "label" : "Age de début",
                    "label_fr" : "Age de début",
                    "type" : "input"
                }, 
                {
                    "id" : "4b",
                    "label" : "Durée d'évolution (ans)",
                    "label_fr" : "Durée d'évolution (ans)",
                    "type" : "input"
                }, 
                {
                    "id" : "4c",
                    "label" : "Aggravation",
                    "label_fr" : "Aggravation",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4c1",
                    "label" : "aggravation lente",
                    "label_fr" : "aggravation lente",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4c2",
                    "label" : "aggravation rapide",
                    "label_fr" : "aggravation rapide",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4d1",
                    "label" : "Micrographie",
                    "label_fr" : "Micrographie",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4d2",
                    "label" : "Akinésie",
                    "label_fr" : "Akinésie",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4d3a",
                    "label" : "en attitude",
                    "label_fr" : "en attitude",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4d3b",
                    "label" : "à l’épreuve doigt-nez",
                    "label_fr" : "à l’épreuve doigt-nez",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4d3c",
                    "label" : "de repos",
                    "label_fr" : "de repos",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4d3d",
                    "label" : "préciser",
                    "label_fr" : "préciser",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "4d4",
                    "label" : "Siège",
                    "label_fr" : "Siège",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4d5",
                    "label" : "Dystonie",
                    "label_fr" : "Dystonie",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4d5a",
                    "label" : "Préciser le siège",
                    "label_fr" : "Préciser le siège",
                    "type" : "input"
                }, 
                {
                    "id" : "4d5b",
                    "label" : "Préciser l’horaire de survenue",
                    "label_fr" : "Préciser l’horaire de survenue",
                    "type" : "input"
                }, 
                {
                    "id" : "4d6",
                    "label" : "Raideur ou crampes",
                    "label_fr" : "Raideur ou crampes",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4d6a",
                    "label" : "préciser le siège",
                    "label_fr" : "préciser le siège",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "4d7",
                    "label" : "Dépression inaugurale",
                    "label_fr" : "Dépression inaugurale",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4d8",
                    "label" : "Autre",
                    "label_fr" : "Autre",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4d8a",
                    "label" : "préciser",
                    "label_fr" : "préciser",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "4e1",
                    "label" : "Dystonie dans l’enfance",
                    "label_fr" : "Dystonie dans l’enfance",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4e2",
                    "label" : "Tremblement d’attitude",
                    "label_fr" : "Tremblement d’attitude",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4e3",
                    "label" : "Tremblement d’action",
                    "label_fr" : "Tremblement d’action",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4e3a",
                    "label" : "Préciser le siège",
                    "label_fr" : "Préciser le siège",
                    "type" : "input"
                }, 
                {
                    "id" : "4f1",
                    "label" : "Instabilité posturale",
                    "label_fr" : "Instabilité posturale",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4f2",
                    "label" : "Troubles du sommeil",
                    "label_fr" : "Troubles du sommeil",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4f3",
                    "label" : "Affaiblissement intellectuel",
                    "label_fr" : "Affaiblissement intellectuel",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4f3a",
                    "label" : "Préciser Score MMSE (Annexe 1)",
                    "label_fr" : "Préciser Score MMSE (Annexe 1)",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "4f4a",
                    "label" : "incontinence",
                    "label_fr" : "incontinence",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4f4b",
                    "label" : "mictions impérieuses",
                    "label_fr" : "mictions impérieuses",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4f4c",
                    "label" : "impuissance",
                    "label_fr" : "impuissance",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4f5",
                    "label" : "Hypotension orthostatique",
                    "label_fr" : "Hypotension orthostatique",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4f5a",
                    "label" : "Si OUI : préciser",
                    "label_fr" : "Si OUI : préciser",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "4g1",
                    "label" : "Addiction à la Dopa",
                    "label_fr" : "Addiction à la Dopa",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4g1a",
                    "label" : "Date",
                    "label_fr" : "Date",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "4g2",
                    "label" : "Impulsivité / Trouble du contrôle",
                    "label_fr" : "Impulsivité / Trouble du contrôle",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4g2a",
                    "label" : "Date",
                    "label_fr" : "Date",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "4g3",
                    "label" : "hypersexualité",
                    "label_fr" : "hypersexualité",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4g3a",
                    "label" : "Date",
                    "label_fr" : "Date",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "4g4",
                    "label" : "achats pathologiques",
                    "label_fr" : "achats pathologiques",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4g4a",
                    "label" : "Date",
                    "label_fr" : "Date",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "4g5",
                    "label" : "Appétence pour les aliments sucrés",
                    "label_fr" : "Appétence pour les aliments sucrés",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4g5a",
                    "label" : "Date",
                    "label_fr" : "Date",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "4g6",
                    "label" : "Punding",
                    "label_fr" : "Punding",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4g6a",
                    "label" : "Date",
                    "label_fr" : "Date",
                    "type" : "input",
                    "style" : "float:right"
                }, 
                {
                    "id" : "4g7",
                    "label" : "Autre",
                    "label_fr" : "Autre",
                    "type" : "radio",
                    "values" : "Oui,Non"
                }, 
                {
                    "id" : "4g7a",
                    "label" : "préciser",
                    "label_fr" : "préciser",
                    "type" : "input",
                    "style" : "float:right"
                }           
            ],
            "parent_group" : "parkinsonform"
        }
    ],

});
