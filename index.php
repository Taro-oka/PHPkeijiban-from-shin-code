<?php 

    date_default_timezone_set("Asia/Tokyo");

    $comment_array = array();

    // 接続確立
    // 第2引数の"root"とは管理者アカウントのこと（デフォルトでそうなる）、第3引数の空文字はパスワード。デフォルトでは設定されていない。
    // 参考・・・https://www.javadrive.jp/xampp/mysql/index2.html
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=bbs-yt', "root", "");
    }catch(PDOException $e) {
        echo $e->getMessage();
    }

    // フォームを打ち込んだ時
    if(!empty($_POST["submitButton"])) {

        if(empty($_POST["username"]) || empty($_POST["comment"])){
            echo "未入力のところがあります";
            return;
        }

        $postDate = date("Y-m-d H:i:s");

        try {
            $stmt = $pdo-> prepare("INSERT INTO `bbs-table` (`username`, `comment`, `postDate`) VALUES (:username, :comment, :postDate)");
            $stmt->bindParam(":username", $_POST["username"], PDO::PARAM_STR);
            $stmt->bindParam(":comment", $_POST["comment"], PDO::PARAM_STR);
            $stmt->bindParam(":postDate", $postDate, PDO::PARAM_STR);
    
            $stmt->execute();
        }catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    $sql = "SELECT `id`, `username`, `comment`, `postDate` FROM `bbs-table`;";
    $comment_array = $pdo->query($sql);   

    $pdo = null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP掲示板</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 class="title">PHPで掲示板アプリ</h1>
    <hr>
    <div class="boardWrapper">
        <section>
            <?php foreach($comment_array as $comment) :?>
            <article>
                <div class="wrapper">
                    <div class="nameArea">
                        <span>名前：</span>
                        <p class="username"><?php echo $comment["username"]; ?></p>
                        <time>：<?php echo $comment["postDate"]; ?></time>
                    </div>
                    <p class="comment"><?php echo $comment["comment"]; ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </section>
        <form class="formWrapper" method="POST">
            <div>
                <input type="submit" value="書き込む" name="submitButton">
                <label>名前：</label>
                <input type="text" name="username">
            </div>
            <div>
                <textarea class="commentTextArea" name="comment"></textarea>
            </div>
        </form>
    </div>

</body>
</html>