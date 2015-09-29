db.user.drop();
db.user.insert({
    "login":"bernard",
    "password":"bernard2015",
    "profil":"administrateur",
    "nom":"te",
    "prenom":"bernard",
    "email":"bernard.te@inserm.fr",
    "telephone":"0625004713",
    "gsm":"",
    "inactif":"0"
});

db.user.insert({
    "login":"nicolas",
    "password":"nicolas2015",
    "profil":"administrateur",
    "nom":"malservet",
    "prenom":"nicolas",
    "email":"nicolas.malservet@inserm.fr",
    "telephone":"0123456789",
    "gsm":"",
    "inactif":"0"
});

db.user.insert( {
   
    "login" : "matth",
    "password" : "guizmo2015",
    "profil":"administrateur",
    "nom":"penicaud",
    "prenom":"matthieu",
    "email":"matthieu.penicaud@inserm.fr",
    "telephone":"0123456789",
    "gsm":"",
    "inactif":"0"
});

db.user.insert({
    "login":"clinicien",
    "password":"clinicien2015",
    "profil":"clinicien",
    "nom":"clinicien",
    "prenom":"clinicien",
    "email":"clinicien@clinicien.com",
    "telephone":"0123456789",
    "gsm":"",
    "inactif":"0",
    "address":"24 rue de Paris"
});

db.user.insert({
    "login":"neuropath",
    "password":"neuropath2015",
    "profil":"neuropathologiste",
    "nom":"neuropath",
    "prenom":"neuropath",
    "email":"neuropath@neuropath.com",
    "telephone":"0123456789",
    "gsm":"",
    "inactif":"1",
    "centre":"CHU"
});

db.user.insert({
    "login":"geneticien",
    "password":"geneticien2015",
    "profil":"généticien",
    "nom":"geneticien",
    "prenom":"geneticien",
    "email":"geneticien@geneticien.com",
    "telephone":"0123456789",
    "gsm":"",
    "inactif":"1"
});
