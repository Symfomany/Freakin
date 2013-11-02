$ ->
  $("form#inscription").submit ->
    form = $(this)
    action = form.attr "action"
    $.ajax
      url: action
      type: "POST"
      data: form.serialize()
      datatype: "json"
      beforeSend: (xhr) ->
        $(".row-fluid .span10 #boxes_suscribe .span8 button.btn-warning").attr "disabled", "disabled"
      success: (data) ->
        $('form#inscription').stop().show().transition
          perspective: '100px'
          rotateY: '180deg'
          opacity: 0
      error: (result) ->
        apprise result.responseText
    false
