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
      error: (result) ->
        alert result.responseText
    return false
