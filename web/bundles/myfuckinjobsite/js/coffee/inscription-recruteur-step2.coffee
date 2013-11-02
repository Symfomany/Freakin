$ ->
  $('#jcrop_target').Jcrop
    onChange: showPreview
    onSelect: showPreview
    aspectRatio: 1

  $("#cropImg").on "click", ->
    if $('#w').val() != 0 || $('#h').val() != 0 || $('#x').val() != 0 || $('#y').val() != 0
      $.ajax
        url: $(this).attr('data-url')
        type: "POST"
        data: { w: $('#w').val(), h: $('#h').val(), x: $('#x').val(), y: $('#y').val() }
        datatype: "json"
        beforeSend: (xhr) ->
        success: (data) ->
          if (!!data.img)
            $('.jcrop-holder').remove()
            $('#jcrop_target').attr('src', data.img).css
              'visibility' : 'visible'
              'display' : 'block'
          false
        error: (result) ->
          apprise result.responseText
          return
    false
  return
  
showPreview = (coords)->
  rx = 100 / coords.w
  ry = 100 / coords.h

  $('#preview').css
    width: Math.round(rx * 500) + 'px'
    height: Math.round(ry * 370) + 'px'
    marginLeft: '-' + Math.round(rx * coords.x) + 'px'
    marginTop: '-' + Math.round(ry * coords.y) + 'px'
    $('#x').val(coords.x)
    $('#y').val(coords.y)
    $('#x2').val(coords.x2)
    $('#y2').val(coords.y2)
    $('#w').val(coords.w)
    $('#h').val(coords.h)

  return