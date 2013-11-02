var mongo = require('mongoskin');
var db = mongo.db('mongodb://localhost:27017/test_database');



db.collection('Notifications').find().toArray(function (err, items) {
    console.dir(items);
});

//db.collection('Notifications').findOne({_id: '5242cc6ee41dd1747f000000'}, function (err, post) {
//    console.dir(post);
//});

db.collection('Notifications').insert({titre: 'Allo', action : 2});