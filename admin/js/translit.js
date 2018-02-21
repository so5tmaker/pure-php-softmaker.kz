String.prototype.trimMiddle=function()
// ������� ��� ������� � ������ � � ����� ������
// ������ ����� �������� ��������� ������
// ������ �������� ������ ������ �� ���� ������
{
  var r=/\s\s+/g;
  return this.trim().replace(r,' ');
}

function SetTranslitRuToLat(){
var text=document.getElementById('title').value;
var transl=new Array();
    transl['�']='A';     transl['�']='a';
    transl['�']='B';     transl['�']='b';
    transl['�']='V';     transl['�']='v';
    transl['�']='G';     transl['�']='g';
    transl['�']='D';     transl['�']='d';
    transl['�']='E';     transl['�']='e';
    transl['�']='Yo';    transl['�']='yo';
    transl['�']='Zh';    transl['�']='zh';
    transl['�']='Z';     transl['�']='z';
    transl['�']='I';     transl['�']='i';
    transl['�']='J';     transl['�']='j';
    transl['�']='K';     transl['�']='k';
    transl['�']='L';     transl['�']='l';
    transl['�']='M';     transl['�']='m';
    transl['�']='N';     transl['�']='n';
    transl['�']='O';     transl['�']='o';
    transl['�']='P';     transl['�']='p';
    transl['�']='R';     transl['�']='r';
    transl['�']='S';     transl['�']='s';
    transl['�']='T';     transl['�']='t';
    transl['�']='U';     transl['�']='u';
    transl['�']='F';     transl['�']='f';
    transl['�']='X';     transl['�']='x';
    transl['�']='C';     transl['�']='c';
    transl['�']='Ch';    transl['�']='ch';
    transl['�']='Sh';    transl['�']='sh';
    transl['�']='Shh';   transl['�']='shh';
    transl['�']='J';     transl['�']='j';
    transl['�']='Y';     transl['�']='y';
    transl['�']='';      transl['�']='';
    transl['�']='E';     transl['�']='e';
    transl['�']='Yu';    transl['�']='yu';
    transl['�']='Ya';    transl['�']='ya';
    transl[' ']='-';     transl['.']=''; 
    transl[String.fromCharCode(1030)]='I'; // ��������� �������� I 
    transl[String.fromCharCode(1110)]='i'; // ��������� �������� i 
    transl[String.fromCharCode(1186)]='N'; // ��������� �������� � (� ���������) 
    transl[String.fromCharCode(1187)]='n'; // ��������� �������� � (� ���������)
    transl[String.fromCharCode(1198)]='Y'; // ��������� � ������ 
    transl[String.fromCharCode(1199)]='y'; // ��������� � ������
    transl[String.fromCharCode(1178)]='K'; // ��������� � (� ���������) 
    transl[String.fromCharCode(1179)]='k'; // ��������� � (� ���������)
    transl[String.fromCharCode(1200)]='Y'; // ��������� � ������ 
    transl[String.fromCharCode(1201)]='y'; // ��������� � ������
    transl[String.fromCharCode(1170)]='G'; // ��������� � (� ���������)
    transl[String.fromCharCode(1171)]='g'; // ��������� � (� ���������)
    transl[String.fromCharCode(1256)]='O'; // ��������� O ������ 
    transl[String.fromCharCode(1257)]='o'; // ��������� o ������ 
    transl[String.fromCharCode(1240)]='A'; // ��������� A
    transl[String.fromCharCode(1241)]='a'; // ��������� a
    
    // ������ ����, ������ ������ ������
    text = text.replace(/\�+/g,''); // ������ 'ndash'
    text = text.replace(/-+/g,''); // ������ '-'
    text = text.replace(/�+/g,''); // ������ 'mdash'

// ������ ������ ������� ������ ������
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