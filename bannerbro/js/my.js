function ShowDel(type,whot)
	{

		//orders.php
		if (type==1) 
			{  
				document.getElementById('ShureDel').value = whot;
				document.getElementById('ShurePos').innerHTML = whot;
			}		
		//position.php
		if (type==2) 
			{  
				document.getElementById('DHLpos').value = jsarpos[whot];
				document.getElementById('DLpos').innerHTML = jsarpos[whot];
			}
		//moderator.php
		if (type==3) 
			{  
				document.getElementById('HLpos').value = jsarpos[whot];
			}			
		//document.getElementById('discount_pos').value = whot;
	}

function EditModer(whot)
	{	
		document.getElementById('DHLpos').value = jsarpos[whot];
	}


function ShowEdit(whot)
	{
		document.getElementById('Emin').value = jsarsizemin[whot];
		document.getElementById('Emax').value = jsarsizemax[whot];
		document.getElementById('Eprice').value = jsarprice[whot];
		document.getElementById('Etext').value = jsarabout[whot];
		document.getElementById('HLpos').value = jsarpos[whot];
		document.getElementById('enumber_place').value = jsarqueue[whot];
		document.getElementById('Lpos').innerHTML = jsarpos[whot];
		document.getElementById('EMyCode').innerHTML = jsarcode[whot];
		if (jsarcode[whot]!=''){ 
			document.getElementById("Ecode").checked = true; 
			document.getElementById("EMyBanner").disabled = true;
			document.getElementById("EMyUrl").disabled = true; 
			document.getElementById("EMyCode").disabled = false;
		} else {
			if (jspic[whot]!=''){ 
				document.getElementById("Eban").checked = true; 
				document.getElementById("EMyBanner").disabled = false;
				document.getElementById("EMyUrl").disabled = false; 
				document.getElementById("EMyCode").disabled = true;
			}else{
				document.getElementById("Eram").checked = true; 
				document.getElementById("EMyBanner").disabled = true;
				document.getElementById("EMyUrl").disabled = true; 
				document.getElementById("EMyCode").disabled = true;	
			}
		}

		
		if (jsarused[whot]==false)
			{
				document.getElementById('pos_used').innerHTML = "<span onclick=\"ChangeTypePay('Eselect','zapretday_edit','enumber_place')\">Выбор оплаты за <select name=\"Eselect\" id=\"Eselect\" required readonly><option>Клики</option><option>Дни</option><option>Показы</option></select></span></td>";
			} 
		else 
			{
				document.getElementById('pos_used').innerHTML = "<span onclick=\"ChangeTypePay('Eselect','zapretday_edit','enumber_place')\"><div style=\"display:none;\">Выбор оплаты за <select name=\"Eselect\" id=\"Eselect\" readonly><option>Клики</option><option>Дни</option><option>Показы</option></select></div></span></td>";
			}		
		document.getElementById('Eselect').value = jsarpay[whot];
	}
function ShowOrder(whot)
	{
		document.getElementById('order_url').value = jsorderurl[whot];
		document.getElementById('order_alt').value = jsorderalt[whot];
		document.getElementById('order_name').value = jsordername[whot];
		document.getElementById('order_mail').value = jsordermail[whot];
		document.getElementById('Orderpos').innerHTML = jsorderid[whot];
		document.getElementById('OrderID').value = jsorderid[whot];
		document.getElementById("ordbanner_1").checked = true;
		document.getElementById("OrderNewBanner").disabled = true;
	}		

function ShowDiscount(whot)
		{
			document.getElementById('discount_pos').value = whot;
		}
	 
