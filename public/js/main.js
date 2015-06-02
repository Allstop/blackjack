//全域變數
var BASE_URL = location.protocol + '//' + location.hostname;
    betAmount=0;
    moneyVal='';

$(document).on("click","#submitBetAmount input",function(){
  var $touch=$(this).attr("class"),
      touchEvent = $touch.split(' ');
  betAmount =parseInt(betAmount)+parseInt(touchEvent[1]);
  moneyAmount = moneyVal-betAmount;
  if (moneyAmount<0) {
    alert("ERROR! You have no money!");
  } else {
    money();
    $('#betAmount').html("");
    $('#betAmount').append('下注金額：$'+betAmount+'<input type="submit" class="reset" value="reset">');
    $('#game').load( "public/game.html"), function() {}
  }
});

$(document).on("click",".reset",function(){
  betAmount=0;
  money();
  $('#betAmount').html("");
  $('#betAmount').append('下注金額：$'+betAmount+'<input type="submit" class="reset" value="reset">');

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

$(document).on("click","#logout",function(){
  logout();
});

$(document).on("click",".deal",function(){
  $('.submitBetAmount').hide();
  $('.reset').hide();
  game_Deal();

});

$(document).on("click",".insurance",function(){
  game_Insurance();
});

$(document).on("click",".spilt",function(){
  var $touch=$(".t1").text(),
    touchEvent = $touch.split('SUM:');
  console.log($touch);
  console.log(touchEvent);
  game_Spilt();
});

$(document).on("click",".double",function(){
  game_Double();
});

$(document).on("click",".hit",function(){
  game_Hit();
});

$(document).on("click",".stand",function(){
  game_Stand();
  $('.submitBetAmount').show();
  $('#game_submit').hide();
  $('.deal').show();
  $('.reset').show();
});

$(document).on("click",".wash",function(){
  game_Wash();
  $('#lastDeck').html('');
  $('#lastDeck').append(52);
});
//login檢查
var loginCheck = function(list) {
  $.ajax({
    url: BASE_URL + "/loginCheck",
    type: "POST",
    dataType: "JSON",
    data: list,
    success: function(response) {
      if (response.status == false) {
        aa.dialog( "open" );
      } else {
        $("#log").html('');
        $('#log').append('<button id="logout">登出</button>');
        $('#hello').html('');
        $('#hello').append('Hello,'+response.status['name']+'!');
        money();
        $('#submitBetAmount').load( "public/BetAmount.html");
      }
    },
    error: function () {
    }
  })
}

var money = function() {
  $.ajax({
    url: BASE_URL + "/money",
    type: "GET",
    dataType: "JSON",
    success: function(response) {
        moneyVal= response.status['money'];
        moneyAmount=moneyVal-betAmount;
        $('#money').html('');
        $('#money').append('You have $'+moneyAmount+'!');
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
      $('#gameA').append('<h2 style="color:red">'+response.status.result+'<br><br>');
    }
    $('#gameA').append($table);
  }
  $('#lastDeck').html('');
  $('#lastDeck').append(response.lastdeck);
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
  $('#lastDeck').html('');
  $('#lastDeck').append(response.lastdeck);
}
//game_新局
var game_Deal = function() {
  $.ajax({
    url: BASE_URL + "/game_Deal",
    dataType: "JSON",
    type: "get",
    success: function (response) {
      if (response.status == false) {
        alert("請先登入！");
      } else {
        $('#game_submit').show();
        $('.deal').hide();
        gameA(response);
        gameB(response);
        $('#game_submit').html('');
        if (response.status.b['sum']>20) {
          //是執行.stand的click事件
          $('#game_submit').append('<input type="submit" class="game stand" value="送出(Stand)">');
          $(".stand").trigger("click");
        } else {
          $('#game_submit').append('<input type="submit" class="game hit" value="發牌(Hit)">');
          $('#game_submit').append('<input type="submit" class="game double" value="雙倍(Double)">');
          if (response.status.a['num'][2] == 11) {
            $('#game_submit').append('<input type="submit" class="game insurance" value="保險(Insurance)">');
          }
          if (response.status.b['num'][1] == response.status.b['num'][2]) {
            $('#game_submit').append('<input type="submit" class="game spilt" value="分牌(Spilt)">');
          }
        }
        $('#game_submit').append('<input type="submit" class="game stand" value="送出(Stand)">');
      }
    }
  })
}
//game_保險
var game_Insurance = function() {
  $.ajax({
    url: BASE_URL + "/game_Insurance",
    type: "GET",
    dataType: "JSON",
    data: {Amount:betAmount*0.5} ,
    success: function (response) {
      if (response.status == false) {
        alert("false!");
      } else {
        $('.insurance').hide();
        money();
        if (response.status>0) {
          alert("You guss right，win $"+response.status+"!");
        } else {
          alert("You guss Wrong，lose $"+-response.status+"!");
        }
      }
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
      $('.spilt').hide();
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
      keys=[];
      for(var key in response.status.b['num']) keys.push(key);
      if (response.status.b['sum']>20 | keys.length>4) {
        //是執行.stand的click事件
        $(".stand").trigger("click");
      }
      $('#game_submit').html('');
      $('#game_submit').append('<input type="submit" class="game hit" value="發牌(Hit)">');
      $('#game_submit').append('<input type="submit" class="game stand" value="送出(Stand)">');
    }
  })
}
//game_Double
var game_Double = function() {
  $.ajax({
    url: BASE_URL + "/game_Hit",
    type: "GET",
    dataType: "JSON",
    data: {i:"b"},
    success: function (response) {
      $('.double').hide();
      gameB(response);
      betAmount = betAmount*2;
      $(".stand").trigger("click");
    }
  })
}
//game_送出
var game_Stand = function() {
  $.ajax({
    url: BASE_URL + "/game_Stand",
    type: "GET",
    dataType: "JSON",
    data: {Amount:betAmount},
    success: function (response) {
      gameA(response);
      money();
    }
  })
}

var game_Wash = function() {
  $.ajax({
    url: BASE_URL + "/game_Wash"
  })
}

var logout = function() {
  $.ajax({
    url: BASE_URL + "/logout",
    success: function () {
      $('#game').hide();
      $('.deal').hide();
      loginCheck();
    }
  })
}

loginCheck();
//登出,釋放session,清空username，回到初始畫面
//$("#log").html('');
//$('#log').append('<button id="login">登入</button>');
