<?php
if (!$_SESSION['loggedin'] || $_SESSION['clientData']['clientLevel'] < 4) {
    header('Location: /sinegorie/');
    exit;}
?><!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление привилегиями</title>
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
            <main class="privilege-page">
                <h1>Управление пользователями</h1>
                <?php
                    if (isset($message)) {
                        echo $message;
                    }
                    ?>
                    <form class="userPrivilege-form" action="/sinegorie/accounts/index.php" method="post">
                        <p>
                            <span style="color: black;">[Заблокированный] - чтение </span><br>
                            <span style="color: #4c96d7;">[Читатель] - чтение | рецензии </span><br>
                            <span style="color: teal;">[Модератор] - чтение | рецензии | управление [жанры, произведения, события] </span><br>
                            <span style="color: crimson;">[Администратор] - полный доступ</span>
                        </p><br>
                        <label for="clientLogin" id="user">Пользователь</label><br>
                        <?php if(isset($usersDisplay))echo $usersDisplay;?><br><br>
                        <label for="clientlevel" id="newPrivilege">Новые привилегии</label><br>               
                            <div class="level-conteiner button-wrap">
                                <input type="radio" name="clientLevel" id="reader" class="hidden radio-label" value="2">
                                <label for="reader" class="button-label"><h1>Читатель</h1></label>
                                <input type="radio" name="clientLevel" id="moder" class="hidden radio-label" value="3">
                                <label for="moder" class="button-label"><h1>Модератор</h1></label>
                                <input type="radio" name="clientLevel" id="admin" class="hidden radio-label" value="4">
                                <label for="admin" class="button-label"><h1>Администратор</h1></label>
                                <input type="radio" name="clientLevel" id="ban" class="hidden radio-label" value="1">
                                <label for="ban" class="button-label"><h1>Заблокировать</h1></label>
                            </div>
                        <input type="submit" name="submit" value="Подтвердить">
                        <input type="hidden" name="action" value="setClientPrivilege">
                    </form>
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