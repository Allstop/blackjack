//全域變數
var BASE_URL = location.protocol + '//' + location.hostname;

$(".submit_fold").click(function(){
  game_Fold();
});
$(".submit_hit").click(function(){
  game_Hit();
});
$(".submit_reset").click(function(){
  game_Reset();
});
//新局
var createDeck = function() {
  $.ajax({
    url: BASE_URL + "/createDeck",
    dataType: "JSON",
    type: "get",
    success: function (response) {
      $('#game').html('');
      $('#game').append("<img width='20' src='public/files/poker.jpg'>"+" "+response.status['a'][2]+"<br><br><br>");
      game_Sum(response.status);
    }
  })
}
//重開新局
var game_Reset = function() {
  $.ajax({
    url: BASE_URL + "/game_Reset",
    success: function () {
      createDeck();
    }
  })
}
//game_Sum
var game_Sum = function(data) {
  $.ajax({
    url: BASE_URL + "/game_Sum",
    type: "POST",
    dataType: "JSON",
    data: data,
    success: function (response) {
      $('#game').append(response.status.output['b']);
      $('#game').append("&nbsp;&nbsp;&nbsp;SUM:"+response.status.sumValue['b']);
    }
  })
}
//game_Hit
var game_Hit = function() {
  $.ajax({
    url: BASE_URL + "/game_Hit",
    type: "GET",
    dataType: "JSON",
    data: {i:"b"} ,
    success: function (response) {
      $('#game').html('');
      $('#game').append("<img width='20' src='public/files/poker.jpg'>"+" "+response.status['a'][2]+"<br><br><br>");
      game_Sum(response.status);
    }
  })
}
//game_Fold
var game_Fold = function() {
  $.ajax({
    url: BASE_URL + "/game_Fold",
    type: "GET",
    dataType: "JSON",
    success: function (response) {
      $('#game').html('');
      $('#game').append("<style=>"+response.status.ans+"<br><br>");
      $('#game').append(response.status.output);
      $('#game').append("&nbsp;&nbsp;&nbsp;SUM:"+response.status.sumValue+"<br>");
      game_Sum(response.status);

    }
  })
}

createDeck();