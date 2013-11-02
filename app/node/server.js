
var app = require('http').createServer();
app.listen(1666);
var io = require("socket.io");
var io = io.listen(app);
var sockets = {};

var mongo = require('mongoskin');
var db = mongo.db('mongodb://localhost:27017/mongo');
var ObjectID = mongo.ObjectID;

var  fs = require('fs')
    , exec = require('child_process').exec
    , util = require('util');

/**
 * Initialisation...
 */
//var io = require('socket.io').listen(server);
var messages = [];
var history = 6;
var iduser = null;
var users = {};
var Files = {};


/**
 * Handler Upload File
 * @param res
 */
function handler (req, res) {
    fs.readFile('http://bateau.weekinsport.fr/interface',
        function (err, data) {
            if (err) {
                res.writeHead(500);
                return res.end('Error loading index.html');
            }
            res.writeHead(200);
            res.end(data);
        });
}


/**
 * Connexion of user at users
 */
io.sockets.on('connection', function (socket) {
    var me;

    for (var k in users) {
        socket.emit('logged', users[k]);
    }

    for (var l in messages) {
        socket.emit('newmessage', messages[l]);
    }

    /**
     * On User logged
     */
    socket.on('login', function (user) {
        me = user;
        users[me.id] = me;
        socket.broadcast.emit('logged', me);
    });

    /**
     * When User Disconnect
     */
    socket.on('disconnect', function () {
        if(!me){
            return false;
        }
        delete users[me.id];
        socket.broadcast.emit('disconnect', me);
    });




    /**
     * Receive Upload
     */
    socket.on('Start', function (data) { //data contains the variables that we passed through in the html file
        var Name = data['Name'];
        Files[Name] = {  //Create a new Entry in The Files Variable
            FileSize : data['Size'],
            Data   : "",
            Downloaded : 0
        }
        var Place = 0;
        try{
            var Stat = fs.statSync('/var/www/project/web/images/realtime/' +  Name);
            if(Stat.isFile())
            {
                Files[Name]['Downloaded'] = Stat.size;
                Place = Stat.size / 524288;
            }
        }
        catch(er){} //It's a New File
        fs.open("/var/www/project/web/images/realtime/" + Name, "a", 0755, function(err, fd){
            if(err)
            {
                console.log(err);
            }
            else
            {
                Files[Name]['Handler'] = fd; //We store the file handler so we can write to it later
                socket.emit('MoreData', { 'Place' : Place, Percent : 0 });
            }
        });
        if(Files[Name]['Downloaded'] == Files[Name]['FileSize']){
            io.sockets.emit('Done',  {'Image' : '' + Name + ''});
        }

    });

    /**
     * On Upload
     */
    socket.on('Upload', function (data){
        var Name = data['Name'];
        Files[Name]['Downloaded'] += data['Data'].length;
        Files[Name]['Data'] += data['Data'];
        if(Files[Name]['Downloaded'] == Files[Name]['FileSize']) //If File is Fully Uploaded
        {
            io.sockets.emit('Done',  {'Image' : '' + Name + ''});

            fs.write(Files[Name]['Handler'], Files[Name]['Data'], null, 'Binary', function(err, Writen){
                //Get Thumbnail Here
            });
        }
        else if(Files[Name]['Data'].length > 10485760){ //If the Data Buffer reaches 10MB
            fs.write(Files[Name]['Handler'], Files[Name]['Data'], null, 'Binary', function(err, Writen){
                Files[Name]['Data'] = ""; //Reset The Buffer
                var Place = Files[Name]['Downloaded'] / 524288;
                var Percent = (Files[Name]['Downloaded'] / Files[Name]['FileSize']) * 100;
                socket.emit('MoreData', { 'Place' : Place, 'Percent' :  Percent});
            });
        }
        else
        {
            var Place = Files[Name]['Downloaded'] / 524288;
            var Percent = (Files[Name]['Downloaded'] / Files[Name]['FileSize']) * 100;
            socket.emit('MoreData', { 'Place' : Place, 'Percent' :  Percent});
        }
    });




    /**
     * Suscribe
     */
    socket.on('write', function (user) {
        io.sockets.emit('writing', user);
    });

    /**
     * add Friend
     */
    socket.on('addfriend', function (user) {
        iduser = user.iduser;
        iduseradded = user.iduseradded;
        io.sockets.socket(socketid).emit('addfriendlalerting', user);
    });


    /**
     * remove Friend
     */
    socket.on('removefriend', function (user) {
        iduser = user.iduser;
        iduseradded = user.iduseradded;
        io.sockets.emit('removefriendlalerting', user);
    });

    /**
     * add Message
     */
    socket.on('addmessage', function (user) {
        iduser = user.iduser;
        iduseradded = user.iduseradded;
        io.sockets.emit('addmessagelalerting', user);
    });

    /**
     * Not Suscribe
     */
    socket.on('notwrite', function (user) {
        iduser = user.id;
        destinataire = user.destinataire;
        firstname = user.firstname;
        io.sockets.emit('notwriting', user);
    });


    /**
     * Tchats
     */
    socket.on('messagerie', function (message) {
        message.user = me;
        message.message = message.message;
        messages.push(message);
        if (messages.length > history) {
            messages.shift();
        }
        socket.broadcast.emit('newmessage', message);
    });


    /**
     * Question
     */
    socket.on('question', function (message) {
        var annonce = { question: message.question, dateCreated : js_yyyy_mm_dd_hh_mm_ss() };
        db.collection('Test').insert(annonce, function(err) {
            if(err) {
                return console.log('inser error', err);
            }
            message.id = annonce._id;
        });
        io.sockets.emit('newquestion', message);
    });


    /**
     * Area writting
     */
    socket.on('keyuparea', function (message) {
        socket.broadcast.emit('writearea', message);
    });


    /**
     * Tchats
     */
    socket.on('annonce', function (message) {
        var annonce = {titre: message.title, content: message.content };
        db.collection('Notifications').insert(annonce, function(err) {
            if(err) {
                return console.log('inser error', err);
            }
            message.id = annonce._id;
        });
        io.sockets.emit('newannonces', message);
    });

    /**
     * Remove annonce
     */
    socket.on('removeannonce', function (message) {
        var annonce = { _id:  new ObjectID(message.id) };
        db.collection('Notifications').remove(annonce);
        io.sockets.emit('removeannonces', message);
    });

    /**
     * Remove question
     */
    socket.on('removequestion', function (message) {
        var annonce = { _id:  new ObjectID(message.id) };
        db.collection('Test').remove(annonce);
        io.sockets.emit('removequestions', message);
    });


    /**
     * Update questions
     */
    socket.on('modifyquestion', function (message) {
        var annonce = { _id:  new ObjectID(message.id) };
        var update = { question: message.message, dateCreated : js_yyyy_mm_dd_hh_mm_ss() };
        db.collection('Test').update(annonce, {$set: update} );
        io.sockets.emit('updatequestions', message);
    });



    /**
     * Update questions
     */
    socket.on('answering', function (message) {
        var annonce = { _id:  new ObjectID(message.id) };
        var update = { reponse: message.message, dateCreated : js_yyyy_mm_dd_hh_mm_ss() };
        db.collection('Test').update(annonce, {$set: update} );
        io.sockets.emit('updatereponse', message);
    });



    /**
     * Update annonce
     */
    socket.on('modifyannonce', function (message) {
        var annonce = { _id:  new ObjectID(message.id) };
        var update = { content: message.message };
        db.collection('Notifications').update(annonce, {$set: update} );
        io.sockets.emit('updateannonces', message);
    });


    /**
     * Update title annonce
     */
    socket.on('modifyannoncetitle', function (message) {
        var annonce = { _id:  new ObjectID(message.id) };
        var update = { titre: message.message };
        db.collection('Notifications').update(annonce, {$set: update} );
        io.sockets.emit('updateannoncestitle', message);
    });

    function js_yyyy_mm_dd_hh_mm_ss () {
        now = new Date();
        year = "" + now.getFullYear();
        month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
        day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
        hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
        minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
        second = "" + now.getSeconds(); if (second.length == 1) { second = "0" + second; }
        now = Math.round(new Date()/1000);

        return now;
    }


    /**
     * Add a user
     */
    socket.on('mongoosetest', function (data) {
        var arrondissements = ["8eme", "14eme", "17eme"];
        var addressestab = [{ville: 'Lyon'},{ville: 'Paris',arrondissements: arrondissements, habitants: 15200000, longitude: 5.6565, latitude: 45.886 }];
        var inscription = { firstname: data.firstname,  description: data.reponse, addresses: addressestab};
        db.collection('User').insert(inscription);
        io.sockets.emit('mongoosetestoutput', inscription);
    });


    /**
     * Add a user
     */
    socket.on('insertmessage', function (data) {
        var mess = {title: data.title, description: data.description, dateCreated : js_yyyy_mm_dd_hh_mm_ss() };
        db.collection('Messages').insert(mess, function(err) {
            if(err) {
                return console.log('inser error', err);
            }
            data.id = mess._id;
        });
        io.sockets.emit('doneinsertmess', data);
    });


    /**
     * Add a message with parent
     */
    socket.on('addanswermessage', function (data) {
        var parentId = data.id;
       db.collection('Messages').findOne({ _id: new ObjectID(data.parent) } , function (err, doc){
           var mess = {title: 'aucun', description: data.answer, dateCreated : js_yyyy_mm_dd_hh_mm_ss(), parent: doc };
           db.collection('Messages').insert(mess, function(err) {
               if(err) {
                   return console.log('inser error', err);
               }
               data.id = mess._id;
           });
       });
        io.sockets.emit('doneanswermess', data);
    });


    /**
     * Add a user
     */
    socket.on('deletemessage', function (data) {
        var mess = { _id:  new ObjectID(data.id) };
        db.collection('Messages').remove(mess);
        io.sockets.emit('donedeletemess', data);
    });

    /**
     * Add a user
     */
    socket.on('updatemessage', function (data) {
        var mess = { _id:  new ObjectID(data.id) };
        db.collection('Messages').remove(mess);
        io.sockets.emit('doneupdatemess', data);
    });

});






