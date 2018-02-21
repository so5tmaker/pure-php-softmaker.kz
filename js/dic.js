// JavaScript Document
function doLoad(value, elem, mess, lang, radio, search, language, backurl, langlink, columnlang){
    if (!backurl){backurl='';}else{backurl='&backurl='+backurl;}
    if (!langlink){langlink='';}else{langlink='&langlink='+langlink;}
    if (!columnlang){columnlang='';}else{columnlang='&columnlang='+columnlang;}
    err=document.getElementById(elem);
//    search=escape(search);
//alert(encodeURIComponent(search));
//alert(search);
    if (radio == 0) 
    {
        err.innerHTML = "<img src='img/dic/loading1.gif' width='16' height='16'><br><p>" + mess + "</p>";
    }
    // Create new JsHttpRequest object.
    var req = new JsHttpRequest();
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
            
            if (req.responseJS.error == 'no') {
                // Clear error information.
//                alert('yes!');
                err.innerHTML = ' ';
                // Write req.responseJS to page element (_req.responseJS become responseJS). 
                err.innerHTML = req.responseJS.ok;	
            } else {
                err.innerHTML = req.responseJS.er_mess;
            }  	
        }
    }
    // Prepare request object (automatically choose GET or POST).
    if (radio == 1) 
    {
        getradio = '&radio=1&language='+mess;
    } else {getradio = '';}
    if (search == '') {search='';} else {search = '&search='+search+'&language='+language;}
    req.open(null, 'dic.php?lang='+lang+getradio+search+backurl+langlink+columnlang, true);
    // Send data to backend.
    req.send( { q: value } );
}