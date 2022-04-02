<?php
if (!$_SESSION['loggedin'] || $_SESSION['clientData']['clientLevel'] < 3) {
    header('Location: /sinegorie/');
    exit;}
?><!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Синегорье | Удаление события</title>
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
        <h1>Удаление рецензии</h1>
        <p id="emptyAddClass">Вы уверены, что хотите удалалить событие? Это действие необратимо.</p>
        <?php
            if (isset($message)) {
            echo $message;
            }
        ?>
        <div>
            <form action="/sinegorie/events/index.php" method="post"> 
                <label for="eventName">Текст Рецензии</label><br>
                <input type="text" name="eventName" readonly value="<?php if (isset($eventData['eventName']))echo $eventData['eventName']?>">
                <label for="eventText">Описание</label><br>
                <textarea name="eventText" id="eventText" readonly ><?php if(isset($eventData['eventText'])){echo $eventData['eventText'];} ?></textarea><br>
                <label for="eventDate">Дата</label><br>
                <input type="date" name="eventDate" readonly value="<?php if (isset($eventData['eventDate']))echo $eventData['eventDate']?>">
                <input type="submit" name="submit" value="Удалить">
                <input type="hidden" name="action" value="deleteEvent">
                <input type="hidden" name="eventId" value="<?php if(isset($eventData['eventId'])){ echo $eventData['eventId'];}?>">
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