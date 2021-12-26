<?php
// Build a select drop-down menu
$classificationList = "<br><label for='classificationId'>Выбирите жанр</label><br>";
$classificationList .= "<select name='classificationId' id='classificationId' required>";
$classificationList .= "<option value='' disabled selected>Не выбрано</option>";
foreach ($classifications as $classification) {
    $classificationList .= "<option  value='$classification[classificationId]'";
    if (isset($classificationId)) {
        if ($classification['classificationId'] === $classificationId) {
            $classificationList .= ' selected ';
        }
    }
    $classificationList .= ">$classification[classificationName]</option>";
}
$classificationList .= '</select>';

?>
<?php
if (!$_SESSION['loggedin'] || $_SESSION['clientData']['clientLevel'] == 1) {
    header('Location: /sinegorie/');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Синегорье | Добавить произведение</title>
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
            <main class="addPoem">
                <h1>Добавить произведение</h1>
                <?php
                if (isset($message)) {
                    echo $message;
                }
                ?>
                <div>
                    <form action="/sinegorie/poetry/index.php" method="post">
                        <?php echo $classificationList; ?><br>
                        <label for="poemName">Название</label><br>
                        <input type="text" name="poemName" id="poemName" <?php if (isset($poemName)) {
                                                                                echo "value='$poemName'";
                                                                            } ?> required><br>
                        <label for="poemText">Текст произведения</label><br>
                        <textarea name="poemText" id="poemText" required><?php if (isset($poemText)) {
                                                                                echo $poemText;
                                                                            } ?></textarea><br>
                        <label for="poemImage">Изображение</label><br>
                        <input type="text" name="poemImage" id="poemImage" <?php if (isset($poemImage)) {
                                                                                echo "value='$poemImage'";
                                                                            } ?>><br>
                        <label for="poemDate">Дата</label><br>
                        <input type="text" name="poemDate" id="poemDate" <?php if (isset($poemDate)) {
                                                                                echo "value='$poemDate'";
                                                                            } ?>><br>
                        <input type="submit" name="submit" id="addPoem" value="Добавить произведение">
                        <input type="hidden" name="action" value="addPoem">
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