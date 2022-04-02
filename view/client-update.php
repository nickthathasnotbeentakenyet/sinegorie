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
    <title>Синегорье | Управление аккаунтом</title>
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
    <script src="/sinegorie/js/uploadimage.js"></script>
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
            <main class="clientUpdate">
                <h1>Управление аккаунтом</h1>
                <?php
                    if (isset($message)) {
                        echo $message;
                    }
                    ?>
                <div class="cug">    
                <section>
                    <h2>Обновить данные</h2>
                    <form class="clientUpdate-form" action="/sinegorie/accounts/index.php" method="post">
                        <label for="clientLogin">Логин</label><br>
                        <input type="text" name="clientLogin" id="clientLogin" value='<?php if (isset($_SESSION['clientData']['clientLogin'])) {
                                                                                            echo $_SESSION['clientData']['clientLogin'];
                                                                                        } ?>' required><br>
                        <label for="clientEmail">Email</label><br>
                        <input type="email" name="clientEmail" id="clientEmail" value="<?php if (isset($_SESSION['clientData']['clientEmail'])) {
                                                                                            echo $_SESSION['clientData']['clientEmail'];
                                                                                        } ?>" required><br>
                        <br>
                        <input type="submit" name="submit" id="updClient" class="updClient1" value="Отправить">
                        <input type="hidden" name="action" value="updateAccountInfo">
                        <input type="hidden" name="clientId" value="<?php if (isset($_SESSION['clientData']['clientId'])) {
                                                                        echo $_SESSION['clientData']['clientId'];
                                                                    } elseif (isset($clientId)) {
                                                                        echo $clientId;
                                                                    } ?>">
                    </form>
                </section>
                <section>
                    <h2>Обновить пароль</h2>
                    <?php
                    if (isset($messageNoPasswd)) {
                        echo $messageNoPasswd;
                    } ?>
                    <form class="clientUpdate-form" action="/sinegorie/accounts/index.php" method="post">
                        <p id="passwordWarning">Внимание! Текущий пароль будет изменен</p>
                        <span class="hint">*минимум 8 знаков, включая: 1 цифру, 1 заглавную and 1 спец.символ)</span><br>
                        <label for="clientPassword">Пароль</label><br>
                        <input type="password" name="clientPassword" id="clientPassword" required pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"><br>
                        <br>
                        <input type="submit" name="submit" id="updPassword" class="updClient2" value="Сменить пароль">
                        <input type="hidden" name="action" value="updatePassword">
                        <input type="hidden" name="clientId" value="<?php if (isset($_SESSION['clientData']['clientId'])) {
                                                                        echo $_SESSION['clientData']['clientId'];
                                                                    } elseif (isset($clientId)) {
                                                                        echo $clientId;
                                                                    } ?>">
                    </form>
                </section>
                <section>
                    <h2>Обновить Аватару</h2>
                    <form class="clientUpdate-form" action="/sinegorie/accounts/index.php" method="post" enctype="multipart/form-data">
                    <label for="fupload" hidden></label>
                    <input type=file hidden id=fupload name="fupload" accept=".jpg, .png, .gif">
                    <p class="hint">Выберите аватар. Изображение должно быть формата jpg, png или gif:</p>
                    <input type=button onClick=getFile.simulate() value="Выбрать">
                    <label id=selected>Ничего не выбрано</label><br>
                    <input type="submit" value="Обновить Аватару" id="uploadImage">
                    <input type="hidden" name="action" value="updateAvatar">
                    <input type="hidden" name="clientId" value="<?php if (isset($_SESSION['clientData']['clientId'])) {
                                                                            echo $_SESSION['clientData']['clientId'];
                                                                        } elseif (isset($clientId)) {
                                                                            echo $clientId;
                                                                        } ?>">
                    </form>
                </section>
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