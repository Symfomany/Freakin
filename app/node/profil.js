var http = require('http');
var app = require('http').createServer();
app.listen(1667);
var io = require("socket.io");
var io = io.listen(app);
var sockets = {};
var mysql = require('mysql');


/**
 * Connexion of user at users
 */
io.sockets.on('connection', function (socket) {
    var me;

    /**
     * On User logged
     */
    socket.on('login', function (user) {

    });

    /**
     * When User Disconnect
     */
    socket.on('disconnect', function () {

    });




    /**
     * Tchats
     */
    socket.on('updateProfil', function (user) {
        $iduser = user.id;
        $field = user.field;
        $val = user.val;


        var connection = mysql.createConnection({
            host :'localhost',
            user:'djscrave',
            password:'OLBypJ_Oeba5B',
            database:'jobeet'
        });

        $query = "UPDATE demandeur SET  "+$field+" =  '"+$val+"' WHERE id="+$iduser+"";
        connection.query($query, function(err, result) {
            if (err) throw err;
            console.log(result.insertId);
        });
        console.log($query);
        io.sockets.emit('updateProfils', user);
    });

});



