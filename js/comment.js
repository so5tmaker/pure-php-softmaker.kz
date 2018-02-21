// JavaScript Document
function doLoad(value, elem, mess, lang, e_img, inp_sum){
    err=document.getElementById(elem);
    path = document.location.href;
    err.innerHTML = "<p align='center'><img src='../../img/dic/loading1.gif' width='16' height='16'><br>" + mess + "</p>";
    img=document.getElementById(e_img);
    sum=document.getElementById(inp_sum);
//    img.innerHTML = "<img style='margin-top:5px; align:left' src=" + o_img +">";
    
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
//                alert(req.responseJS.ok);
                 // Clear error information.
                img.innerHTML = ' ';
                // Write req.responseJS to page element (_req.responseJS become responseJS). 
                img.innerHTML = req.responseJS.img;
//                alert(req.responseJS.img);
                // Clear error information.
                sum.innerHTML = ' ';
                // Write req.responseJS to page element (_req.responseJS become responseJS). 
                sum.innerHTML = req.responseJS.inp_sum;
//                alert(req.responseJS.inp_sum);
            } else {
                err.innerHTML = req.responseJS.er_mess;
            }  	
        }
    }

    req.open(null, '../../comment.php?lang='+lang, true);

    // Send data to backend.
    req.send( { q: value } );
}