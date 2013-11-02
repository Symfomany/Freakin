$(document).ready(function () {

    /**
     * Undescrore Libraries
     */
//     console.log(_.uniq([5, 3, 5, 5, 3, 1]));
////     console.log(_.each([1, 2, 3], alert));
////     console.log(_.each({one: 1, two: 2, three: 3}, alert));
//     console.log(_.map([1, 2, 3], function(num){ return num * 3; }));
//     console.log(_.reduce([1, 2, 3], function(memo, num){ return memo + num; }, 0));
//     console.log(_.find([1, 2, 3, 4, 5, 6], function(num){ return num % 2 == 0; }));
//     console.log(_.filter([1, 2, 3, 4, 5, 6], function(num){ return num % 2 == 0; }));
//     console.log(_.reject([1, 2, 3, 4, 5, 6], function(num){ return num % 2 == 0; }));
//     console.log(_.every([true, 1, null, 'yes'], _.identity));
//     console.log(_.some([null, 0, 'yes', false]));
//     console.log(_.invoke([[5, 1, 7], [3, 2, 1]], 'sort'));
//
//     var stooges = [{name: 'moe', age: 40}, {name: 'larry', age: 50}, {name: 'curly', age: 60}];
//     console.log(_.pluck(stooges, 'name'));
//     console.log(_.max(stooges, function(stooge){ return stooge.age; }));
//
//    var numbers = [10, 5, 100, 2, 1000];
//    console.log(_.min(numbers));
//
//    _.countBy([1, 2, 3, 4, 5], function(num) {
//        console.log(num % 2 == 0 ? 'even': 'odd');
//    });
//
//    console.log(_.shuffle([1, 2, 3, 4, 5, 6]));
//
//    console.log(_.sample([1, 2, 3, 4, 5, 6]));
//
//    console.log(_.size({one: 1, two: 2, three: 3}));
//    console.log(_.first([5, 4, 3, 2, 1]));
//    console.log(_.last([5, 4, 3, 2, 1]));
//
//
//    console.log(_.without([1, 2, 1, 0, 3, 1, 4], 0, 1));
//    console.log(_.union([1, 2, 3], [101, 2, 1, 10], [2, 1]));
//    console.log(_.intersection([1, 2, 3], [101, 2, 1, 10], [2, 1]));
//    console.log(_.difference([1, 2, 3, 4, 5], [5, 2, 10]));
//    console.log(_.uniq([1, 2, 1, 3, 1, 4]));
//    console.log(_.object(['moe', 'larry', 'curly'], [30, 40, 50]));
//    console.log(_.indexOf([1, 2, 3], 2));
//    console.log(_.range(10));




    /**
     * Connexion
     * @type {*}
     */
    var socket = io.connect('http://ns3296046.ovh.net:1666');
    /**
     * --------------------------------------------------Receive IO----------------------------------------
     */
//
//
//    /**
//     * Add a user
//     */
//    socket.on('mongoosetestoutput', function (data) {
//        apprise(data.addresses[0].ville);
//    });
//
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


//
//    /**
//     * --------------------------------------------------Action Handler----------------------------------------
//     * --------------------------------------------------Action Handler----------------------------------------
//     */
//
//
//    /**
//     * Add a user
//     */
//    $('#formongoose').on('submit', function (event) {
//        $firstname = $('#formongoose #firstname').val();
//        $reponse = $('#formongoose #description').val();
//        event.preventDefault();
//        socket.emit('mongoosetest', {
//            'firstname' : $firstname,
//            'reponse' : $reponse
//        });
//        return false;
//    });

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
