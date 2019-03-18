<!DOCTYPE html>
<html lang="uk">
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="shortcut icon" href="/qrcode.ico" type="image/x-icon" />
  <link rel="icon" href="/qrcode.ico" type="image/x-icon" />
  <title>HELLO</title>
  <style>
    table.t {
      border-collapse: collapse;
    }
    table.t td, table.t th {
      border-bottom: 1px solid grey;
      border-right: 1px solid grey;
    }
    table.t tr:first-child td, table.t tr:first-child th {
      border-top: 1px solid grey;
    }
    table.t td:first-child, table.t th:first-child {
      border-left: 1px solid grey;
    }
    
    .dg-head-column-sort, .dg-head-column-filter {
      text-align: center;
    }

  </style>
  <script src="/a.js?n=1"></script>
</head>
<body>
  <div id="content"></div>
  
  <script>
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
    function DataGrid(oParams){
      this.aProps = ["hO", 
        "htmlHeadRowTemplate",
        "htmlHeadColumn1Template",
        "htmlHeadColumn2Template",
        "htmlBodyRowTemplate",
        "htmlBodyCellTemplate",
        "oData",
        "id"];
      this.htmlHeadRowTemplate = ''
        +'<tr class="dg-head-row-1">'
        +'{{headColumns1}}'
        +'</tr>'+"\n"
        +'<tr class="dg-head-row-2">'
        +'{{headColumns2}}'
        +'</tr>'+"\n";
      this.htmlHeadColumn1Template = ''
        +'<th class="dg-head-column-1" colspan="2" id="{{colId}}">'
        +'{{columnOutName}}'
        +'</th>'+"\n";
      this.htmlHeadColumn2Template = ''
        +'<td class="dg-head-column-filter">'
        +'<a href="#" title="Фільтрація" id="{{colFilterId}}">[ ]</a>'
        +'</td>'+"\n"
        +'<td class="dg-head-column-sort">'
        +'<a href="#" title="Сортування" id="{{colSortId}}">[~]</a>'
        +'</td>'+"\n";
      this.htmlBodyRowTemplate = ''
        +'<tr class="dg-body-row">'
        +'{{bodyCells}}'
        +'</tr>'+"\n";
      this.htmlBodyCellTemplate = ''
        +'<td class="dg-body-cell" colspan="2" id="{{cellId}}">'
        +'{{cellValue}}'
        +'</td>'+"\n";
      DataGrid.lastId++;
      this.id = "DG_"+DataGrid.lastId;
      this.init(oParams);
      this.render();
    }
    
    DataGrid.lastId = 0;
    
    DataGrid.prototype.init = function(oParams){
      if (typeof(oParams) === "object"){
        for (var i = 0; i < this.aProps.length; i++){
          for (var p in oParams){
            if (p === this.aProps[i] 
                && oParams[p] !== undefined){
              this[p] = oParams[p];
            }
          }
        }
      }
    };
    
    DataGrid.prototype.checkColWidth = function(){
      var self = this;
      setTimeout(function(){
        var els = document
          .querySelectorAll("table#"+self.id+" th.dg-head-column-1");
        for (var i = 0; i < els.length; i++){
          //console.log(els[i].offsetWidth);
          if(els[i].offsetWidth > 100){
            els[i].style.width = "100px";
          }
        }
      },200);
    };
    
    DataGrid.prototype.render = function(){
      if (this.oData 
          && this.oData.struct
          && this.oData.struct.length){
        var nL = this.oData.struct.length;
        var cHtmlHead = "";
        var cHtmlBody = "";
        var cHtmlHeadColmns1 = "";
        var cHtmlHeadColmns2 = "";
        for (var i = 0; i < nL; i++){
          var oS = this.oData.struct[i];
          var cH1 = this.htmlHeadColumn1Template
            .replace("{{colId}}","col_"+(i+1)+"_"+oS.name)
            .replace("{{columnOutName}}",oS.outName);
          var cH2 = this.htmlHeadColumn2Template
            .replace("{{colFilterId}}","col_"+(i+1)+"_"+oS.name+"_filter")
            .replace("{{colSortId}}","col_"+(i+1)+"_"+oS.name+"_sort");
          cHtmlHeadColmns1 += cH1;
          cHtmlHeadColmns2 += cH2;
        }
        cHtmlHead = this.htmlHeadRowTemplate
          .replace("{{headColumns1}}",cHtmlHeadColmns1)
          .replace("{{headColumns2}}",cHtmlHeadColmns2);
        var nDL = this.oData.data.length;
        for (var i = 0; i < nDL; i++){
          var cHtmlBodyRow = "";
          var oD = this.oData.data[i];
          for (var j = 0; j < nL; j++){
            var oS = this.oData.struct[j];
            var htmlVal = "";
            if (oS.type === "string" || oS.type === "number"){
              htmlVal = oD[oS.name].toString();
            }
            if (oS.type === "boolean"){
              htmlVal = '<input type="checkbox" disabled '
              +((oD[oS.name])? "checked":"")
              +' />';
            }
            if (oS.type === "date" 
                && (oD[oS.name] instanceof Date)){
              var dDate = oD[oS.name];
              htmlVal = DateStr(dDate);
            }
            cHtmlBodyRow += this.htmlBodyCellTemplate
              .replace("{{cellValue}}", htmlVal)
              .replace("{{cellId}}","col_"+(j+1)+"_"+oS.name+"_"+(i+1));
          }
          cHtmlBody += this.htmlBodyRowTemplate
            .replace("{{bodyCells}}", cHtmlBodyRow);
        }
        //console.log(cHtmlHead);
        var cHtml = '<table class="t" id="'+this.id+'">'
        + '<thead>'
        + cHtmlHead
        + '</thead>'
        + '<tbody>'
        + cHtmlBody
        + '</tbody>'
        + '</table>';
        hO.innerHTML = cHtml;
        this.checkColWidth();
      }
    };
    
    var hO = document.getElementById("content");
    window.dg = new DataGrid({
      hO: hO,
      oData: window.data
    });
  </script>
</body>
</html>