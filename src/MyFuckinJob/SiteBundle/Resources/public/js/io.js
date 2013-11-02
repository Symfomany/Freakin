$(document).ready(function () {
    var socket = io.connect('http://ns3296046.ovh.net:1666');
    var me = null;



    socket.emit('login', {});

    socket.on('new_pvr_message', function (user) {
        me = user;
        $description = user.message;
        $li = "<li class='alert-block alert-success'><span class='description'>" + $description + "</span></li>";
        $('#messages_user').prepend($li);
    });

    socket.on('logged', function (user) {
        me = user;
        $li = "<li class='alert-block alert-success'>Il s'est connecté</li>";
        $('#messages').prepend($li);
    });

    socket.on('disconnect', function (user) {});

    /**
     */
    $('#messagerie').on('submit', function (event) {
        var form = $(this);
        var action = form.attr('action');
        $txt = $('#messagetxt').val();
        event.preventDefault();
        if ($txt.length > 3) {
            socket.emit('messagerie', {
                message: $txt
            });
            $elt = $('<p class="clear"><span class="text-info">Il a dit:</span> ' + $('#messagetxt').val() + '</p>').hide();
            $('#messages').append($elt);
            $elt.show('slow');
            $('#messages').animate({scrollTop:$('#messages').prop('scrollHeight')}, 500);
            $('#messagetxt').val('');
            $('#messagetxt').focus();
        }
        return false;
    });

    $('#messagetxt').on('focus', function () {
        socket.emit('write', {
        });
    });


    socket.on('writing', function (user) {
        $('#writin').html('<small><i>Il est en train de rédigé un message... </i></small>');
    });

    socket.on('notwriting', function (user) {
        $('#writin').html('');
    });

    socket.on('newmessage', function (user) {
        $('#writin').html('');
        $elt = $('<p class="clear" id="' + user.id + '"><span class="text-info">Il a dit:</span> ' + user.message + '</p>').hide();
        $('#messages').append($elt);
        $elt.show('slow');
        $('#messages').animate({scrollTop:$('#messages').prop('scrollHeight')}, 500);
    });

    /**
     * Annonces avec Mongo
     */


    /**
     * Submit
     */
    $('#annonces .close').on('click', function (event) {
        $title = $(this).parents('.alert').find('h3').text();
        $content = $(this).parents('.alert').find('p').text();
        $id = $(this).parents('.alert').attr('id');
        socket.emit('removeannonce', {
            'content' : $content,
            'title' : $title,
            'id' : $id
        });
    });

    /**
     * Remove
     */
    socket.on('removeannonces', function (message) {
        $('#annonces #'+ message.id).fadeOut('fast');
    });

    /**
     * Submit
     */
    $('#formannonce').on('submit', function (event) {
        var form = $(this);
        var action = form.attr('action');
        $title = $('#formannonce #title').val();
        $content = $('#formannonce #content').val();
        var obj = {};
        obj.titre = $title;
        obj.contenu = $content;
        event.preventDefault();
        if ($content.length > 3) {
            socket.emit('annonce', {
               'content' : $content,
               'title' : $title
            });
        }
        return false;
    });

    /**
     * Affichage new annonce
     */
    socket.on('newannonces', function (message) {
        $elt = $('<div id="'+ message.id +'" class="alert alert-warning fade in"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><h3>'+ message.title +'</h3><p>'+ message.content +'</p></div>').hide();
        $('#annonces').append($elt);
        $elt.show('slow');
    });


});