//全域變數
var BASE_URL = location.protocol + '//' + location.hostname;

$('#new_game').hide();
$('#game').hide();

$(".submit_deal").click(function(){
  game_Deal();
  $('#game').show();
  $('#new_game').show();
  $('.submit_deal').hide();
});

$(".submit_insurance").click(function(){
  game_Insurance();
});

$(".submit_spilt").click(function(){
  game_Spilt();
});

$(".submit_double").click(function(){
  game_Double();
});

$(".submit_hit").click(function(){
  game_Hit();
});

$(".submit_stand").click(function(){
  game_Stand();
  $('#new_game').hide();
  $('.submit_deal').show();
});

var game = function (response) {
  response.status['a'][1]="<img width='28' src='public/files/poker.jpg'>";
  $('#game').html('');
  var $table = $('<table></table>');;
  for (var key in response.status ) {
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
      $('#game').append($table);
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
      game(response);
    }
  })
}
//game_保險
var game_Insurance = function() {
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
    }
  })
}
//game_雙倍
var game_Double = function() {
  $.ajax({
    url: BASE_URL + "/game_Double",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
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
      game(response);
      if (response.status.b['sum']>21) {
        alert("Boom! You lose!");
        game_Stand();
        $('#new_game').hide();
        $('.submit_deal').show();
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
      $('#game').html('');
      $('#game').append("<style=>"+response.status.ans+"<br><br>");
      $('#game').append(response.status.output);
      $('#game').append("&nbsp;&nbsp;&nbsp;SUM:"+response.status.sumValue+"<br>");
    }
  })
}
