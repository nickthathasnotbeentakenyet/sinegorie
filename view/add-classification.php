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
    <title>Синегорье | Добавить Жанр</title>
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
            <main class="addClassification">
                <h1>Добавить Жанр</h1>
                <?php
                if (isset($message)) {
                    echo $message;
                }
                ?>
                <div>
                    <form action="/sinegorie/poetry/index.php" method="post">
                        <label for="classificationName">Название Жанра</label><br>
                        <span class="hint">Пожалуйста, ограничтесь 30 символами</span><br>
                        <input type="text" name="classificationName" id="classificationName" required pattern="[а-яё]+" placeholder="жанр"><br>
                        <input type="submit" name="submit" id="addClassification" value="Добавить жанр">
                        <input type="hidden" name="action" value="addClassification">
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