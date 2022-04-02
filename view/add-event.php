<?php
if (!$_SESSION['loggedin'] || $_SESSION['clientData']['clientLevel'] < 3) {
    header('Location: /sinegorie/');
    exit;
}
?><!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление событиями</title>
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
                <h1>Добавить Событие</h1>
                <?php
                if (isset($message)) {
                    echo $message;
                }
                ?>
                <div>
                    <form action="/sinegorie/events/index.php" method="post">
                        <label for="eventName">Название События</label><br>
                        <input type="text" name="eventName" id="eventName" required><br>
                        <label for="eventText">Описание</label><br>
                        <textarea name="eventText" id="eventText" required></textarea><br>
                        <label for="eventDate">Дата</label><br>
                        <input type="date" name="eventDate" id="eventDate"><br>
                        <label for="eventTime">Время</label><br>
                        <input type="time" name="eventTime" id="eventTime"><br>
                        <input type="submit" name="submit" id="addEvent" value="Добавить событие">
                        <input type="hidden" name="action" value="newEvent">
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