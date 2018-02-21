<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Demos
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/*
* This sample utilizes the Zend Gdata Client Library, which can be
* downloaded from: http://framework.zend.com/download
*
* This sample is meant to show basic CRUD (Create, Retrieve, Update
* and Delete) functionality of the Blogger data API, and can only
* be run from the command line.
*
* To run the sample:
* php Blogger.php --user=email@email.com --pass=password
*/

/**
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * @see Zend_Gdata
 */
Zend_Loader::loadClass('Zend_Gdata');

/**
 * @see Zend_Gdata_Query
 */
Zend_Loader::loadClass('Zend_Gdata_Query');

/**
 * @see Zend_Gdata_ClientLogin
 */
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');


/**
 * Class that contains all simple CRUD operations for Blogger.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Demos
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Blog
{
    /**
     * $blogID - Blog ID used for demo operations
     *
     * @var string
     */
    public $blogID;

    /**
     * $gdClient - Client class used to communicate with the Blogger service
     *
     * @var Zend_Gdata_Client
     */
    public $gdClient;


    /**
     * Constructor for the class. Takes in user credentials and generates the
     * the authenticated client object.
     *
     * @param  string $email    The user's email address.
     * @param  string $password The user's password.
     * @return void
     */
    public function __construct($email = "softmaker.kz@gmail.com", $password = "]budetsoft")
    {
        $client = Zend_Gdata_ClientLogin::getHttpClient($email, $password, 'blogger');
        $this->gdClient = new Zend_Gdata($client);
    }

    /**
     * This function retrieves all the blogs associated with the authenticated
     * user and prompts the user to choose which to manipulate.
     *
     * Once the index is selected by the user, the corresponding blogID is
     * extracted and stored for easy access.
     *
     * @return void
     */
    public function promptForBlogID()
    {
        $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/default/blogs');
        $feed = $this->gdClient->getFeed($query);

        //id text is of the form: tag:blogger.com,1999:user-blogID.blogs
        $idText = explode('-', $feed->entries[0]->id->text);
        $this->blogID = $idText[2];
    }

    /**
     * This function creates a new Zend_Gdata_Entry representing a blog
     * post, and inserts it into the user's blog. It also checks for
     * whether the post should be added as a draft or as a published
     * post.
     *
     * @param  string  $title   The title of the blog post.
     * @param  string  $content The body of the post.
     * @param  boolean $isDraft Whether the post should be added as a draft or as a published post
     * @return string The newly created post's ID
     */
    public function createPost($title, $content, $date, $label = "",  $isDraft=False)
    {
        // We're using the magic factory method to create a Zend_Gdata_Entry.
        // http://framework.zend.com/manual/en/zend.gdata.html#zend.gdata.introdduction.magicfactory
        $entry = $this->gdClient->newEntry();

        $entry->title = $this->gdClient->newTitle(iconv('windows-1251', 'UTF-8',trim($title)));
        $entry->content = $this->gdClient->newContent(iconv('windows-1251', 'UTF-8',trim($content)));
        $entry->content->setType('text');
        $uri = "http://www.blogger.com/feeds/" . $this->blogID . "/posts/default";

        if ($isDraft)
        {
            $control = $this->gdClient->newControl();
            $draft = $this->gdClient->newDraft('yes');
            $control->setDraft($draft);
            $entry->control = $control;
        }

        //zend gdata blogger ярлыки
        $labels = $entry->getCategory();
        $newLabel = $this->gdClient->newCategory(iconv('windows-1251', 'UTF-8', $label), 'http://www.blogger.com/atom/ns#');
        $labels[] = $newLabel; // Append the new label to the list of labels. 
        $entry->setCategory($labels);

        // Установим дату
        $entry->published = $this->gdClient->newPublished($date.'T00:00:00.000-00:00');
        
        $createdPost = $this->gdClient->insertEntry($entry, $uri);
        //format of id text: tag:blogger.com,1999:blog-blogID.post-postID
        $idText = explode('-', $createdPost->id->text);
        $postID = $idText[2];

        return $postID;
    }

    /**
     * Prints the titles of all the posts in the user's blog.
     *
     * @return void
     */
    public function printAllPosts()
    {
        $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/' . $this->blogID . '/posts/default');
        $feed = $this->gdClient->getFeed($query);
        $this->printFeed($feed);
    }

    /**
     * Retrieves the specified post and updates the title and body. Also sets
     * the post's draft status.
     *
     * @param string  $postID         The ID of the post to update. PostID in <id> field:
     *                                tag:blogger.com,1999:blog-blogID.post-postID
     * @param string  $updatedTitle   The new title of the post.
     * @param string  $updatedContent The new body of the post.
     * @param boolean $isDraft        Whether the post will be published or saved as a draft.
     * @return Zend_Gdata_Entry The updated post.
     */
    public function updatePost($postID, $updatedTitle, $updatedContent, $isDraft)
    {
        $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/' . $this->blogID . '/posts/default/' . $postID);
        $postToUpdate = $this->gdClient->getEntry($query);
        $postToUpdate->title->text = $this->gdClient->newTitle(iconv('windows-1251', 'UTF-8',trim($updatedTitle)));
        $postToUpdate->content->text = $this->gdClient->newContent(iconv('windows-1251', 'UTF-8',trim($updatedContent)));

        if ($isDraft) {
            $draft = $this->gdClient->newDraft('yes');
        } else {
            $draft = $this->gdClient->newDraft('no');
        }

        $control = $this->gdClient->newControl();
        $control->setDraft($draft);
        $postToUpdate->control = $control;
        $updatedPost = $postToUpdate->save();

        return $updatedPost;
    }

    /**
     * This function uses query parameters to retrieve and print all posts
     * within a specified date range.
     *
     * @param  string $startDate Beginning date, inclusive. Preferred format is a RFC-3339 date,
     *                           though other formats are accepted.
     * @param  string $endDate   End date, exclusive.
     * @return void
     */
    public function printPostsInDateRange($startDate, $endDate)
    {
        $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/' . $this->blogID . '/posts/default');
        $query->setParam('published-min', $startDate);
        $query->setParam('published-max', $endDate);

        $feed = $this->gdClient->getFeed($query);
        $this->printFeed($feed);
    }

    /**
     * This function creates a new comment and adds it to the specified post.
     * A comment is created as a Zend_Gdata_Entry.
     *
     * @param  string $postID      The ID of the post to add the comment to. PostID
     *                             in the <id> field: tag:blogger.com,1999:blog-blogID.post-postID
     * @param  string $commentText The text of the comment to add.
     * @return string The ID of the newly created comment.
     */
    public function createComment($postID, $commentText)
    {
        $uri = 'http://www.blogger.com/feeds/' . $this->blogID . '/' . $postID . '/comments/default';

        $newComment = $this->gdClient->newEntry();
        $newComment->content = $this->gdClient->newContent($commentText);
        $newComment->content->setType('text');
        $createdComment = $this->gdClient->insertEntry($newComment, $uri);

        echo 'Added new comment: ' . $createdComment->content->text . "\n";
        // Edit link follows format: /feeds/blogID/postID/comments/default/commentID
        $editLink = explode('/', $createdComment->getEditLink()->href);
        $commentID = $editLink[8];

        return $commentID;
    }

    /**
     * This function prints all comments associated with the specified post.
     *
     * @param  string $postID The ID of the post whose comments we'll print.
     * @return void
     */
    public function printAllComments($postID)
    {
        $query = new Zend_Gdata_Query('http://www.blogger.com/feeds/' . $this->blogID . '/' . $postID . '/comments/default');
        $feed = $this->gdClient->getFeed($query);
        $this->printFeed($feed);
    }

    /**
     * This function deletes the specified comment from a post.
     *
     * @param  string $postID    The ID of the post where the comment is. PostID in
     *                           the <id> field: tag:blogger.com,1999:blog-blogID.post-postID
     * @param  string $commentID The ID of the comment to delete. The commentID
     *                           in the editURL: /feeds/blogID/postID/comments/default/commentID
     * @return void
     */
    public function deleteComment($postID, $commentID)
    {
        $uri = 'http://www.blogger.com/feeds/' . $this->blogID . '/' . $postID . '/comments/default/' . $commentID;
        $this->gdClient->delete($uri);
    }

    /**
     * This function deletes the specified post.
     *
     * @param  string $postID The ID of the post to delete.
     * @return void
     */
    public function deletePost($postID)
    {
        $uri = 'http://www.blogger.com/feeds/' . $this->blogID . '/posts/default/' . $postID;
        $this->gdClient->delete($uri);
    }

    /**
     * Helper function to print out the titles of all supplied Blogger
     * feeds.
     *
     * @param  Zend_Gdata_Feed The feed to print.
     * @return void
     */
    public function printFeed($feed)
    {
        $i = 0;
        foreach($feed->entries as $entry)
        {
            echo "\t" . $i ." ". $entry->title->text . "\n";
            $i++;
        }
    }

    /**
     * Runs the sample.
     *
     * @return void
     */
    public function run()
    {
        echo "Note: This sample may Create, Read, Update and Delete data " .
             "stored in the account provided.  Please exit now if you provided " .
             "an account which contains important data.\n\n";
        $this->promptForBlogID();

        echo "Creating a post.\n";
        $this->createPost('Hello, world!', 'I am on the intarweb!', False);

        echo "Creating a draft post.\n";
        $postID = $this->createPost('Salutations, world!', 'Does not sound right.. must work on title.', True);

        echo "Updating the previous post and publishing it.\n";
        $updatedPost = $this->updatePost($postID, 'Hello, world, it is.', 'There we go.', False);
        echo "The new title of the post is: " . $updatedPost->title->text . "\n";
        echo "The new body of the post is: " . $updatedPost->content->text . "\n";

        echo "Adding a comment to the previous post.\n";
        $this->createComment($postID, 'I am so glad this is public now.');

        echo "Adding another comment.\n";
        $commentID = $this->createComment($postID, 'This is a spammy comment.');

        echo "Deleting the previous comment.\n";
        $this->deleteComment($postID, $commentID);

        echo "Printing all posts.\n";
        $this->printAllPosts();

        echo "Printing posts between 2007-01-01 and 2007-03-01.\n";
        $this->printPostsInDateRange('2007-01-01','2007-06-30');

        echo "Deleting the post titled: " . $updatedPost->title->text . "\n";
        $this->deletePost($postID);
    }
}

