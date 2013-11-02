$ ->
  $('input[type=file]').attr 'accept', "image/*"

  jcrop_api = undefined
  compteur = 0
  $("form#resize_img_profil").submit ->
    form = $(this)
    action = form.attr("action")
    if $('#w').val() != 0 || $('#h').val() != 0 || $('#x').val() != 0 || $('#y').val() != 0
      $.ajax
        url: action
        type: "POST"
        data: form.serialize()
        datatype: "json"
        beforeSend: (xhr) ->
        success: (data) ->
          window.location.reload()
          return false
        , ->
          bounds = @getBounds()
          boundx = bounds[0]
          boundy = bounds[1]
          jcrop_api = this
        error: (result) ->
          apprise result.responseText
    return false



  # Move the preview into the jcrop container for css positioning
  updatePreview = (c) ->
    updateCoords(c)
    if parseInt(c.w) > 0
      rx = xsize / c.w
      ry = ysize / c.h
      $pimg.css
        width: Math.round(rx * boundx) + "px"
        height: Math.round(ry * boundy) + "px"
        marginLeft: "-" + Math.round(rx * c.x) + "px"
        marginTop: "-" + Math.round(ry * c.y) + "px"

  updateCoords = (c) ->
    $("#x").val c.x
    $("#y").val c.y
    $("#w").val c.w
    $("#h").val c.h

  jcrop_api = undefined
  boundx = undefined
  boundy = undefined
  $preview = $("#preview-pane")
  $pcnt = $("#preview-pane .preview-container")
  $pimg = $("#preview-pane .preview-container img")
  xsize = $pcnt.width()
  ysize = $pcnt.height()
  if $(".cropped").length > 0
    $(".cropped").Jcrop
      minSize: [290, 250]
      setSelect: [75,75,300,300]
      bgOpacity: 0.5
      bgColor: 'white'
      addClass: 'jcrop-light'
      onChange: updatePreview
      onSelect: updatePreview
      aspectRatio : 290/250
    , ->
      bounds = @getBounds()
      boundx = bounds[0]
      boundy = bounds[1]
      jcrop_api = this



  checkCoords = ->
    return true  if parseInt($("#w").val())
    $("#x").val 0
    $("#y").val 0
    $("#w").val 150
    $("#h").val 150
    true
  showPreview = (coords) ->
    if parseInt(coords.w) > 0
      rx = 100 / coords.w
      ry = 100 / coords.h
      $("#preview").css
        width: Math.round(rx * $imgpos.width) + "px"
        height: Math.round(ry * $imgpos.height) + "px"
        marginLeft: "-" + Math.round(rx * coords.x) + "px"
        marginTop: "-" + Math.round(ry * coords.y) + "px"

  $imgpos =
    width: "300"
    height: "180"


  ###
  Uploadify
  ###

  if $(".uploadify").length > 0
    uploader = new qq.FineUploader(
      element: $(".uploadify")[0]
      debug: false
      multiple: false
      sizeLimit: 6048576
      minSizeLimit: 50000
      disableCancelForFormUploads: false
      uploadButton: "Télécharger un fichier"
      request:
        endpoint: $("form#suscribe_upload").attr("action")

      messages:
        typeError: "Photo non valide"
      text:
        uploadButton: '<span class="ico3 ico-images"></span> Télécharger une photo'

      callbacks:
        onComplete: (id, fileName, responseJSON) ->
          if responseJSON.img != "undefined"
            window.location.reload()

        onCancel: (id, fileName) ->
          apprise "photo non ajoutée"
    )

