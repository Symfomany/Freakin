$(document).ready(function () {

    /**
     * Connexion
     * @type {*}
     */
    var socket = io.connect('http://ns3296046.ovh.net:1666');
    /**
     * --------------------------------------------------Receive IO----------------------------------------
     */

        socket.on('doneinsertmess', function(data){
            $elt = '<div style="opacity:0" class="alert alert-block alert-success" id="' + data.id + '">';
            $elt += '<h3>'+ data.title +'</h3>';
            $elt += '<p>'+ data.description +'</p>';
            $elt += '<a class=" btn btn-success pull-right" data-id="'+ data.id +'"><span class="fui-heart"></span></span> Coup de coeur</a><a class=" btn btn-info pull-right" data-id="'+ data.id +'"><span class="fui-cross"></span> Répondre ce message</a><a class="remove btn btn-danger pull-right" data-id='+ data.id +'><span class="fui-new"></span> Supprimmer ce message</a><div class="clear"></div>';
            $elt += '</div>';
            $elt = $($elt);
            $('#les-messages').prepend($elt);
            $elt.stop().transition({
                x: '5',
                opacity: 1
            });
        });

        socket.on('donedeletemess', function(data){
            $('div#'+data.id).stop().transition({
                y: '50',
                opacity: 0
            }).fadeIn('slow', function(){
                    $(this).remove()
                });
        });

        socket.on('doneanswermess', function(data){
            $elt = '<div style="opacity:0" class="alert alert-block alert-success" id="' + data.id + '">';
            $elt += '<h3>Réponse</h3>';
            $elt += '<p>'+ data.answer +'</p>';
            $elt += '<div class="hide repondre"><textarea id="answer" class="form-control" placeholder="Répondre à ce message..." ></textarea><a class="btn btn-inverse pull-right">Valider</a></div>'
            $elt += '<a class=" btn btn-success pull-right" data-id="'+ data.id +'"><span class="fui-heart"></span></span> Coup de coeur</a><a class="write btn btn-info pull-right" data-id="'+ data.id +'"><span class="fui-cross"></span> Répondre ce message</a><a class="remove btn btn-danger pull-right" data-id='+ data.id +'><span class="fui-new"></span> Supprimmer ce message</a><div class="clear"></div>';
            $elt += '</div>';
            $elt = $($elt);
            $('#'+data.parent).append($elt);
            $elt.stop().transition({
                x: '5',
                opacity: 1
            });
        })




    /**
     * --------------------------------------------------Action Handler----------------------------------------
     */


    $('#formongoose').on('submit', function(event){
        socket.emit('insertmessage', {
            title :$('#message_title').val(),
            description : $('#message_description').val(),
            user : $('#hidden-user').val()
        })
        return false
    })

    $('#les-messages').on('click','.remove', function(event){
        socket.emit('deletemessage', {
            id : $(this).attr('data-id')
        });
        return false
    })

    $('#les-messages').on('click','.write', function(event){
        $(this).siblings('.hide').hide().removeClass('hide').fadeIn("slow");
    })

    $('.repondre').on('click','.btn', function(event){
        socket.emit('addanswermessage', {
            parent : $(this).closest('.alert-block').attr('id'),
            answer : $(this).siblings('#answer').val()
        });
        return false
    })

});
