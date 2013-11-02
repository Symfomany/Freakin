
$ ->
  $("input#twoplages").on "change", ->
    $('#simpleview .hide').toggleClass('show')
    $('select').val('').trigger('change')