function showbanner(type,whot,width,hight,url)
	{
			
		if (type==1) 
			{  
				document.getElementById('showbanner').innerHTML = '<span class="hidden-link" data-link="'+jsurl[url]+'"><img style="margin:0 auto;" src="/bannerbro/img/'+jspic[whot]+'"></span>';
			}
			
		if (type==2) 
			{  
				document.getElementById('showbanner').innerHTML = '<object type="application/x-shockwave-flash" data="/bannerbro/img/'+jspic[whot]+'" width="'+width+'" height="'+hight+'"><param name="movie" value="/bannerbro/img/'+jspic[whot]+'" /><param name="FlashVars" value="clickTAG='+jsurl[url]+'" /></object>';
			}	
			
		if (type==3) 
			{  
				document.getElementById('showbanner').innerHTML = '<span class="hidden-link" data-link="'+jsurlnew[url]+'"><img style="margin:0 auto;" src="/bannerbro/img/'+jspicnew[whot]+'"></span>';
			}
		
		if (type==4) 
			{  
				document.getElementById('showbanner').innerHTML = '<object type="application/x-shockwave-flash" data="/bannerbro/img/'+jspicnew[whot]+'" width="'+width+'" height="'+hight+'"><param name="movie" value="/bannerbro/img/'+jspicnew[whot]+'" /><param name="FlashVars" value="clickTAG='+jsurlnew[url]+'" /></object>';
			}
			
		if (type==5) 
			{  
				if (jsurl[url]!='')
					{
						document.getElementById('showbanner').innerHTML = '<span class="hidden-link" data-link="'+jsurl[url]+'"><img style="margin:0 auto;" src="/bannerbro/admin/img/'+jspic[whot]+'"></span>';
					} 
				else 
					{
						document.getElementById('showbanner').innerHTML = '<a href="/bannerbro"><img style="margin:0 auto;" src="/bannerbro/admin/img/'+jspic[whot]+'"></a>';
					}
			}
			
		if (type==6) 
			{  
				if (jsurl[url]!='')
					{ 
						document.getElementById('showbanner').innerHTML = '<object type="application/x-shockwave-flash" data="/bannerbro/admin/img/'+jspic[whot]+'" width="'+width+'" height="'+hight+'"><param name="movie" value="/bannerbro/admin/img/'+jspic[whot]+'" /><param name="FlashVars" value="clickTAG='+jsurl[url]+'" /></object>'; 
					} 
				else 
					{
						document.getElementById('showbanner').innerHTML = '<object type="application/x-shockwave-flash" data="/bannerbro/admin/img/'+jspic[whot]+'" width="'+width+'" height="'+hight+'"><param name="movie" value="/bannerbro/admin/img/'+jspic[whot]+'" /><param name="FlashVars" value="clickTAG=/bannerbro" /></object>';
					}
			}			
			
		$('.hidden-link').click(function(){window.open($(this).data('link'));return false;});
	}

	
function showday(whot)
	{
		document.getElementById('showday').innerHTML = jstableday[whot];
	}
		



function otkaz(type,whot,mail)
		{
		
		document.getElementById("badmail").value = mail
		document.getElementById("badpos").value = whot
		
		if (type==1) 
			{  
				document.getElementById('button_no').innerHTML = '<a href=\'orders.php?bad='+document.getElementById("badpos").value+'&mail='+document.getElementById("badmail").value+'&titletext='+document.getElementById("theme").value+'&text='+document.getElementById("reason").value+'\'> <input type="button" value="Отправить письмо" /></a>'
			}		

		if (type==2) 
			{  
				document.getElementById('button_no').innerHTML = '<a href=\'reban.php?new='+document.getElementById("badpos").value+'&mail='+document.getElementById("badmail").value+'&titletext='+document.getElementById("theme").value+'&text='+document.getElementById("reason").value+'\'> <input type="button" value="Отправить письмо" /></a>'
			}			

		}
		
function otkaztext(type)
		{
			if (type==1) 
				{  
					document.getElementById('button_no').innerHTML = '<a href=\'orders.php?bad='+document.getElementById("badpos").value+'&mail='+document.getElementById("badmail").value+'&titletext='+document.getElementById("theme").value+'&text='+document.getElementById("reason").value+'\'> <input type="button" value="Отправить письмо" /></a>'
				}		

			if (type==2) 
				{  
					document.getElementById('button_no').innerHTML = '<a href=\'reban.php?new='+document.getElementById("badpos").value+'&mail='+document.getElementById("badmail").value+'&titletext='+document.getElementById("theme").value+'&text='+document.getElementById("reason").value+'\'> <input type="button" value="Отправить письмо" /></a>'
				}					
		}

	
function otkaztext_mod(){
	document.getElementById('button_no_mod').innerHTML = '<a href=\'orders.php?bad='+document.getElementById("badpos_mod").value+'&mail='+document.getElementById("badmail_mod").value+'&titletext='+document.getElementById("theme_mod").value+'&text='+document.getElementById("reason_mod").value+'\'> <input type="button" value="Отправить письмо" /></a>'
	}	

function isright(obj)
{
if (obj.value>99) obj.value=15; 
}

// Сторона покупателя

