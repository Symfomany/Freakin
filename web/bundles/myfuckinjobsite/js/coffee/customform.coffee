$ ->

  $("input[type=checkbox], input[type=radio]").iCheck
    checkboxClass: "icheckbox_square-red"
    radioClass: "iradio_square-red"
    increaseArea: "20%"

checkboxHeight = "11"
radioHeight = "11"
selectWidth = "190"

#if $('select').length != 0
# No need to change anything after this
document.write "<style type=\"text/css\">input.styled { display: none; } select.styled { position: relative; width: " + selectWidth + "px; opacity: 0; filter: alpha(opacity=0); z-index: 5; } .disabled { opacity: 0.5; filter: alpha(opacity=50); }</style>"
Custom =
  init: ->
    inputs = document.getElementsByTagName("input")
    span = Array()
    textnode = undefined
    option = undefined
    active = undefined
    a = 0
    while a < inputs.length
      a++
    inputs = document.getElementsByTagName("select")
    a = 0
    while a < inputs.length
      if inputs[a].className is "styled"
        option = inputs[a].getElementsByTagName("option")
        active = option[0].childNodes[0].nodeValue
        textnode = document.createTextNode(active)
        b = 0
        while b < option.length
          textnode = document.createTextNode(option[b].childNodes[0].nodeValue)  if option[b].selected is true
          b++
        span[a] = document.createElement("span")
        span[a].className = "select"
        span[a].id = "select" + inputs[a].name
        span[a].appendChild textnode
        inputs[a].parentNode.insertBefore span[a], inputs[a]
        unless inputs[a].getAttribute("disabled")
          inputs[a].onchange = Custom.choose
        else
          inputs[a].previousSibling.className = inputs[a].previousSibling.className += " disabled"
      a++
#    document.onmouseup = Custom.clear

#  check: ->
#    element = @nextSibling
#    if element.checked is true and element.type is "checkbox"
#      @style.backgroundPosition = "0 0"
#      element.checked = false
#    else
#      if element.type is "checkbox"
#        @style.backgroundPosition = "0 -" + checkboxHeight * 2 + "px"
#      else
#        @style.backgroundPosition = "0 -" + radioHeight * 2 + "px"
#        group = @nextSibling.name
#        inputs = document.getElementsByTagName("input")
#        a = 0
#        while a < inputs.length
#          inputs[a].previousSibling.style.backgroundPosition = "0 0"  if inputs[a].name is group and inputs[a] isnt @nextSibling
#          a++
#      element.checked = true

#  clear: ->
#    inputs = document.getElementsByTagName("input")
#    b = 0
#    while b < inputs.length
#      if inputs[b].type is "checkbox" and inputs[b].checked is true and inputs[b].className is "styled"
#        inputs[b].setAttribute('checked', 'checked')
#        inputs[b].previousSibling.style.backgroundPosition = "0 -" + checkboxHeight * 2 + "px"
#      else if inputs[b].type is "checkbox" and inputs[b].className is "styled"
#        inputs[b].setAttribute('checked', false)
#        inputs[b].previousSibling.style.backgroundPosition = "0 0"
#      else if inputs[b].type is "radio" and inputs[b].checked is true and inputs[b].className is "styled"
#        inputs[b].previousSibling.style.backgroundPosition = "0 -" + radioHeight * 2 + "px"
#      else inputs[b].previousSibling.style.backgroundPosition = "0 0"  if inputs[b].type is "radio" and inputs[b].className is "styled"
#      b++

  choose: ->
    option = @getElementsByTagName("option")
    d = 0
    while d < option.length
      document.getElementById("select" + @name).childNodes[0].nodeValue = option[d].childNodes[0].nodeValue  if option[d].selected is true
      d++

window.onload = Custom.init
