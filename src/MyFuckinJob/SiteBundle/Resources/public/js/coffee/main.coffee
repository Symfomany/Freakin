$ ->

  $(".city").autocomplete
      source: (request, response) ->
        $.ajax
          url: "http://ws.geonames.org/searchJSON"
          dataType: "jsonp"
          data:
            featureClass: "P"
            style: "medium"
            maxRows: 12
            country: "FR"
            name_startsWith: request.term

          success: (data) ->
            response $.map(data.geonames, (item) ->
              label: item.name + ((if item.adminName1 then ", " + item.adminName1 else ""))
              value: item.name
            )
      minLength: 2
      open: ->
        $(this).removeClass("ui-corner-all").addClass "ui-corner-top"

      close: ->
        $(this).removeClass("ui-corner-top").addClass "ui-corner-all"
