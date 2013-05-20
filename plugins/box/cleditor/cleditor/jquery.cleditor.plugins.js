/*
 CLEditor Advanced Table Plugin v1.0.0
 http://premiumsoftware.net/cleditor
 requires CLEditor v1.2.2 or later

 Copyright 2010, Sergio Drago
 Dual licensed under the MIT or GPL Version 2 licenses.

 Based on Chris Landowski's Table Plugin v1.0.2
*/
(function(c){c.cleditor.buttons.table={name:"table",image:"table.gif",title:"Insert Table",command:"inserthtml",popupName:"table",popupClass:"cleditorPrompt",popupContent:'<table cellpadding=0 cellspacing=0><tr><td style="padding-right:6px;">Cols:<br /><input type=text value=4 size=12 /></td><td style="padding-right:6px;">Rows:<br /><input type=text value=4 size=12 /></td></tr><tr><td style="padding-right:6px;">Cell Spacing:<br /><input type=text value=2 size=12 /></td><td style="padding-right:6px;">Cell Padding:<br /><input type=text value=2 size=12 /></td></tr><tr><td style="padding-right:6px;">Border:<br /><input type=text value=1 size=12 /></td><td style="padding-right:6px;">Style (CSS):<br /><input type=text size=12 /></td></tr></table><br /><input type=button value=Submit  />',
buttonClick:function(l,d){c(d.popup).children(":button").unbind("click").bind("click",function(){var j=d.editor,a=c(d.popup).find(":text"),e=parseInt(a[0].value),f=parseInt(a[1].value),g=parseInt(a[2].value),h=parseInt(a[3].value),i=parseInt(a[4].value),k=a[5].value;if(parseInt(e)<1||!parseInt(e))e=0;if(parseInt(f)<1||!parseInt(f))f=0;if(parseInt(g)<1||!parseInt(g))g=0;if(parseInt(h)<1||!parseInt(h))h=0;if(parseInt(i)<1||!parseInt(i))i=0;var b;if(e>0&&f>0){b="<table border="+i+" cellpadding="+h+" cellspacing="+
g+(k?' style="'+k+'"':"")+">";for(y=0;y<f;y++){b+="<tr>";for(x=0;x<e;x++)b+="<td>"+x+","+y+"</td>";b+="</tr>"}b+="</table><br />"}b&&j.execCommand(d.command,b,null,d.button);a[0].value="4";a[1].value="4";a[2].value="2";a[3].value="2";a[4].value="1";a[5].value="";j.hidePopups();j.focus()})}};c.cleditor.defaultOptions.controls=c.cleditor.defaultOptions.controls.replace("rule ","rule table ")})(jQuery);

/*
 CLEditor XHTML Plugin v1.0.0
 http://premiumsoftware.net/cleditor
 requires CLEditor v1.3.0 or later

 Copyright 2010, Chris Landowski, Premium Software, LLC
 Dual licensed under the MIT or GPL Version 2 licenses.

 Based on John Resig's HTML Parser Project (ejohn.org)
 http://ejohn.org/files/htmlparser.js
 Original code by Erik Arvidsson, Mozilla Public License
 http://erik.eae.net/simplehtmlparser/simplehtmlparser.js
*/
(function(k){var n=k.cleditor.defaultOptions.updateTextArea;k.cleditor.defaultOptions.updateTextArea=function(a){if(n)a=n(a);return k.cleditor.convertHTMLtoXHTML(a)};k.cleditor.convertHTMLtoXHTML=function(a){function i(e){var b={};e=e.split(",");for(var f=0;f<e.length;f++)b[e[f]]=true;return b}function v(e,b,f,h){b=b.toLowerCase();if(w[b])for(;c.last()&&x[c.last()];)j("",c.last());y[b]&&c.last()==b&&j("",b);(h=z[b]||!!h)||c.push(b);var l=[];f.replace(A,function(D,m,o,p,q){l.push({name:m,escaped:(o?
o:p?p:q?q:B[m]?m:"").replace(/(^|[^\\])"/g,'$1\\"')})});g+="<"+b;for(e=0;e<l.length;e++)g+=" "+l[e].name+'="'+l[e].escaped+'"';g+=(h?"/":"")+">"}function j(e,b){if(b){b=b.toLowerCase();for(f=c.length-1;f>=0;f--)if(c[f]==b)break}else var f=0;if(f>=0){for(var h=c.length-1;h>=f;h--)g+="</"+c[h]+">";c.length=f}}function r(e,b){g=g.replace(e,b)}var s=/^<(\w+)((?:\s+\w+(?:\s*=\s*(?:(?:"[^"]*")|(?:'[^']*')|[^>\s]+))?)*)\s*(\/?)>/,t=/^<\/(\w+)[^>]*>/,A=/(\w+)(?:\s*=\s*(?:(?:"((?:\\.|[^"])*)")|(?:'((?:\\.|[^'])*)')|([^>\s]+)))?/g,
z=i("area,base,basefont,br,col,frame,hr,img,input,isindex,link,meta,param,embed"),w=i("address,applet,blockquote,button,center,dd,del,dir,div,dl,dt,fieldset,form,frameset,hr,iframe,ins,isindex,li,map,menu,noframes,noscript,object,ol,p,pre,script,table,tbody,td,tfoot,th,thead,tr,ul"),x=i("a,abbr,acronym,applet,b,basefont,bdo,big,br,button,cite,code,del,dfn,em,font,i,iframe,img,input,ins,kbd,label,map,object,q,s,samp,script,select,small,span,strike,strong,sub,sup,textarea,tt,u,var"),y=i("colgroup,dd,dt,li,options,p,td,tfoot,th,thead,tr"),
B=i("checked,compact,declare,defer,disabled,ismap,multiple,nohref,noresize,noshade,nowrap,readonly,selected"),C=i("script,style"),c=[];c.last=function(){return this[this.length-1]};for(var d,u=a,g="";a;){if(!c.last()||!C[c.last()])if(a.indexOf("<!--")==0){d=a.indexOf("--\>");if(d>=0){g+=a.substring(0,d+3);a=a.substring(d+3)}}else if(a.indexOf("</")==0){if(d=a.match(t)){a=a.substring(d[0].length);d[0].replace(t,j)}}else if(a.indexOf("<")==0){if(d=a.match(s)){a=a.substring(d[0].length);d[0].replace(s,
v)}}else{d=a.indexOf("<");g+=d<0?a:a.substring(0,d);a=d<0?"":a.substring(d)}else{a=a.replace(RegExp("(.*)</"+c.last()+"[^>]*>"),function(e,b){b=b.replace(/<!--(.*?)--\>/g,"$1").replace(/<!\[CDATA\[(.*?)]]\>/g,"$1");g+=b;return""});j("",c.last())}if(a==u)throw"Parse Error: "+a;u=a}j();r(/<b>(.*?)<\/b>/g,"<strong>$1</strong>");r(/<i>(.*?)<\/i>/g,"<em>$1</em>");return g}})(jQuery);