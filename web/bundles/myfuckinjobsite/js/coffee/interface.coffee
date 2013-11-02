
$ ->

  ###
    ------------------------------------------------------------------------Initialize----------------------------------------------------------
  ###

  ###
    Io Connect
  ###
  socket = io.connect "http://ns3296046.ovh.net:1666"
  me = null
  socket.emit "login", {}





  ###
    ------------------------------------------------------------------------Recept an IO Event----------------------------------------------------------
  ###

  ###
  Receive a Remove a annonces
  ###
  socket.on "removeannonces", (message) ->
    $("#annonces #" + message.id).fadeOut "fast"


  ###
  Affichage new annonce
  ###
  socket.on "newannonces", (message) ->
    $elt = $("<div id=\"" + message.id + "\" class=\"alert alert-warning fade in\"><button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button><h3>" + message.title + "</h3><p>" + message.content + "</p></div>").hide()
    $("#annonces").append $elt
    $elt.show "slow"







  ###
    ------------------------------------------------------------------------Action Handlers----------------------------------------------------------
  ###

  ###
    On Close Button Annonces
  ###
  $("#annonces .close").on "click", ->
    $title = $(this).parents(".alert").find("h3").text()
    $content = $(this).parents(".alert").find("p").text()
    $id = $(this).parents(".alert").attr("id")
    socket.emit "removeannonce",
      content: $content
      title: $title
      id: $id


  ###
  Submit an annonces
  ###
  $("#formannonce").on "submit", (event) ->
    form = $(this)
    action = form.attr("action")
    $title = $("#formannonce #title").val()
    $content = $("#formannonce #content").val()
    obj = {}
    obj.titre = $title
    obj.contenu = $content
    event.preventDefault()
    if $content.length > 3
      socket.emit "annonce",
        content: $content
        title: $title
    false