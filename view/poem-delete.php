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
    <title>Синегорье | Удалить Произведение</title>
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
            <main>
                <h1><?php if (isset($poemInfo['poemName'])) {
                        echo "Удалить \"$poemInfo[poemName]\"";
                    } elseif (isset($poemName)) {
                        echo "Удалить$poemName";
                    } ?>
                </h1>
                <?php
                if (isset($message)) {
                    echo $message;
                }
                ?>
                <div>
                    <form action="/sinegorie/poetry/index.php" method="post">
                        <label for="poemName">Название</label><br>
                        <input type="text" name="poemName" id="poemName" <?php if (isset($poemName)) {
                                                                                echo "value='$poemName'";
                                                                            } elseif (isset($poemInfo['poemName'])) {
                                                                                echo "value='$poemInfo[poemName]'";
                                                                            } ?> readonly><br>
                        <label for="poemText">Тест произведения</label><br>
                        <textarea name="poemText" id="poemText" readonly><?php if (isset($poemText)) {
                                                                                echo $poemText;
                                                                            } elseif (isset($poemInfo['poemText'])) {
                                                                                echo $poemInfo['poemText'];
                                                                            } ?></textarea><br>
                                                                            <label for="poemAuthor">Автор</label><br>
                        <input type="text" name="poemAuthor" id="poemAuthor" <?php if (isset($poemAuthor)) {
                                                                                echo "value='$poemAuthor'";
                                                                            } elseif (isset($poemInfo['poemAuthor'])) {
                                                                                echo "value='$poemInfo[poemAuthor]'";
                                                                            } ?> readonly><br>
                        <label for="poemDate">Дата написания</label><br>
                        <input type="text" name="poemDate" id="poemDate" <?php if (isset($poemDate)) {
                                                                                        echo  "value='$poemDate'";
                                                                                    } elseif (isset($poemInfo['poemDate'])) {
                                                                                        echo  "value='$poemInfo[poemDate]'";
                                                                                    } ?>readonly><br>
                        <label for="poemImage">Изображение</label><br>
                        <input type="text" name="poemImage" id="poemImage" <?php if (isset($poemImage)) {
                                                                                echo "value='$poemImage'";
                                                                            } elseif (isset($poemInfo['poemImage'])) {
                                                                                echo "value='$poemInfo[poemImage]'";
                                                                            } ?> value="/images/no-image.png" readonly><br>
                        <input type="submit" name="submit" value="Удалить произведение">
                        <input type="hidden" name="action" value="deletePoem">
                        <input type="hidden" name="poemId" value="<?php if (isset($poemInfo['poemId'])) {
                                                                        echo $poemInfo['poemId'];
                                                                    } elseif (isset($poemId)) {
                                                                        echo $poemId;
                                                                    } ?>">
                    </form>
                </div>
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