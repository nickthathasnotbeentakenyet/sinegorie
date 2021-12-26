<!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Синегорье | Произведения</title>
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
                <section  class='poemContent'>
                    <?php if (isset($message)) {echo $message;}
                    ?>
                    <div>
                        <?php if (isset($poemDisplay)) {echo $poemDisplay;} ?>
                    </div>
                </section>
                <hr>
        <section class="review" id="review">
        <h2>Рецензии</h2>
        <?php
        if (isset($msgNoReviews)) {
            echo $msgNoReviews;
        }
        if (isset($displayReviews)) {
            echo $displayReviews;
        }
        ?>
        <?php
        if (!isset($_SESSION['loggedin'])) {
            echo '<div class="flexyRight">';
            echo '<p id="reviewLogin"><a href="/sinegorie/accounts?action=login-view">Войти в аккаунт</a>, чтобы оставить рецензию</p>';
            echo '<a href="#top" class="updown"></a>';
            echo '</div>';
            echo '</section>';
            echo '</main>';
            exit;
        }
        ?>
        <h3>Добавить рецензию</h3>
       <form action="/sinegorie/reviews/index.php" method="POST" class="review-form">
            <label for="screenname">Пользователь:</label><br>
            <input name="screenname" id="screenname" readonly value="<?php echo $_SESSION['clientData']['clientLogin']; ?> "><br>
            <label for="reviewText">Текст Рецензии: </label><br>
            <textarea name="reviewText" id="reviewText" required></textarea><br>
            <div class="flexy">
            <input type="submit" name="submit" id="review-submit" value="Опубликовать">
            <a href="#top" class="updown"></a>
            </div>
            <input type="hidden" name="action" value="newReview">
            <input type="hidden" name="clientId" value="<?php 
            if (isset($_SESSION['clientData']['clientId'])) {echo $_SESSION['clientData']['clientId'];} 
            elseif (isset($clientId)) {echo $clientId;} ?>">
            <input type="hidden" name="poemId" value="<?php if (isset($poemId)) {echo $poemId;} ?>">
        </form>
        </section>
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