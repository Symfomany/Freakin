$(document).ready(function () {


    var convertVideoToJpgAndSendToServer = function (stream, canvasElement, ctx) {
        ctx.drawImage(stream, 0, 0);
        var picture = canvasElement.toDataURL('image/jpeg');
        socket.emit('vs-stream', {
            picture: picture
        });
    }

    var init = function () {
        var videoStream = document.getElementById('sourcevid');
        var canvas = document.getElementById('output');
        var ctx = canvas.getContext('2d');

        // output the video data in the source video element
        var successCallback = function (srm) {
            videoStream.src = window.webkitURL.createObjectURL(srm);
        };

        // log error
        var errorCallback = function (error) {
            console.log('error: ' + error.msg);
        };

        // grab the incoming device data
        window.navigator.webkitGetUserMedia({video: true}, successCallback, errorCallback);

        // send the video data every 250ms
        setInterval(function () {
            convertVideoToJpgAndSendToServer(videoStream, canvas, ctx);
        }, 500);
    }


    if(window.File && window.FileReader){ //These are the relevant HTML5 objects that we are going to use
        document.getElementById('UploadButton').addEventListener('click', StartUpload); //event go upload
        document.getElementById('FileBox').addEventListener('change', FileChosen); //change file
    }
    else
    {
        document.getElementById('UploadArea').innerHTML = "Your Browser Doesn't Support The File API Please Update Your Browser";
    }

    /**
     * Change File name :)
     */
    var SelectedFile;
    var FReader;
    var Name;

    function FileChosen(evnt) {
        SelectedFile = evnt.target.files[0];
        document.getElementById('NameBox').value = SelectedFile.name;
    }




    function UpdateBar(percent){
//        document.getElementById('ProgressBar').style.width = percent + '%';
//        document.getElementById('percent').innerHTML = (Math.round(percent*100)/100) + '%';
//        var MBDone = Math.round(((percent/100.0) * SelectedFile.size) / 1048576);
//        document.getElementById('MB').innerHTML = MBDone;
    }

    var Path = "http://localhost/";

    function Refresh(){
        location.reload(true);
    }


    function timeAgo(){
        var templates = {
            prefix: "Il y a ",
            suffix: " ",
            seconds: "moins d'une minute",
            minute: "une minute",
            minutes: "%d minutes",
            hour: "une hour",
            hours: " %d hours",
            day: "un jour",
            days: "%d jours",
            month: "un mois",
            months: "%d mois",
            year: "1 an",
            years: "%d années"
        };
        var template = function(t, n) {
            return templates[t] && templates[t].replace(/%d/i, Math.abs(Math.round(n)));
        };

        var timer = function(time) {
            if (!time)
                return;
            time = time.replace(/\.\d+/, ""); // remove milliseconds
            time = time.replace(/-/, "/").replace(/-/, "/");
            time = time.replace(/T/, " ").replace(/Z/, " UTC");
            time = time.replace(/([\+\-]\d\d)\:?(\d\d)/, " $1$2"); // -04:00 -> -0400
            time = new Date(time * 1000 || time);

            var now = new Date();
            var seconds = ((now.getTime() - time) * .001) >> 0;
            var minutes = seconds / 60;
            var hours = minutes / 60;
            var days = hours / 24;
            var years = days / 365;

            return templates.prefix + (
                seconds < 45 && template('seconds', seconds) ||
                    seconds < 90 && template('minute', 1) ||
                    minutes < 45 && template('minutes', minutes) ||
                    minutes < 90 && template('hour', 1) ||
                    hours < 24 && template('hours', hours) ||
                    hours < 42 && template('day', 1) ||
                    days < 30 && template('days', days) ||
                    days < 45 && template('month', 1) ||
                    days < 365 && template('months', days / 30) ||
                    years < 1.5 && template('year', 1) ||
                    template('years', years)
                ) + templates.suffix;
        };

        var elements = $('.timeago');

        elements.each(function(index) {
            var $this = $(this);
            if (typeof $this === 'object') {
                $this.html(timer($this.attr('title')));
            }
        });

    }

    timeAgo();
    // update time every minute
    setTimeout(timeAgo, 60000);


    /**
     * Connexion
     * @type {*}
     */
    var socket = io.connect('http://ns3296046.ovh.net:1666');
    var me = null;
    socket.emit('login', {});



    /**
     * --------------------------------------------------Upload Handler----------------------------------------
     */

    socket.on('MoreData', function (data){
        UpdateBar(data['Percent']);
        var Place = data['Place'] * 524288; //The Next Blocks Starting Position
        var NewFile; //The Variable that wll hold the new Block of Data
        NewFile = SelectedFile.slice(Place, Place + Math.min(524288, (SelectedFile.size-Place)));
        FReader.readAsBinaryString(NewFile);
    });

    socket.on('Done', function (data){
        $img = '<img class="img-thumbnail" src="/images/realtime/'+ data['Image'] +'" />';
        $('#UploadBox').find('img').remove();
        $('#UploadBox').append($img).hide().fadeIn('slow');
    });



    /**
     * Go Upload
     * @constructor
     */
    function StartUpload(){
        if(document.getElementById('FileBox').value != "")
        {
            FReader = new FileReader();
            Name = document.getElementById('NameBox').value;
            var Content = "<span id='NameArea'>Uploading " + SelectedFile.name + " as " + Name + "</span>";
            Content += '<div id="ProgressContainer"><div id="ProgressBar"></div></div><span id="percent">0%</span>';
            Content += "<span id='Uploaded'> - <span id='MB'>0</span>/" + Math.round(SelectedFile.size / 1048576) + "MB</span>";
            $('.UploadArea').append(Content);
            FReader.onload = function(evnt){
                socket.emit('Upload', { 'Name' : Name, Data : evnt.target.result });
            }
            socket.emit('Start', { 'Name' : Name, 'Size' : SelectedFile.size });
        }
        else
        {
            alert("Please Select A File");
        }
    }

    /**
     * --------------------------------------------------Action Handler----------------------------------------
     */


    /**
     * Modify Question
     */
    $('#questions .alert h3 .txt').editable(
        function(value, settings) {
            $id = $(this).parents('.alert').attr('id');
            socket.emit('modifyquestion', {
                'message' : value,
                'id' : $id
            });
            return(value);
        }, {
            indicator : 'Saving...',
            tooltip   : 'Click to edit...',
            submit  : 'Je modifie ma question',
            cssclass  : 'around',
            width: 400
        });



    /**
     * Add an annonce: URL or Function to callback
     */
    $('#annonces .alert p').editable(
    function(value, settings) {
        $id = $(this).parents('.alert').attr('id');
        socket.emit('modifyannonce', {
            'message' : value,
            'id' : $id
        });
        return(value);
    }, {
        indicator : 'Saving...',
        tooltip   : 'Click to edit...',
        submit  : 'Je modifie ce texte',
    });



    /**
     * Add an annonce: URL or Function to callback
     */
    $('#annonces .alert h3').editable(
        function(value, settings) {
            $id = $(this).parents('.alert').attr('id');
            socket.emit('modifyannoncetitle', {
                'message' : value,
                'id' : $id
            });
            return(value);
        }, {
            indicator : 'Saving...',
            tooltip   : 'Click to edit...',
            submit  : 'Je modifie ce texte'
        });



    /**
     * Textarea response
     */
    $('.answer').on('click', function (event) {
       $('.answerarea').slideUp('fast');
       $(this).parents('.alert').find('.answerarea').show('fast');
    });



    /**
     * Textarea response
     */
    $('.answerarea').on('keyup', function (event) {
        $(this).parents('.alert').find('.btn-block').addClass('btn-warning').text('En cours de rédaction');
        $id = $(this).parents('.alert').attr('id');
        $valeur = $(this).val();
        socket.emit('keyuparea', {
            'id' : $id,
            'message' : $valeur
        });
    });


    /**
     * Add an annonce
     */
    $('#annonces .alert .close').on('click', function (event) {
        $title = $(this).parents('.alert').find('h3').text();
        $content = $(this).parents('.alert').find('p').text();
        $id = $(this).parents('.alert').attr('id');
        $html = "Souhaitez-vous supprimer cette annonce ?";
        return apprise($html, {
            verify: true,
            textYes: "Confirmer",
            textNo: "Annuler"
        }, function(r) {
            if (r) {
                socket.emit('removeannonce', {
                    'id' : $id
                });
            }
        });
    });


    $('#formquestion #question').on('focus', function (event) {
        $(this).stop().animate({height:'60'});
    });

    $('#formquestion #question').on('blur', function (event) {
        $(this).stop().animate({height:'41'});
    });

    /**
     * Add an annonce
     */
    $('#questions .alert .close').on('click', function (event) {
        $id = $(this).parents('.alert').attr('id');
        $html = "Souhaitez-vous supprimer cette question ?";
        return apprise($html, {
            verify: true,
            textYes: "Confirmer",
            textNo: "Annuler"
        }, function(r) {
            if (r) {
                socket.emit('removequestion', {
                    'id' : $id
                });
            }
        });
    });


    /**
     * Modify an annonce
     */
    $('#formannonce').on('submit', function (event) {
        $title = $('#formannonce #title').val();
        $content = $('#formannonce #content').val();
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
     * Modify an annonce
     */
    $('form#formquestion').on('submit', function (event) {
        $question = $('#formquestion #question').val();
        event.preventDefault();
        if ($question.length > 3) {
            socket.emit('question', {
               'question' : $question
            });
        }
        return false;
    });


    /**
     * Modify an annonce
     */
    $('form.answering').on('submit', function (event) {
        $(this).parents('.alert').find('.btn-block').removeClass('btn-warning').addClass('btn-success').text('Répone validé!');
        $id = $(this).parents('.alert').attr('id');
        $question = $(this).find('.answerarea').val();
        event.preventDefault();
        if ($question.length > 3) {
            socket.emit('answering', {
               'message' : $question,
               'id' : $id
            });
        }
        return false;
    });



    /**
     * --------------------------------------------------receive IO Actions----------------------------------------
     */


    /**
     * Remove an annonce
     */
    socket.on('writearea', function (message) {
        $('#'+ message.id +' .answerarea').parents('.alert').find('button').addClass('btn-warning').text('En cours de rédaction');
        $('.answerarea').not($('#'+ message.id +' .answerarea')).slideUp('fast');
        $('#'+ message.id +' .answerarea').show('fast').val(message.message);
    });


    /**
     * Remove an annonce
     */
    socket.on('removeannonces', function (message) {
        $('#annonces #'+ message.id).fadeOut('fast');
    });

    /**
     * Remove an annonce
     */
    socket.on('removequestions', function (message) {
//        $('#questions #'+ message.id).fadeOut('fast');
        $('#questions #'+ message.id).stop().transition({
            x: '50',
            opacity: 0
        }).fadeOut('slow');

        if($('#questions .alert-info').length == 0 ){
            $('#questions .alert-danger').fadeIn('slow');
        }
    });


    /**
     * Update an annonce
     */
    socket.on('updateannoncestitle', function (message) {
        $('#annonces #'+ message.id).find('h3').text(message.message);
    });


    /**
     * Update an annonce
     */
    socket.on('updateannonces', function (message) {
        $('#annonces #'+ message.id).find('p').text(message.message);
    });


    /**
     * Update an annonce
     */
    socket.on('updatequestions', function (message) {
        $('#questions #'+ message.id).find('h3 .txt').text(message.message);
    });


    /**
     * Update an annonce
     */
    socket.on('updatereponse', function (message) {
        $('#questions #'+ message.id).find('button').removeClass('btn-warning').addClass('btn-success').text('Répone validé!');
        $('#questions #'+ message.id).find('.answerarea').val(message.message);
    });



    $('#questions .alert').stop().transition({
        y: '50',
        opacity: 1
    }).fadeIn('slow');

    /**
     * Add a new annonce
     */
    socket.on('newquestion', function (message) {

        $('#questions .alert-danger').fadeOut('fast');


        $('#formquestion #question').val('');
        $bage = $('#questions .alert').length + 1;
        $elt = $('<div id="'+ message.id +'" class="alert alert-info fade in"><button class="close" type="button">×</button><span class="pull-right" ><a class="answer"><span class="fui-chat"></span> Répondre à cette question </a></span><h3><span class="label label-primary">'+ $bage +'</span><span class="txt"> '+ message.question +'</span></h3><form class="answering" method="post"><textarea name="answerarea" class="answerarea hiden form-control" placeholder="Votre réponse ..." rows="10" cols="95"></textarea><button class="btn btn-block btn-lg btn-danger save" type="submit">J\'enregistre ma réponse</button></form></div>').hide();
        $('#questions').append($elt);
        $elt.show().transition({
            y: '50',
            opacity: 1
        });




        /**
         * Textarea response
         */
        $elt.find('.answerarea').on('keyup', function (event) {
            $(this).parents('.alert').find('.btn-block').addClass('btn-warning').text('En cours de rédaction');
            $id = $(this).parents('.alert').attr('id');
            $valeur = $(this).val();
            socket.emit('keyuparea', {
                'id' : $id,
                'message' : $valeur
            });
        });

        $elt.find('form.answering').on('submit', function (event) {
            $(this).parents('.alert').find('.btn-block').removeClass('btn-warning').addClass('btn-success').text('Répone validée !');
            $id = $(this).parents('.alert').attr('id');
            $question = $(this).find('.answerarea').val();
            event.preventDefault();
            if ($question.length > 3) {
                socket.emit('answering', {
                    'message' : $question,
                    'id' : $id
                });
            }
            return false;
        });


        $elt.find('.answer').on('click', function (event) {
            $('.answerarea').slideUp('fast');
            $(this).parents('.alert').find('.answerarea').show('fast');
        });

        $elt.find('.txt').editable(
            function(value, settings) {
                $id = $(this).parents('.alert').attr('id');
                socket.emit('modifyquestion', {
                    'message' : value,
                    'id' : $id
                });
                return(value);
            }, {
                indicator : 'Saving...',
                tooltip   : 'Click to edit...',
                submit  : 'Je modifie ma question',
                width: 400
            });

        /**
         * Add eveet close
         */
        $elt.find('.close').on('click', function (event) {
            $id = $(this).parents('.alert').attr('id');
            $html = "Souhaitez-vous supprimer cette question ?";
            return apprise($html, {
                verify: true,
                textYes: "Confirmer",
                textNo: "Annuler"
            }, function(r) {
                if (r) {
                    socket.emit('removequestion', {
                        'id' : $id
                    });
                }
            });
        });

    });



    /**
     * Add a new annonce
     */
    socket.on('newannonces', function (message) {
        $elt = $('<div id="'+ message.id +'" class="alert alert-warning fade in"><button class="close" type="button">×</button><h3>'+ message.title +'</h3><p>'+ message.content +'</p></div>').hide();
        $elt.find('.close').on('click', function (event) {
            $title = $(this).parents('.alert').find('h3').text();
            $content = $(this).parents('.alert').find('p').text();
            $id = $(this).parents('.alert').attr('id');
            $html = "Souhaitez-vous supprimer cette annonce ?";
            return apprise($html, {
                verify: true,
                textYes: "Confirmer",
                textNo: "Annuler"
            }, function(r) {
                if (r) {
                    socket.emit('removeannonce', {
                        'content' : $content,
                        'title' : $title,
                        'id' : $id
                    });
                }
            });
        });

        $('#annonces').append($elt);
        $elt.show('slow');


        $elt.find('p').editable(
            function(value, settings) {
                $id = $(this).parents('.alert').attr('id');
                socket.emit('modifyannonce', {
                    'message' : value,
                    'id' : $id
                });
                return(value);
            }, {
                indicator : 'Saving...',
                tooltip   : 'Click to edit...',
                submit  : 'Je modifie ce texte'
            });

        $elt.find('h3').editable(
            function(value, settings) {
                $id = $(this).parents('.alert').attr('id');
                socket.emit('modifyannoncetitle', {
                    'message' : value,
                    'id' : $id
                });
                return(value);
            }, {
                indicator : 'Saving...',
                tooltip   : 'Click to edit...',
                submit  : 'Je modifie ce texte'
        });

    });


});