/**
 * Gets credentials from user.
 *
 * @param  string $text
 * @return string Index of the blog the user has chosen.
 */

function CreateSmartMessage($myrow, $faq = '') {
  
    global $filename, $sec, $db, $open;

    // Формирование сообщения из базы
    $cat = $myrow[cat];
    $result_cat = mysql_query("SELECT title, name FROM categories WHERE (id=$cat)", $db);
    $myrow_cat = mysql_fetch_array($result_cat);
    $num_rows = mysql_num_rows($result_cat);
    // если количество записей не равно нулю
    if ($num_rows!=0)
    {
        $cat_name = $myrow_cat[name];
        $label = $myrow_cat[title];
    } else { 
        return FALSE;
    }
    if ($cat_name == '0')
    {
        return FALSE;
    }
    $link = get_other_lang_link($myrow[lang]);
    $was = FALSE;
    if ($sec !== '2') {
        if ($faq !== '') {
            $was = TRUE;
//            $result_faq = mysql_query("SELECT id,answer,question,date FROM faq WHERE (id=$faq_id)", $db);
//            $myrow_faq = mysql_fetch_array($result_faq);
//            $num_rows = mysql_num_rows($result_faq);
            $myrow_faq = $faq;
            $num_rows = count($myrow_faq);
            // если количество записей не равно нулю
            if ($num_rows!=0)
            {
                $anchor = "#$myrow_faq[id]";
            } else { 
                echo "<p align=center>Сообщение не создано! Наверное нет вопросов для заметки - " . $myrow[title] . '</p>';
                return FALSE;
            }
        }
    } 
    
    if (!$was) { // если это не вопрос ЧаВО
        $text = $myrow[description];
        $anchor = "";
    }
    
    $matches = null;
    // Вырезает из текста все теги img вместе с атрибутами $myrow[text]
    preg_match_all('/<img[^>]+>/i', stripslashes($myrow[text]), $matches);
    $bodytag = $matches[0];
    $bodytag = str_replace("../../", $link, $bodytag);

    if ($bodytag[0] != null){
        $image = "<p align=center>"
        ."<a href='$link$filename/$cat_name/".$myrow[name].".html$anchor'>" 
//            .$open
        .$bodytag[0]."</a>"
        ."</p>";
        // Убираю лишние переводы строк из текста
        $image = str_replace("\r\n",'',$image);
        $image = str_replace("\n",'',$image);
    } else {
        // папка с миниатюрами
        $part_file = '../img/thumb/';
        $catname = str_replace("1s", "odins", $cat_name);
        // временная папка по названию категории
        $mini_img_temp = $part_file.'temp/'.$catname.'.png';
        // уже должна лежать миниатюра default.png
        $default_name = $part_file.'default.png'; 
        // миниатюра по транслитерированному названию статьи
        $distination = $part_file.$myrow[name].'.png'; 
        // ищем миниатюру по названию статьи
        if (!file_exists($distination)){ 
            $distination = $mini_img_temp;
            // ищем миниатюру по названию категории
            if (!file_exists($distination)){ 
                // не нашли берём по умолчанию
                $distination = $default_name;
            }
        }
        // Теперь получаем размеры исходного изображения.
        $size = getimagesize($distination);
        $image_size = $size[3];
        // заменяю ../ на $link
        $distination = str_replace("../", $link, $distination); // width="200" height="150"
        $img = '<img '.$image_size.' title="'.$myrow[title].'
	alt="'.$myrow[meta_d].'" src="'.$distination.'" >';
        $image = "<p align=center>"
        ."<a href='$link$filename/$cat_name/".$myrow[name].".html$anchor'>" 
//            .$open
        .$img."</a>"
        ."</p>";
    }
    
    $title = "";
    if (!$was) { // если это не вопрос ЧаВО
        $title = $myrow[meta_d];
        $footer = "<p align=center>"
        .get_foreign_equivalent("Читать далее")." <a href='$link$filename/$cat_name/".$myrow[name].".html'" 
        .$open."'>"
        ."«$myrow[title]»</a>"
        ."</p>";
        $title1 = "<h1 align=center>".$title."</h1>";
        $body1 = $title1.$image.$text.$footer;
        echo $body1;
        $body = $image.$text.$footer;
    } else { // если это ЧаВО
        $title = $myrow_faq[question];
        $footer = "<p align=center>"
        .get_foreign_equivalent("Подробнее об этом в статье")." <a href='$link$filename/$cat_name/".$myrow[name].".html$anchor'" 
        .$open."'>"
        ."«".$myrow[meta_d]."»</a>"
        ."</p>";
        $title1 = "<h1 align=center>".$title."</h1>";
        $body1 = $title1.$image.$footer;
        echo $body1;
        $body = $image.$text.$footer;
    }
    ?>
    <p>
     <label>Текст записи<br>
       <textarea name="answer" id="answer" cols="70" rows="5"><?echo $body?></textarea>
     </label>
     </p>
    <?
    $message = array (
    "title" => $title,
    "body"  => $body,
    "label" => $label,
    );

    return $message;
}

