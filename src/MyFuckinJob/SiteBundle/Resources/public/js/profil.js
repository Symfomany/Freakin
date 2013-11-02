$(document).ready(function () {

    /**
     * Connexion
     * @type {*}
     */
    var socket = io.connect('http://ns3296046.ovh.net:1667');
    var me = null;
    socket.emit('login', {});



    /**
     * --------------------------------------------------Upload Handler----------------------------------------
     */

    /**
     * Tchats
     */
    socket.on('updateProfils', function (user) {
          $('span[data-field="'+user.field+'"]').text(user.val);
    });

    /**
     * --------------------------------------------------Action Handler----------------------------------------
     */


    /**
     * Modify Question
     */
    $('.editable').editable(
        function(value, settings) {
            $id = $(this).attr('data-user');
            $field = $(this).attr('data-field');
            $val = value;
            socket.emit('updateProfil', {
                'id' : $id,
                'field' : $field,
                'val' : $val
            });
            return(value);
        }, {
            indicator : 'Saving...',
            tooltip   : 'Click to edit...',
            submit  : 'Je modifie ma question',
            cssclass  : 'around',
            width: 400
        });



});
