(function(){$(function(){return $("form#inscription").submit(function(){var b,a;a=$(this);b=a.attr("action");$.ajax({url:b,type:"POST",data:a.serialize(),datatype:"json",beforeSend:function(a){return $(".row-fluid .span10 #boxes_suscribe .span8 button.btn-warning").attr("disabled","disabled")},success:function(a){return $("form#inscription").stop().show().transition({perspective:"100px",rotateY:"180deg",opacity:0})},error:function(a){return apprise(a.responseText)}});return!1})})}).call(this);
