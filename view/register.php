<!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Синегорье | Регистрация</title>
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
            <main class="register-view">
                <h1>Регистрация</h1>
                <?php
                if (isset($message)) {
                    echo $message;
                }
                ?>
                <form class="register-form" action="/sinegorie/accounts/index.php" method="post">
                    <label for="clientLogin">Логин</label><br>
                    <input type="text" name="clientLogin" id="clientLogin" <?php if (isset($clientLogin)) {
                                                                                echo "value='$clientLogin'";
                                                                            }  ?> required placeholder="Имя"><br>
                    <label for="clientEmail">Email</label><br>
                    <input type="email" name="clientEmail" id="clientEmail" <?php if (isset($clientEmail)) {
                                                                                echo "value='$clientEmail'";
                                                                            }  ?> required placeholder="пример@mail.com"><br>
                    <label for="clientPassword">Пароль</label><br>
                    <span class="hint">*минимум 8 знаков, включая: 1 цифру, 1 заглавную and 1 спец.символ)</span><br>
                    <input type="password" name="clientPassword" id="clientPassword" required pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"><br>
                    <input type="submit" name="submit" class="reg" value="Зарегистрироваться">
                    <input type="hidden" name="action" value="register-procedure">
                </form>
                <a href="/sinegorie/accounts/index.php?action=login-view">Войти в аккаунт</a>
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