db = connect("localhost:27017/mongo");
//use
db = db.getSiblingDB('mongo');

var c = db.Messages.find().limit(2);
var d = db.Messages.findOne( { description : "fgdfgdfg" } )


var elt = {title : 'Blabla', description: 'Alpha...', parent: 'sfdfsdf'}
//var subtitle = {title : {primary: 'Blabla', secondary: 'Bloblo'},subtitles: ['Bleble', 'Blibli'], description: 'Alpha...'}
//db.Messages.insert(elt);

db.Messages.insert( {  ancestors: [ "Books", "Programming", "Databases" ], parent: "Databases" } )
db.Messages.ensureIndex( { ancestors: 1 } )

//db.Messages.findOne( { _id: "524874c15dc691c0fba1f2c0" } ).ancestors


//db.Messages.findOne( { _id: "524872afbdba83ea66b7567f" })
//db.Messages.ensureIndex( { parent: 1 } )
//printjson(db.Messages.findOne( { parent: "sfdfsdf" } )).parent

//var c = db.Messages.find().limit(10);
//while ( c.hasNext() ) printjson( c.next() )
//for (var i = 1; i <= 5; i++) db.Messages.insert( { title : 'ok', description: 'sa va' } )


//printjson(c[4])
//while ( c.hasNext() ) printjson( c.next() )