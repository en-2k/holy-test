<!DOCTYPE html>
<html lang="uk">
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="shortcut icon" href="/qrcode.ico" type="image/x-icon" />
  <link rel="icon" href="/qrcode.ico" type="image/x-icon" />
  <title>Chatik</title>
  <style>
    .main-container {
      height: 420px; 
      width: 100%; 
      background-color: #DDDDDD;
    }
    
    .container-left {
      width: 30%; 
      height: 420px; 
      background-color: #EEEEFF; 
      float: left;
    }
    
    .container-left-top {
      width: 100%; 
      height: 40px; 
      background-color: #E0E0FF;
    }
    
    .container-search {
      width: 90%; 
      margin: 0 auto; 
      padding: 0; 
      height: 60%;
    }
    
    .field-search {
      width: 100%;
      height: 20px; 
      border: 0; 
      margin: 0; 
      margin-top: 10px; 
      padding: 0;
    }
    
    .contact-item {
      width: 99%; 
      height: 59px; 
      background-color: #EEEEFF; 
      border: 0; 
      border-bottom: 1px solid #DDDDDD; 
      text-align: left;
    }
    
    .contact-current {
      font-weight: bold;
    }
    
    .contact-list {
      width: 100%; 
      height: 380px; 
      background-color: #EEEEFF; 
      overflow: auto;
    }
    
    .container-right {
      width: 70%; 
      height: 420px; 
      overflow: auto; 
      background-color: #DDDDDD;
    }
    
    .container-right-top {
      height: 40px; 
      overflow: auto; 
      background-color: #D0D0EE;
    }
    
    .container-msg {
      height: 310px; 
      overflow: auto; 
      background-color: #DDDDDD;
    }
    
    .msg {
      width:50%; 
    }
    
    .msg-left {
      width:50%; 
      background-color: lightblue;
    }
    
    .msg-right {
      background-color: lightgreen;
    }

    .msg-unseen {
      border-right: 2px solid blue;
    }

    .msg-pending {
      border-left: 2px solid green;
    }

    .container-right-top {
      text-align: center;
      padding-top: 10px;
      height: 30px; 
    }
    
    .container-right-bottom {
      height: 70px; 
      overflow: auto; 
      background-color: #D0D0FF;
    }
    
    .container-msg-write {
      width: 85%; 
      height: 60px; 
      padding: 0; 
      margin: 0; 
      float: left; 
      margin-top: 5px; 
      text-align: center;
    }
    
    .textarea-msg {
      width: 97%; 
      border: 0; 
      padding: 0; 
      margin: 0; 
      height: 100%;
      overflow: auto;
    }
    
    .container-send-msg {
      width: 15%; 
      height: 60px; 
      padding: 0; 
      margin: 0; 
      float: left; 
      margin-top: 5px;
    }
    
    .button-send-msg {
      margin: 0; 
      padding: 0; 
      width: 100%; 
      height: 100%; 
      word-break: break-all;
    }

    table.messages {
      width: 100%;
      border: 0;
      border-spacing: 5px;
    }

    .hidden {
      display: none;
    }

  </style>
