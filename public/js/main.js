//全域變數
var BASE_URL = location.protocol + '//' + location.hostname;
    betAmount='';
$('#game').hide();

$("#money input").click(function(){
  var $touch=$(this).attr("class"),
      touchEvent = $touch.split(' ');
  betAmount = touchEvent[1];
  $('#betAmount').html("");
  $('#betAmount').append('下注金額：$'+betAmount);
});


var aa = $( "#user" ).load( "public/log.html" ).dialog({
    autoOpen: false,
    modal: true,
    buttons: {
      "Login": function() {
        var nameValue=$("#name").val();
        var passwordValue=$("#password").val();
        var list = {
          "name" : nameValue,
          "password" : passwordValue
        }
        loginCheck(list);
        aa.dialog( "close" );
      }
    }
  });

$("#login-user").click(function(){
  aa.dialog( "open" );
});

$(".deal").click(function(){
    game_Deal();
});

$(".insurance").click(function(){
  console.log(betAmount);
  game_Insurance();
});

$(".spilt").click(function(){
  var $touch=$(".t1").text(),
    touchEvent = $touch.split('SUM:');
  console.log($touch);
  console.log(touchEvent);
  game_Spilt();
});

$(".double").click(function(){
  var $touch=$(".t1").text(),
      touchEvent = $touch.split('SUM:');
  console.log($touch);
  console.log(touchEvent);

  game_Hit();
  $(".stand").trigger("click");
});

$(".hit").click(function(){
  game_Hit();
});

$(".stand").click(function(){
  game_Stand();
  $('#game').hide();
  $('.deal').show();
});
//login檢查
var loginCheck = function(list) {
  $.ajax({
    url: BASE_URL + "/loginCheck",
    type: "POST",
    dataType: "JSON",
    data: list,
    success: function(response) {
      if (response.status!= false) {
        $('#hello').html('');
        $('#hello').append('Hello,'+response.status['name']+'! You have $'+response.status['money']+'!');
      } else {
        alert('login error! Please try agin!');
      }
    },
    error: function () {
    }
  })
}

var gameA = function (response) {
  if (!response.status['a'][1]) {
    response.status['a'][1]="<img width='28' src='public/files/poker.jpg'>";
  }
  var $table = $('<table></table>');
  for (var key in response.status ) {
    if (/a/.exec(key)) {
      var $Tr = $('<tr></tr>');
      for (var i in response.status[key] ) {
        if (/sum/.exec(i)) {
          var $Td2 = $('<td class="t1">SUM:'+response.status[key][i]+'</td>');
          $Tr.append($Td2);
          $table.append($Tr);
        } else if (/[0-9]/.exec(i)) {
          var $Td1 = $('<td>'+response.status[key][i]+'</td>');
          $Tr.append($Td1);
          $table.append($Tr);
        }
      }
    }
    $('#gameA').html('');
    if (response.status.result) {
      $('#gameA').append("<style=>"+response.status.result+"<br><br>");
    }
    $('#gameA').append($table);
  }
}

var gameB = function (response) {
  var $table = $('<table></table>');
  for (var key in response.status ) {
    if (/b/.exec(key)) {
      var $Tr = $('<tr></tr>');
      for (var i in response.status[key] ) {
        if (/sum/.exec(i)) {
          var $Td2 = $('<td class="t1">SUM:'+response.status[key][i]+'</td>');
          $Tr.append($Td2);
          $table.append($Tr);
        } else if (/[0-9]/.exec(i)) {
          var $Td1 = $('<td>'+response.status[key][i]+'</td>');
          $Tr.append($Td1);
          $table.append($Tr);
        }
        $('#gameB').html('');
        $('#gameB').append($table);
      }
    }
  }
}
//game_新局
var game_Deal = function() {
  $.ajax({
    url: BASE_URL + "/game_Deal",
    dataType: "JSON",
    type: "get",
    success: function (response) {
      console.log(response.status);
      $('#game').show();
      $('.deal').hide();
      gameA(response);
      gameB(response);
      if (response.status.b['sum']==21) {
        //是執行.stand的click事件
        $(".stand").trigger("click");
      }
    }
  })
}
//game_保險
var game_Insurance = function(betAmount) {
  $.ajax({
    url: BASE_URL + "/game_Insurance",
    type: "GET",
    dataType: "JSON",

    success: function (response) {
    }
  })
}
//game_分牌
var game_Spilt = function() {
  $.ajax({
    url: BASE_URL + "/game_Spilt",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      gameB(response);
    }
  })
}
//game_加牌
var game_Hit = function() {
  $.ajax({
    url: BASE_URL + "/game_Hit",
    type: "GET",
    dataType: "JSON",
    data: {i:"b"} ,
    success: function (response) {
      gameB(response);
      if (response.status.b['sum']>21) {
        //是執行.stand的click事件
        $(".stand").trigger("click");
      }
    }
  })
}
//game_送出
var game_Stand = function() {
  $.ajax({
    url: BASE_URL + "/game_Stand",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      gameA(response);
    }
  })
}
