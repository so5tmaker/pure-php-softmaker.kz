<?php
//phpinfo();
  // �������� ��������� Zend Gdata 
  require_once 'Zend/Loader.php';
  Zend_Loader::loadClass('Zend_Gdata');
  Zend_Loader::loadClass('Zend_Gdata_Query');
  Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
  Zend_Loader::loadClass('Zend_Gdata_Feed');

  // ������� ������� ������ ��� �������������� ClientLogin
  $user = "softmaker.kz@gmail.com";
  $pass = "]budetgoo";
try {
  // ����������� 
  // ������������� ������� ������
  $client = Zend_Gdata_ClientLogin::getHttpClient(
    $user, $pass, 'blogger');
  $service = new Zend_Gdata($client);

  // ��������� ������ ���� ������
  $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/default/blogs');
  $feed = $service->getFeed($query);
} catch (Exception $e) {
  die('ERROR:' . $e->getMessage());  
}
?>
<html>
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
  <body>
    <h2>Blogs</h2>
    <ul>
      <?php 
      print_r($feed);
      foreach ($feed as $entry): ?>      
      <li>
        <a href="<?php echo $entry->getLink('alternate')->getHref(); ?>">
         <?php echo $entry->getTitle(); ?> 
        </a>          
      </li>
      <?php endforeach; ?>
    </ul>  
  </body>
</html>
<?
//  // ������� �������������� �����
//  $id = 'YOUR-BLOG-ID-HERE';
//
//  try {
//    // ����������� 
//    // ������������� ������� ������
//    $client = Zend_Gdata_ClientLogin::getHttpClient(
//      $user, $pass, 'blogger');
//    $service = new Zend_Gdata($client);
//
//    // �������� ������ ������� ������
//    // ���������� ��� �������    
//    $uri = 'http://www.blogger.com/feeds/' . $id . '/posts/default';
//    $entry = $service->newEntry();
//    $entry->title = $service->newTitle($_POST['title']);
//    $entry->content = $service->newContent($_POST['body']);
//    $entry->content->setType('text');
//
//    // ���������� ������ �� �������
//    // ��������� ����������� �������������� ����� �������
//    $response = $service->insertEntry($entry, $uri);
//    $arr = explode('-', $response->getId());
//    $id = $arr[2];
//    echo 'Successfully added post with ID: ' . $id;
//
//  } catch (Exception $e) {
//    die('ERROR:' . $e->getMessage());  
//  }
//}
?>
<!--<html>
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  </head>
  <body>
     <h2>Add New Post</h2>
    <form method="post">
      Title: <br/> <input type="text" name="title" 
       size="50" /> <br/>
      Body: <br/> <textarea name="body" cols="40" 
       rows="10"> </textarea> <br/>
      <input type="submit" name="submit" value="Post" />
    </form>
  </body>
</html>-->