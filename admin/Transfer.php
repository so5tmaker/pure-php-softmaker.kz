<?php
namespace Acme\Tools; 
class Foo
{
  public function doAwesomeFooThings ()
  {
     echo 'Hi listeners';
     $dt = new DateTime();
  }
}
//echo phpinfo();
//namespace Foo;
//function strlen($string) {
//    return 5;
//}
//
//namespace Bar;
//use Foo;
//echo \Foo\strlen('baz');

include ("lock.php");

include("header.html");

class A {
    public function __construct() {
        $std = new \stdClass();  
        $std->prop = 15;
        return $std;
    }
}

$c = new A();
echo $c->prop;
//
//$a=1;
//$a<=>1;
//$a>=1;
//$a<=1;
//$a<>1;
//$a===1;
//$a!=1;

//session_start();
//echo session_start();
//echo(session_name('abs'));
//session_destroy();
//echo session_start();

//$array = array("size" => "XL", "color" => "gold");
//print_r(ksort($array)); // ksort возвращает TRUE или false
//print_r(array_values($array));
//class Faz{}
//class Baz extends Faz{};
//
//interface Foo{
//    public function du(Faz $param);
//}
//
//class Boo implements Foo {
//    public function du(Baz $param) {
//        echo false;   
//    }
//}
//$c = new Boo();
//$c->du(new Baz);

//function foo(&$bar) {
//    $bar *= 2;
//    return $bar;
//    
//}
//$x = 3;
//$y = foo($x);
//$x = 5;
//
//echo $x.', '.$y;

//$pattern = '@^(?:http://)?([^/]+)@i';
//preg_match($pattern, 'http://www.php.net/index.html', $matches);
//echo $matches[1];

//trait Foo {
//    private $name = 'Foo';
//}
//
//class Bar  {
//    use Foo;
//    private $name = 'Bar';
//    public function getName(){
//        $this->name;
//    }
//}
//
//$bar = new Bar();
//$bar->getName();

//ob_start();
//echo 'Test';
//$output = ob_get_contents();
//ob_end_clean();
//echo $output;
//
//$a = array('c', 'b', 'a');
//$b = (array) $a;
//print_r($b);

//class A {
//    public function __construct(){
//        echo 'Hello World!';
//    }
//}
//
//class B extends A {
//    public function __construct(){
//        
//    }
//}
//
//$c = new B();

//trait Foo {
//    public function getName(){
//        echo 'Foo';
//    }
//}
//
//class Bar {
//    public function getName(){
//        echo 'Bar';
//    }
//}
//
//class Baz extends Bar {
//    use Foo {getName as private;}
//}
//
//$baz = new Baz();
//$baz->getName();




//$data = array("id, id_lang, name, cat, meta_d, meta_k, description, "
//    . "text, view, author, date, lang, mini_img, title, secret, rating, q_vote, "
//    . "file, phpcolor, notprohibit, blog_id");
//$result = $db1->select('data', "cat<>cat ORDER BY id ASC");
//foreach($result as $k => $v) {
//    $data = $result[$k];
//    $id  = $data[id];
//    $data1 = array(
//            "cat" => "'$data[cat]'"
//            );
//    //update the row in the database
//    $db1->update($data1, 'data', "id='$id'");
//}
//$result = $db1->columns('advs');
//foreach($result as $k => $v) {
//    $data = $result[$k];
//    echo "public $$v;<br>";
//}

include_once ("footer.html");
?>
