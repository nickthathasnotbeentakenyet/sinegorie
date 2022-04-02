<?php
if (!$_SESSION['loggedin'] || $_SESSION['clientData']['clientLevel'] < 3) {
    header('Location: /sinegorie/');
    exit;}
?><?php
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
}
?><!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Синегорье | Управление Изображениями</title>
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
    <script src="https://rawgit.com/thielicious/selectFile.js/master/selectFile.js"></script>
    <script src="../js/uploadimage.js"></script>
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
            <main class="imageUploadView">
        <h1>Управление Изображениями</h1>
        <p class="hint">Подалуйста, выберите одну из опций ниже</p>
        <h2>Добавить изображение к произведению</h2>
        <?php
        if (isset($message)) {
            echo $message;
        } ?>

        <form action="/sinegorie/uploads/" method="post" enctype="multipart/form-data">
            <label for="poemId">Произведение</label><br>
            <?php echo $prodSelect; ?>
            <fieldset class="form_toggle">
                <!-- <label>Это <b id="TealColor">главное</b> изображение для данного произведения?</label> -->
                <!-- <div class="form_toggle-item item-1"> -->
                    <!-- <input type="radio" name="imgPrimary" id="priYes" value="1"> -->
                    <input type="hidden" name="imgPrimary" id="priYes" value="1">
                    <!-- <label for="priYes">Да</label> -->
                <!-- </div> -->
                <!-- <div class="form_toggle-item item-2">
                    <input type="radio" name="imgPrimary" id="priNo" checked value="0">
                    <label for="priNo">Нет</label>
                </div> -->
            </fieldset>
            <fieldset>
                <label for="fupload" hidden>Выбрать</label>
                <input type=file hidden id=fupload name="file1" accept=".jpg, .png, .gif">
                <input type=button onClick=getFile.simulate() value="Выбрать">
                <label id=selected>Ничего не выбрано</label><br>
                <input type="submit" value="Upload" id="uploadImage">
                <input type="hidden" name="action" value="upload">
            </fieldset>
        </form>
        <hr>
        <h2>Управление существующими изображениями</h2>
        <p class="warning">Удаляя главное изображение, удаляйте также и соответствующие ему эскизы.</p>
        <?php
        if (isset($imageDisplay)) {
            echo $imageDisplay;
        } ?>
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
<?php unset($_SESSION['message']); ?>