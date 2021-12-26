<?php
if (!$_SESSION['loggedin'] || $_SESSION['clientData']['clientLevel'] == 1) {
    header('Location: /sinegorie/');
    exit;
}
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
}
?>
<!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Синегорье | Управление контентом</title>
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
            <main class="poetryManagement">
                <h1>Управление поэзией</h1>
                <section>
                <h2>Добавить контент</h2>
                <div>
                    <a href="/sinegorie/poetry/index.php?action=addClassification-view">Добавить Жанр</a><br>
                    <a href="/sinegorie/poetry/index.php?action=addPoem-view">Добавить произведение</a>
                </div>
                </section>
                <?php
                if (isset($message)) {
                    echo $message;
                }
                if (isset($classificationList)) {
                    echo '<section>';
                    echo '<div>';
                    echo '<h2>Управление произведениями</h2>';
                    echo '<p>Произведения по жанрам</p>';
                    echo $classificationList;
                    echo '</div>';
                    echo '</section>';
                }
                ?>
                <noscript>
                    <p><strong>Пожалуйста, включите JavaScript для корректной работы сайта</strong></p>
                </noscript>
                <table id="poemsDisplay"></table>
            </main>
        </div>
        <div class="footer">
            <footer>
                <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/sinegorie/snippets/footer.php'; ?>
            </footer>
        </div>
    </div>
    <script src="/sinegorie/js/hamburger.js"></script>
    <script src="/sinegorie/js/poetry.js"></script>
</body>

</html>