function changeyourday(whot) {
document.getElementById('change_day').innerHTML='Позиция № '+whot;
  if(document.getElementById('yourday').style.display=='none') {
    document.getElementById('yourday').style.display = '';
	document.getElementById('something').style.display = 'none';
	document.getElementById('yourclick').style.display = 'none';
	document.getElementById('yourshows').style.display = 'none';
	document.getElementById('queue').style.display = 'none';
	document.getElementById('change_day').innerHTML='Позиция № '+whot;
  }
  return false;
}
function changeyourclick(whot) {
document.getElementById('change_click').innerHTML='Позиция № '+whot;
  if(document.getElementById('yourclick').style.display=='none') {
    document.getElementById('yourclick').style.display = '';
	document.getElementById('something').style.display = 'none';
	document.getElementById('yourday').style.display = 'none';
	document.getElementById('yourshows').style.display = 'none';
	document.getElementById('queue').style.display = 'none';
	document.getElementById('change_click').innerHTML='Позиция № '+whot;
  }
  return false;
}
function changeyourshows(whot) {
document.getElementById('change_shows').innerHTML='Позиция № '+whot;
  if(document.getElementById('yourshows').style.display=='none') {
    document.getElementById('yourshows').style.display = '';
	document.getElementById('something').style.display = 'none';
	document.getElementById('yourday').style.display = 'none';
	document.getElementById('yourclick').style.display = 'none';
	document.getElementById('queue').style.display = 'none';
	document.getElementById('change_shows').innerHTML='Позиция № '+whot;
  }
  return false;
}

function go_queue(whot) {
  if(document.getElementById('queue').style.display=='none') {
    document.getElementById('queue').style.display = '';
	document.getElementById('something').style.display = 'none';
	document.getElementById('yourday').style.display = 'none';
	document.getElementById('yourclick').style.display = 'none';
	document.getElementById('yourshows').style.display = 'none';
	document.getElementById('change_queue').innerHTML='Позиция № '+whot;
  }
  return false;
}

function skidclickshow(whot) {
	document.getElementById(whot).innerHTML='Сумма <span style="color:green;">скидка '+document.getElementById('discount_2').value+'%</span>'
	}

function skidhide(whot) {
	document.getElementById(whot).innerHTML='Сумма'
	}

function daysend() {
    if (document.getElementById('count').value=='0'){
        document.getElementById('dayerr').innerHTML='Заказ не сделан!';
        return false;
    };
};
function clicksend() {
    if (document.getElementById('priceclick').value=='0'){
        document.getElementById('clickserr').innerHTML='Заказ не сделан!';
        return false;
    };
};
function showssend() {
    if (document.getElementById('priceshows').value=='0'){
        document.getElementById('showsserr').innerHTML='Заказ не сделан!';
        return false;
    };
};
//ПОДСЧИТЫВАЕМ СУММУ ОПЛАТЫ И ВЫВОДИМ В ОКОШКО
function Show()
	{
	MyStringCount=document.getElementById('altField').value;
	crr = MyStringCount.split(',');
	skidhide('skidday');
	document.getElementById('count').value=crr.length*document.getElementById('dayHowcount').innerHTML;
		if (parseInt(document.getElementById('count').value, 10) > parseInt(document.getElementById('discount_1').value,10))
		{
		skidclickshow('skidday')
		document.getElementById('count').value=crr.length*document.getElementById('dayHowcount').innerHTML*(100-document.getElementById('discount_2').value)/100
		}
	if (MyStringCount==""){document.getElementById('count').value='0'; skidhide('skidday');}
	}
//ЗАКОНЧИЛИ//
function paymentclick()
	{
	skidhide('skidclick'); 
	document.getElementById('priceclick').value =document.getElementById('clickHowcount').innerHTML * document.getElementById('Myclick').value
		if (parseInt(document.getElementById('priceclick').value, 10) > parseInt(document.getElementById('discount_1').value,10)) 
		{
		skidclickshow('skidclick')
		document.getElementById('priceclick').value=document.getElementById('clickHowcount').innerHTML * document.getElementById('Myclick').value*(100-document.getElementById('discount_2').value)/100
		}
	}
	
function paymentshows()
	{
	skidhide('skidshow'); 
	document.getElementById('priceshows').value =document.getElementById('ShowsHowcount').innerHTML * document.getElementById('Myshows').value
		if (parseInt(document.getElementById('priceshows').value, 10) > parseInt(document.getElementById('discount_1').value,10)) 
		{
		skidclickshow('skidshow')
		document.getElementById('priceshows').value =document.getElementById('ShowsHowcount').innerHTML * document.getElementById('Myshows').value*(100-document.getElementById('discount_2').value)/100
		}
	}
function new_ram(){
	document.getElementById("MyBanner").disabled = true;
	document.getElementById("MyUrl").disabled = true; 
	document.getElementById("NMyCode").disabled = true;
	}	
function new_ban(){
	document.getElementById("MyBanner").disabled = false;
	document.getElementById("MyUrl").disabled = false; 
	document.getElementById("NMyCode").disabled = true;
	}
