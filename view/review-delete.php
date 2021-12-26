<?php 
if(!$_SESSION['loggedin']){
    header('Location: /sinegorie/');
    exit;
   }
?><!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Синегорье | Удаление рецензии</title>
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
        <p id="emptyAddClass">Вы уверены, что хотите удалалить рецензию? Это действие необратимо.</p>
        <?php
            if (isset($message)) {
            echo $message;
            }
        ?>
        <div>
            <form action="/sinegorie/reviews/index.php" method="post"> 
                <label for="reviewText">Текст Рецензии</label><br>
                <textarea name="reviewText" id="reviewText" readonly ><?php if(isset($displaySpecReview)){echo $displaySpecReview;} ?></textarea><br>
                <input type="submit" name="submit" value="Удалить">
                <input type="hidden" name="action" value="deleteReview">
                <input type="hidden" name="reviewId" value="<?php if(isset($specReview['reviewId'])){ echo $specReview['reviewId'];} 
                elseif(isset($reviewId)){ echo $reviewId; } ?>">
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