</head>
<body>
  <div class="main-container">
    <div class="container-left">
      <div class="container-left-top">
        <div class="container-search">
          <input type="text" placeholder="Поиск..." class="field-search" />
        </div>
      </div>
      <div class="contact-list">
        <button class="contact-item" >
        Друг 1
        </button>
        <button class="contact-item" >
        Друг 2
        </button>
        <button class="contact-item" >
        Друг 3
        </button>
        <button class="contact-item" >
        Друг 4
        </button>
        <button class="contact-item" >
        Друг 5
        </button>
        <button class="contact-item" >
        Друг 6
        </button>
        <button class="contact-item" >
        Друг 7
        </button>
      </div>
    </div>
    <div class="container-right">
      <div class="container-right-top">
        Уже почти рабочий... Йопта! <a href="?u=2">А что видит Трололоша?</a>
      </div>
      <div class="container-msg"></div>
      <div class="container-right-bottom">
        <div class="container-msg-write">
          <textarea class="textarea-msg"></textarea>
        </div>
        <div class="container-send-msg">
          <button class="button-send-msg">Отправить</button>
        </div>
      </div>    
    </div>
  </div>
  <script>
    window.user_id = <?php echo (isset($_GET['u'])? intval($_GET['u']):1); ?>;
    if (!document.querySelectorAll)
    document.querySelectorAll = function(selector){
      var head = document.documentElement.firstChild;
      var styleTag = document.createElement("STYLE");
      head.appendChild(styleTag);
      document.__qsResult = [];
      styleTag.styleSheet.cssText = selector 
        + "{x:expression(document.__qsResult.push(this))}";
      window.scrollBy(0, 0);
      head.removeChild(styleTag);
      var result = [];
      for (var i in document.__qsResult)
        result.push(document.__qsResult[i]);
      return result;
    }
    function trim(cStr){
      var cVal = cStr;
      if (typeof(cVal) !== "string"){
        return "";
      }
      return cVal.replace(/\s+$/,"").replace(/^\s+/,"");
    }
    function padL(cStr,nSize,cPad){
      var cVal = cStr;
      var clPad = "0"; 
      if (typeof(cStr) === "number"){
        cVal = cStr.toString();
      }
      if (typeof(cVal) !== "string"){
        return "";
      }
      if (typeof(nSize) !== "number" || isNaN(nSize) || nSize <= 0){
        return cVal;
      }
      if (typeof(cPad) === "string" && cPad.length > 0){
        clPad = cPad;
      }
      for (var i = 0; i < nSize - cVal.length; i++){
        cVal = clPad + cVal;
      }
      return cVal;
    }
    function DateStr(dDate){
      var cVal = "";
      if ((dDate instanceof Date) 
          && dDate.toString() !== "Invalid Date"
          && dDate.toString() !== "NaN" ){
        cVal = padL( dDate.getDate(), 2 )
          + '.' + padL( (dDate.getMonth()+1), 2 )
          + '.' + dDate.getFullYear();
      }
      if (cVal === "30.11.1899"){
        cVal = "";
      }
      return cVal;
    }
    function getScrollPercent() {
      var h = document.documentElement, 
          b = document.body,
          st = 'scrollTop',
          sh = 'scrollHeight';
      return (h[st]||b[st]) / ((h[sh]||b[sh]) - h.clientHeight) * 100;
    }
    if (!XMLHttpRequest.DONE){
      XMLHttpRequest.DONE = 4;
    }
    function xpost(cUrl,aParams,fCallback){
      var xhr= new XMLHttpRequest(); 
      xhr.open("POST", cUrl, true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
      xhr.onreadystatechange = function() {
        if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
          var o = null;
          try { o = JSON.parse(xhr.responseText); } catch(e) { o = null; }
          if (!(typeof(fCallback) === "function")){
            if (o === null){
              alert(xhr.responseText);
            } else {
              alert(o);
            }
          } else {
            fCallback(o);
          }
        } else if (xhr.readyState == XMLHttpRequest.DONE){
          alert(xhr.status);
        }
      };
      var cParams = "_="+(new Date()).getTime().toString();
      for (var i=0; i<aParams.length; i++){
        if (aParams[i].name && aParams[i].value){
          cParams += "&"+encodeURIComponent(aParams[i].name.toString());
          if (aParams[i].json){
            cParams += "="+encodeURIComponent(JSON.stringify(aParams[i].value));
          } else {
            cParams += "="+encodeURIComponent(aParams[i].value.toString());
          }
        }
      }
      xhr.send(cParams);
    }
    function setCookie(cName, cValue, nExdays) {
      var d = new Date();
      d.setTime(d.getTime() + (nExdays*24*60*60*1000));
      var expires = "expires="+ d.toUTCString();
      document.cookie = cName + "=" + cValue + ";" + expires + ";path=/";
    }

    function getCookie(cName) {
      var name = cName + "=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var ca = decodedCookie.split(';');
      for(var i = 0; i < ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) == ' ') {
              c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
          }
      }
      return "";
    }
    function delNode(node){
      for(var i = 0; i < node.childNodes.length; i++){
        delNode(node.childNodes[0]);
      }
      node.parentNode.removeChild(node);
    }
    function isVisibleInViewport(elem){
      var y = elem.offsetTop;
      var height = elem.offsetHeight;
      while ( elem = elem.offsetParent )
          y += elem.offsetTop;
      var maxHeight = y + height;
      var isVisible = ( y < ( window.pageYOffset + window.innerHeight ) ) 
          && ( maxHeight >= window.pageYOffset );
      return isVisible; 

    }
    function reCount(room_id){
      var aTds = document.querySelectorAll("table#room_"
          + room_id
          + " td.msg.msg-left.msg-unseen");
      var aIndexes = [];
      var inCnt = 0;
      for (var i = 0; i < aTds.length; i++){
        var nPos = aTds[i].getBoundingClientRect().top
          -aTds[i].parentNode.parentNode
                  .parentNode.parentNode
                  .getBoundingClientRect().top
          +aTds[i].offsetHeight/2.5;
        if(nPos>0 && room_id === window.room_id){
          aIndexes.push(i);
        }
      }
      inCnt = aTds.length - aIndexes.length;
      var objB = document.getElementById("btn__"+room_id);
      var oc = document.getElementById('cnt_+'+room_id);
      if (!oc && inCnt > 0){
        objB.innerHTML = objB.innerHTML
          +' <span id="cnt_+'+room_id+'">('+inCnt+')</span>';
      } else if(oc) {
        oc.innerHTML = '('+inCnt+')';
      }
      if (inCnt <= 0 && oc){
        delNode(oc);
      }
      for (var i = 0; i < aIndexes.length; i++){
        aTds[aIndexes[i]].className = "msg msg-left";
      }
    };
    function renderAllMsgs(){
      var oMContainer = document.querySelectorAll("div.container-msg")[0];
      for (var i = 0; i < window.rooms.length; i++){
        var oTTMessages = document.createElement("table");
        oTTMessages.id = "room_"+window.rooms[i].room_id;
        oTTMessages.className = "messages hidden";
        var oTMessages = document.createElement("tbody");
        for (var j in  window.rooms[i].msg){
          var m = window.rooms[i].msg[j];
          if (!m.when){continue;}
          var oTr = document.createElement("TR");
          var oTd1 = document.createElement("TD");
          var oTd2 = document.createElement("TD");
          if (m.user_id === window.user_id){
            oTd2.className = "msg msg-right";
            oTd2.title = m.when;
            oTd2.innerHTML = m.text;
            oTd1.className = "msg";
            oTr.appendChild(oTd1);
            oTr.appendChild(oTd2);
          } else {
            oTd1.className = "msg msg-left msg-unseen";
            oTd1.title = m.user
              + ' / ' + m.when;
            oTd1.innerHTML = m.text;
            oTd2.className = "msg";
            oTr.appendChild(oTd1);
            oTr.appendChild(oTd2);
          }
          oTMessages.appendChild(oTr);
        }
        oTTMessages.appendChild(oTMessages);
        oMContainer.appendChild(oTTMessages);
      }
      document.getElementById("room_"+window.room_id)
              .className = "messages";
      setTimeout(function(){
        var objDiv = document.querySelectorAll(".container-msg")[0];
        objDiv.scrollTop = objDiv.scrollHeight;
        setTimeout(function(){
          for (var i = 0; i < window.rooms.length; i++){
            reCount(window.rooms[i].room_id);
          }
          
        },50);
        var inte = setInterval(function(){
          reCount(window.room_id);
        },1500);
      },100);

    }
    window.onload = function(){
      var oBtnSend = document.querySelectorAll("button.button-send-msg")[0];
      oBtnSend.onclick = function(){
        var eventObject;
        var eventTarget;
        if (window.event) {
          eventObject = window.event;
          eventTarget = window.event.srcElement;
        }
        else {
          eventObject = event;
          eventTarget = event.target;
        }
        var oTxt = document.querySelectorAll("textarea.textarea-msg")[0];
        var oTr = document.createElement("TR");
        var oTd1 = document.createElement("TD");
        var oTd2 = document.createElement("TD");
        oTd2.className = "msg msg-right msg-pending";
        oTd2.title = "";
        oTd2.innerHTML = oTxt.value;
        oTd1.className = "msg";
        oTr.appendChild(oTd1);
        oTr.appendChild(oTd2);
        document.querySelectorAll("table#room_"
          + window.room_id+" tbody")[0].appendChild(oTr);
        oBtnSend.setAttribute("disabled","disabled");
        var objDiv = document.querySelectorAll(".container-msg")[0];
        objDiv.scrollTop = objDiv.scrollHeight;
        xpost("post.php",
            [ {name: 'user_id', value: window.user_id},
              {name: 'room_id', value: window.room_id},
              {name: 'text', value: oTxt.value}
            ],
        function(o){
          if (o === "ok"){
            setTimeout(function(){
              oBtnSend.removeAttribute("disabled");
              oTd2.className = "msg msg-right";
              oTxt.value = "";
            },100);
          }
        });
        return false;
      };

      xpost("get.php",
          [{name: 'user_id', value: window.user_id}],
      function(oResult){
        var oCList = document.querySelectorAll("div.contact-list")[0];
        for (var i = 0; oCList.childNodes.length > 0; i++){
          delNode(oCList.childNodes[0]);
        }
        for (var i = 0; i < oResult.rooms.length; i++){
          var oButton = document.createElement("BUTTON");
          oButton.className = "contact-item";
          oButton.id = "btn__"+oResult.rooms[i].room_id;
          oButton.setAttribute("room_id",oResult.rooms[i].room_id);
          oButton.innerHTML = oResult.rooms[i].room_name;
          if (i === 0 && typeof(window.room_id) === 'undefined'){
            window.room_id = oResult.rooms[i].room_id;
            oButton.className = oButton.className + " contact-current";
          } else if(oResult.rooms[i].room_id === window.room_id){
            oButton.className = oButton.className + " contact-current";
          }
          //переключалка между далогами (комнатами)
          oButton.onclick = function(event){
            var eventObject;
            var eventTarget;
            if (window.event) {
              eventObject = window.event;
              eventTarget = window.event.srcElement;
            }
            else {
              eventObject = event;
              eventTarget = event.target;
            }
            var nRoomId = parseInt(eventTarget.getAttribute('room_id'),10);
            if (nRoomId === window.room_id || window.blocked || isNaN(nRoomId)){
              return false;
            }
            window.blocked = true;
            setTimeout(function(){
              var objDiv = document.querySelectorAll(".container-msg")[0];
              var nScro = objDiv.scrollTop;
              var oLastB = document.querySelectorAll(".contact-item.contact-current")[0];
              oLastB.setAttribute("last-scroll",nScro);
              var oCList = document.querySelectorAll("div.contact-list")[0];
              for (var i = 0; i < oCList.childNodes.length; i++){
                oCList.childNodes[i].className = "contact-item";
              }
              eventTarget.className = "contact-item contact-current";
              window.room_id = parseInt(eventTarget.getAttribute('room_id'),10);
              var oMContainer 
                = document.querySelectorAll("div.container-msg")[0];
              for (var i = 0; i < oMContainer.childNodes.length; i++){
                oMContainer.childNodes[i].className = "messages hidden";
              }
              document.getElementById("room_"+window.room_id)
                .className = "messages";
              nScro = parseFloat(eventTarget.getAttribute('last-scroll'),10);
              if (isNaN(nScro) || typeof(nScro) !== "number"){
                objDiv.scrollTop = objDiv.scrollHeight;
              } else {
                objDiv.scrollTop = nScro;
              }
              window.blocked = false;
            },50);
            return false;
          }
          oCList.appendChild(oButton);
        }
        window.rooms = oResult.rooms;
        renderAllMsgs();
      });
    };
    
    
  </script>
</body>
</html>