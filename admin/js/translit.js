String.prototype.trimMiddle=function()
// убирает все пробелы в начале и в конце строки
// помимо этого заменяет несколько подряд
// идущих пробелов внутри строки на один пробел
{
  var r=/\s\s+/g;
  return this.trim().replace(r,' ');
}

function SetTranslitRuToLat(){
var text=document.getElementById('title').value;
var transl=new Array();
    transl['А']='A';     transl['а']='a';
    transl['Б']='B';     transl['б']='b';
    transl['В']='V';     transl['в']='v';
    transl['Г']='G';     transl['г']='g';
    transl['Д']='D';     transl['д']='d';
    transl['Е']='E';     transl['е']='e';
    transl['Ё']='Yo';    transl['ё']='yo';
    transl['Ж']='Zh';    transl['ж']='zh';
    transl['З']='Z';     transl['з']='z';
    transl['И']='I';     transl['и']='i';
    transl['Й']='J';     transl['й']='j';
    transl['К']='K';     transl['к']='k';
    transl['Л']='L';     transl['л']='l';
    transl['М']='M';     transl['м']='m';
    transl['Н']='N';     transl['н']='n';
    transl['О']='O';     transl['о']='o';
    transl['П']='P';     transl['п']='p';
    transl['Р']='R';     transl['р']='r';
    transl['С']='S';     transl['с']='s';
    transl['Т']='T';     transl['т']='t';
    transl['У']='U';     transl['у']='u';
    transl['Ф']='F';     transl['ф']='f';
    transl['Х']='X';     transl['х']='x';
    transl['Ц']='C';     transl['ц']='c';
    transl['Ч']='Ch';    transl['ч']='ch';
    transl['Ш']='Sh';    transl['ш']='sh';
    transl['Щ']='Shh';   transl['щ']='shh';
    transl['Ъ']='J';     transl['ъ']='j';
    transl['Ы']='Y';     transl['ы']='y';
    transl['Ь']='';      transl['ь']='';
    transl['Э']='E';     transl['э']='e';
    transl['Ю']='Yu';    transl['ю']='yu';
    transl['Я']='Ya';    transl['я']='ya';
    transl[' ']='-';     transl['.']=''; 
    transl[String.fromCharCode(1030)]='I'; // Казахская сонорная I 
    transl[String.fromCharCode(1110)]='i'; // Казахская сонорная i 
    transl[String.fromCharCode(1186)]='N'; // Казахская сонорная Н (с хвостиком) 
    transl[String.fromCharCode(1187)]='n'; // Казахская сонорная н (с хвостиком)
    transl[String.fromCharCode(1198)]='Y'; // Казахская У мягкая 
    transl[String.fromCharCode(1199)]='y'; // Казахская у мягкая
    transl[String.fromCharCode(1178)]='K'; // Казахская К (с хвостиком) 
    transl[String.fromCharCode(1179)]='k'; // Казахская к (с хвостиком)
    transl[String.fromCharCode(1200)]='Y'; // Казахская У твёрдая 
    transl[String.fromCharCode(1201)]='y'; // Казахская У твёрдая
    transl[String.fromCharCode(1170)]='G'; // Казахская Г (с чёрточкой)
    transl[String.fromCharCode(1171)]='g'; // Казахская г (с чёрточкой)
    transl[String.fromCharCode(1256)]='O'; // Казахская O мягкая 
    transl[String.fromCharCode(1257)]='o'; // Казахская o мягкая 
    transl[String.fromCharCode(1240)]='A'; // Казахская A
    transl[String.fromCharCode(1241)]='a'; // Казахская a
    
    // Убираю тире, дефисы внутри строки
    text = text.replace(/\–+/g,''); // символ 'ndash'
    text = text.replace(/-+/g,''); // символ '-'
    text = text.replace(/—+/g,''); // символ 'mdash'

// Убираю лишние пробелы внутри строки
    text = text.trimMiddle();

    var result='';
    for(i=0;i<text.length;i++) {
        if(transl[text[i]]!=undefined) { result+=transl[text[i]]; }
        else { result+=text[i]; }
    }
    
    var literals = 'QqWwEeRrTtYyUuIiOoPpAaSsDdFfGgHhJjKkLlZzXxCcVvBbNnMm-0123456789';
    var newString = '';
    for (var i = 0; i < result.length; i++) {
        if (!(literals.indexOf(result.charAt(i)) == -1)) {
            newString += result.charAt(i); 
        };
    };

    document.getElementById('name').value=newString.toLowerCase();
}