<?php
if (!$_SESSION['loggedin']) {
    header('Location: /sinegorie/');
}
?>
<!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Синегорье | Личный Кабинет</title>
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
            <main class="admin-view">
                <section class="lc">
                <h1>Добро пожаловать, <?php echo $_SESSION['clientData']['clientLogin'];
                                        echo " "; ?></h1>
                <?php if (isset($_SESSION['message'])) {
                    echo $_SESSION['message'];
                } ?>
                <ul class="userInfo">
                    <li>Логин: <?php echo $_SESSION['clientData']['clientLogin']; ?></li>
                    <li>Email: <?php echo $_SESSION['clientData']['clientEmail']; ?></li>
                    <li>Статус: <?php if(isset($clientStatus)) echo $clientStatus; ?></li>
                    <li>Аватар:<br> <?php echo "<img src="; echo $_SESSION['clientData']['clientImage'];echo">"; ?></li>
                    <li><?php if(isset($displayFriends))echo $displayFriends ?></li>
                </ul>
                </section>
                <section class="lc">
                <h2>Управление сообщениями</h2>
                <a class="admin-link" href="/sinegorie/messages/?action=messenger-view">Открыть почтовый ящик<?php if(isset($messageCounter)){ if($messageCounter < 1){echo "";} else { echo ' (<span class="msgCounter">'; echo $messageCounter;echo'</span>)';}}?></a>
                </section>
                <section class="lc">
                <h2>Управление аккаунтом</h2>
                <a class="admin-link" href="/sinegorie/accounts/?action=accountUpdate-view">Перейти к обновлению данных аккаунта</a>
                </section>
                <?php if ($_SESSION['clientData']['clientLevel'] > 2) {
                    echo '<section class="lc">';
                    echo '<h2>Управление контентом</h2>';
                    echo '<a class="admin-link" href="/sinegorie/poetry/">Перейти к управлению поэзией</a>';
                    echo '</section>';
                    echo '<section class="lc">';
                    echo '<h2>Управление изображениями произведений</h2>';
                    echo '<a class="admin-link" href="/sinegorie/uploads/">Перейти к управлению изображениями</a>';
                    echo '</section>';
                    echo '<section class="lc">';
                    echo '<h2>Управление событиями</h2>';
                    echo '<a class="admin-link" href="/sinegorie/events/">Перейти к управлению событиями</a>';
                    echo '</section>';
                }
                if ($_SESSION['clientData']['clientLevel'] > 3) {
                    echo '<section class="lc">';
                    echo '<h2>Управление пользователями</h2>';
                    echo '<a class="admin-link" href="/sinegorie/accounts/?action=clientPrivilege-view">Перейти к управлению пользователями</a>';
                    echo '</section>';
                } ?>
                <section>
                <h2>Управление рецензиями</h2>
                <?php
                if (isset($reviewsDisplay)) {
                    echo "<details><summary>Список рецензий</summary>";
                    echo $reviewsDisplay;
                    if (empty($accountReviews)){
                        echo "<p class='failure'>К сожалению, у вас пока нет рецензий</p>";
                    }
                    echo "</details>";
                }
                ?>
                </section>
                <br><br>
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