
$ ->
  $("#place_field").autocomplete
    source: $('#place_field').attr "data-url"
    minLength: 3

  $("#skill_field").autocomplete
    source: $('#skill_field').attr "data-url"
    minLength: 3