function new_code(){
	document.getElementById("MyBanner").disabled = true;
	document.getElementById("MyUrl").disabled = true; 
	document.getElementById("NMyCode").disabled = false;
	}
function edit_ram(){
	document.getElementById("EMyBanner").disabled = true;
	document.getElementById("EMyUrl").disabled = true; 
	document.getElementById("EMyCode").disabled = true;
	}
function edit_ban(){
	document.getElementById("EMyBanner").disabled = false;
	document.getElementById("EMyUrl").disabled = false; 
	document.getElementById("EMyCode").disabled = true;
	}
function edit_code(){
	document.getElementById("EMyBanner").disabled = true;
	document.getElementById("EMyUrl").disabled = true; 
	document.getElementById("EMyCode").disabled = false;
	}	
	
function edit_scrin(){
	document.getElementById("MyScrin").disabled = false;
	}
function edit_scrin_auto(){
	document.getElementById("MyScrin").disabled = true;
	}
	
function edit_order_banner(){
	document.getElementById("OrderNewBanner").disabled = false;
	}
function edit_order_banner_auto(){
	document.getElementById("OrderNewBanner").disabled = true;
	}		

function ChangeTypePay(whot,whot2,whot3)
	{
		var sel = document.getElementById(whot); 
		var val = sel.options[sel.selectedIndex].value; 
		
		if (val=='Дни')
			{
				document.getElementById(whot2).innerHTML = 'Количество заказов  <input style="width:40px" type="text" name="'+whot3+'" id="'+whot3+'" value="0" readonly> *0 - неограниченно</td>'
			} 
		else
			{
				document.getElementById(whot2).innerHTML = 'Количество заказов  <input style="width:40px" type="text" name="'+whot3+'" id="'+whot3+'" value="0"> *0 - неограниченно</td>'
			}
	}
function NextM(){
/* 
var date = new Date();
b = ((date.getMonth() + 1) + '/' + date.getDate() + '/' +  date.getFullYear());
var n = b.toString();
n=n.split('/');
var mon = new Array();
if (n[0]==1){mon[0]=31;mon[1]=59;mon[2]=90;};
if (n[0]==2){mon[0]=28;mon[1]=59;mon[2]=89;};
if (n[0]==3){mon[0]=31;mon[1]=61;mon[2]=92;};

if (n[0]==4){mon[0]=30;mon[1]=61;mon[2]=91;};
if (n[0]==5){mon[0]=31;mon[1]=61;mon[2]=92;};
if (n[0]==6){mon[0]=30;mon[1]=61;mon[2]=92;};

if (n[0]==7){mon[0]=31;mon[1]=62;mon[2]=92;};
if (n[0]==8){mon[0]=31;mon[1]=61;mon[2]=92;};
if (n[0]==9){mon[0]=30;mon[1]=61;mon[2]=91;};

if (n[0]==10){mon[0]=31;mon[1]=60;mon[2]=91;};
if (n[0]==11){mon[0]=30;mon[1]=61;mon[2]=92;};
if (n[0]==12){mon[0]=31;mon[1]=62;mon[2]=90;};
mon[0]=mon[0]+5;
mon[1]=mon[1]+7;
mon[2]=mon[2]+9;
var c = arr.toString();
c=c.split(',');
if (n[1]>=mon[0]-c.length){$('.ui-icon-circle-triangle-e').click();}
if (n[1]>=mon[1]-c.length){$('.ui-icon-circle-triangle-e').click();}
if (n[1]>=mon[2]-c.length){$('.ui-icon-circle-triangle-e').click();}
*/
}	
	
$('.hidden-link').click(function(){window.open($(this).data('link'));return false;});

/*
document.onkeydown = function(e) {
e = e || window.event;
if(e.keyCode == 85 | e.keyCode == 83) {
return false;
}
return true;
}
*/

/* Версия 1.01 */
function ChoiseUsed(id) {
if (document.getElementById(id).checked){
	$$('GoUsed',$$('GoUsed').value=document.getElementById('GoUsed').value+id+',');
	} else {
	t=document.getElementById('GoUsed').value;
	t=t.replace(id+',',"");
	document.getElementById('GoUsed').value=t;
	}
}
function ChoiseDel(id) {
if (document.getElementById(id).checked){
	$$('GoDelet',$$('GoDelet').value=document.getElementById('GoDelet').value+id+',');
	} else {
	t=document.getElementById('GoDelet').value;
	t=t.replace(id+',',"");
	document.getElementById('GoDelet').value=t;
	}
}