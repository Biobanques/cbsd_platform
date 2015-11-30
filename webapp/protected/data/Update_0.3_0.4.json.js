//update des droits
db.Droits.drop()
db.Droits.insert({
    "_id" : ObjectId("562634e92d2b45afc5b8035e"),
    "profil" : "administrateur",
    "type" : "clinique",
    "role" : [ 
        "view", 
        "update", 
        "delete"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("562640092d2b45afc5b8035f"),
    "profil" : "clinicien",
    "type" : "clinique",
    "role" : [ 
        "view", 
        "update", 
        "delete", 
        "create"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("562640662d2b45afc5b80360"),
    "profil" : "neuropathologiste",
    "type" : "clinique",
    "role" : [ 
        "view"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("5626406c2d2b45afc5b80361"),
    "profil" : "geneticien",
    "type" : "clinique",
    "role" : [ 
        "view"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("562640712d2b45afc5b80362"),
    "profil" : "chercheur",
    "type" : "clinique",
    "role" : [ 
        "view"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("5626438a2d2b45afc5b80363"),
    "profil" : "administrateur",
    "type" : "neuropathologique",
    "role" : [ 
        "view", 
        "update", 
        "delete"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("562651932d2b45afc5b80364"),
    "profil" : "neuropathologiste",
    "type" : "neuropathologique",
    "role" : [ 
        "view", 
        "update", 
        "delete", 
        "create"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("562651a72d2b45afc5b80365"),
    "profil" : "geneticien",
    "type" : "neuropathologique",
    "role" : [ 
        "view"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("562651b22d2b45afc5b80366"),
    "profil" : "chercheur",
    "type" : "neuropathologique",
    "role" : [ 
        "view"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("562651cd2d2b45afc5b80367"),
    "profil" : "clinicien",
    "type" : "neuropathologique",
    "role" : ""
});
db.Droits.insert({
    "_id" : ObjectId("562652252d2b45afc5b80368"),
    "profil" : "clinicien",
    "type" : "genetique",
    "role" : ""
});
db.Droits.insert({
    "_id" : ObjectId("5626522d2d2b45afc5b80369"),
    "profil" : "administrateur",
    "type" : "genetique",
    "role" : [ 
        "view", 
        "update", 
        "delete"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("5626523d2d2b45afc5b8036a"),
    "profil" : "neuropathologiste",
    "type" : "genetique",
    "role" : [ 
        "view"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("562652522d2b45afc5b8036b"),
    "profil" : "geneticien",
    "type" : "genetique",
    "role" : [ 
        "view", 
        "update", 
        "delete", 
        "create"
    ]
});
db.Droits.insert({
    "_id" : ObjectId("562652572d2b45afc5b8036c"),
    "profil" : "chercheur",
    "type" : "genetique",
    "role" : [ 
        "view"
    ]
});