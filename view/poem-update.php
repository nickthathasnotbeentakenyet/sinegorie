<?php
// Build a select drop-down menu using the $classifications array
$classificationList = "<br><label for='classificationId'>Выбирите Жанр</label><br>";
$classificationList .= "<select name='classificationId' id='classificationId' required>";
foreach ($classifications as $classification) {
    $classificationList .= "<option  value='$classification[classificationId]'";
    if (isset($classificationId)) {
        if ($classification['classificationId'] === $classificationId) {
            $classificationList .= ' selected ';
        }
    } elseif (isset($poemInfo['classificationId'])) {
        if ($classification['classificationId'] === $poemInfo['classificationId']) {
            $classificationList .= ' selected ';
        }
    }
    $classificationList .= ">$classification[classificationName]</option>";
}
$classificationList .= '</select>';
?>
<?php
if (!$_SESSION['loggedin'] || $_SESSION['clientData']['clientLevel'] < 3) {
    header('Location: /sinegorie/');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Синегорье | Изменить Произведение</title>
    <link rel="stylesheet" href="/sinegorie/css/mobile.css" type="text/css">
    <link rel="stylesheet" href="/sinegorie/css/desktop.css" type="text/css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gemunu+Libre:wght@300&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&family=Gemunu+Libre:wght@300&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="/sinegorie/images/logo.png" type="image/x-icon">
</head>

<body>
    <div class="wrapper">
        <div class="content">
            <header>
                <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/sinegorie/snippets/header.php'; ?>
            </header>
            <nav>
                <?php echo getNavigationBar($classifications); ?>
            </nav>
            <main class="poemUpdate-view">
                <h1><?php if (isset($poemInfo['poemName'])) { echo "Изменить \"$poemInfo[poemName]\"";} 
                elseif (isset($poemName)) {echo "Изменить \"$poemName\"";} ?></h1>
                <?php
                if (isset($message)) {
                    echo $message;
                }
                ?>
                    <form action="/sinegorie/poetry/index.php" method="post">
                        <?php echo $classificationList; ?><br>
                        <label for="poemName">Название</label><br>
                        <input type="text" name="poemName" id="poemName" <?php if (isset($poemName)) {
                                                                                echo "value='$poemName'";
                                                                            } elseif (isset($poemInfo['poemName'])) {
                                                                                echo "value='$poemInfo[poemName]'";
                                                                            } ?> required placeholder="Белая береза"><br>
                        <label for="poemText">Текст произведения</label><br>
                        <textarea name="poemText" id="poemText" required><?php if (isset($poemText)) {
                                                                                echo $poemText;
                                                                            } elseif (isset($poemInfo['poemText'])) {
                                                                                echo $poemInfo['poemText'];
                                                                            } ?></textarea><br>
                                                                             <label for="poemAuthor">Автор</label><br>
                        <input type="text" name="poemAuthor" id="poemAuthor" <?php if (isset($poemNAuthor)) {
                                                                                echo "value='$poemAuthor'";
                                                                            } elseif (isset($poemInfo['poemAuthor'])) {
                                                                                echo "value='$poemInfo[poemAuthor]'";
                                                                            } ?> placeholder="Сергей Есенин"><br>
                        <label for="poemDate">Дата написания</label><br>
                        <input type="text" name="poemDate" id="poemDate" <?php if (isset($poemDate)) {
                                                                                   echo "value='$poemDate'";
                                                                                } elseif (isset($poemInfo['poemDate'])) {
                                                                                echo "value='$poemInfo[poemDate]'";
                                                                                } ?>><br>
                        <label for="poemImage">Изображение</label><br>
                        <input type="text" name="poemImage" id="poemImage" <?php if (isset($poemImage)) {
                                                                                echo "value='$poemImage'";
                                                                            } elseif (isset($poemInfo['poemImage'])) {
                                                                                echo "value='$poemInfo[poemImage]'";
                                                                            } ?> placeholder="/images/безымянный.png"><br>
                        <input type="submit" name="submit" id="updPoem" value="Изменить произведение">
                        <input type="hidden" name="action" value="updatePoem">
                        <input type="hidden" name="poemId" value="<?php if (isset($poemInfo['poemId'])) {
                                                                        echo $poemInfo['poemId'];
                                                                    } elseif (isset($poemId)) {
                                                                        echo $poemId;
                                                                    } ?>">
                    </form>
                    <br>
            </main>
        </div>
        <div class="footer">
            <footer>
                <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/sinegorie/snippets/footer.php'; ?>
            </footer>
        </div>
    </div>
    <script src="/sinegorie/js/hamburger.js"></script>
</body>

</html>