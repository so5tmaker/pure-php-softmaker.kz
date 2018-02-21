<?php
require_once '../blocks/global.inc.php';

$link = filter_input(INPUT_POST, 'link');
$name = filter_input(INPUT_POST, 'name'); 

if (isset($name) AND (isset($link))) {

    $result = $db1->select('data', "name='$name'", "id, cat, text, title");
    if(count($result)){
        $id_score = $result["id"]; 
        $cat = $result["cat"]; 
        $filename = 'articles';
        $deep = '../../';
        $distination = $DIR."tmp/$name.pdf";
        if (!file_exists($distination)){
            topdf($result["text"], $result["title"], $link, $name);
        }
        echo $url."tmp/$name.pdf";
    }
}

function rem_text($param) {
    global $url;
    $text = str_replace('../../', $url, $param); 
    $text = iconv('windows-1251', 'utf-8', stripcslashes($text));
    return $text;
}

function topdf($content, $title, $link, $name) {
    global $id_score, $cat, $deep, $filename;

    include("mpdf60/mpdf.php");

    $mpdf=new mPDF('cp-1251','A4','','',15,15,35,10,5,5); 

    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdf60/my.css');
    $mpdf->WriteHTML(rem_text($stylesheet),1);

//    $mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

    $img = "'../../img/header.png'";
//    $header = '
//    <table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
//    <td width="33%">Left header p <span style="font-size:14pt;">{PAGENO}</span></td>
//    <td width="33%" align="center"><img src="../img/SM.png" width="50px" /></td>
//    <td width="33%" style="text-align: right;"><span style="font-weight: bold;"><a target=blank_ title= "Откроется в новом окне" href="http://www.softmaker.kz">www.softmaker.kz</a></span></td>
//    </tr></table>
//    ';
    $header = '<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
        <td height="100" width="100%" align="center" style="background:url('.$img.') no-repeat #4491D7; padding-left:120px;">
        <div >

        <a style="color:#FFFFFF;font:20pt Arial,sans-serif;" href="../../">SoftMaker.Kz</a>
        <p>
        <a style="color:#FFFFFF;font:12pt Arial,sans-serif;" href="'.$link.'">'.$title.'</a>
        </p>

        </div>
        </td>
    </tr>
    </table>';

    $footer = '<table style="vertical-align: bottom; background-image: url(../../img/ArticleBarBlue.gif); background-repeat: repeat-x;color : #999999;" width="100%">
    <tr>&nbsp;</tr>
    <tr>
        <td width="33%" style="text-align: left; font: 12px Arial,sans-serif;" >
            <a href="../../" >www.softmaker.kz</a>
        </td>
        <td width="33%" style="text-align: center; color: #999999; font: 12px Arial,sans-serif;" >
            {PAGENO}
        </td>
        <td width="33%" style="text-align: right; color: #999999; font: 12px Arial,sans-serif; ">
            Copyright SoftMaker.Kz © 2009
        </td>
    </tr>
    </table>';
    $footerE = '<div align="center">See <a href="http://mpdf1.com/manual/index.php">documentation manual</a></div>';


    $mpdf->SetHTMLHeader(rem_text($header));
    $mpdf->SetHTMLFooter(rem_text($footer));

    $text = rem_text(get_highlight_code($content, false).
            '<br><strong>Другие заметки на эту же тему:</strong>'
            .show_list());

    $mpdf->WriteHTML($text);

    $mpdf->Output("../tmp/$name.pdf", 'F'); //, 'F'
//    exit;
}


