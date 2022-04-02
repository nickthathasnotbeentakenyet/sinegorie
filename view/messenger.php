<?php
// All users SELECTOR
$receivers = getAllUsers();
$clientsList = "<br><label for='clientId'>Выберите получателя</label><br>";
$clientsList .= "<select name='receiverId' id='receiverId' required>";
$clientsList .= "<option value='' disabled selected>Не выбрано</option>";
foreach ($receivers as $receiver) {
    $clientsList .= "<option  value='$receiver[clientId]'";
    if (isset($receiver)) {
        if ($receiver['clientId'] === $receiver) {
            $clientsList .= ' selected ';
        }
    }
    $clientsList .= ">$receiver[clientLogin]</option>";
}
$clientsList .= '</select>';

// Friend SELECTOR
$clientId = $_SESSION['clientData']['clientId'];
$friendRecipient = getFriendListToMsg($clientId);
$frList = "<br><label for='clientId'>Выберите друга</label><br>";
$frList .= "<select name='receiverId' id='receiverId' required>";
$frList .= "<option value='' disabled selected>Не выбрано</option>";
foreach ($friendRecipient as $recipient) {
    $frList .= "<option  value='$recipient[clientId]'";
    if (isset($recipient)) {
        if ($recipient['clientId'] === $recipient) {
            $frList .= ' selected ';
        }
    }
    $frList .= ">$recipient[clientLogin]</option>";
}
$frList .= '</select>';
?><!DOCTYPE html>
<html lang="ru-ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сообщения</title>
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
                <h1 id='mailboxh1'>Почтовый ящик</h1>
                <?php if(isset($message)) echo $message?>
                <div class="messenger-view">
                <section>
                    <h2>Входящие сообщения</h2>
                    <p class="checkMail"><a href="/sinegorie/messages/">Проверить почту</a></p>
                    <?php if(isset($dFReq))echo $dFReq ?>
                    <?php if(isset($displayIncoming))
                    echo $displayIncoming;
                    ?>
                </section>
                <section class="sendMsg">
                    <h2>Отправить сообщения</h2>
                    <form action="/sinegorie/messages/index.php" method="post">
                        <?php if(isset($clientsList)) echo $clientsList;?><br><br>
                        <?php if(isset($frList)) echo $frList;?><br><br>
                        <label for="messageText">Текст сообшения</label><br>
                        <textarea name="messageText" id="messageText" required><?php if (isset($messageText)) {echo $messageText;} ?></textarea><br>
                        <input type="submit" name="submit" id="newMessage" value="Отправить">
                        <input type="hidden" name="action" value="new-message">
                        <input type="hidden" name="senderId" value="<?php 
                        if (isset($_SESSION['clientData']['clientId'])) 
                        {
                            echo $_SESSION['clientData']['clientId'];
                        } 
                        elseif (isset($clientId)) 
                        { echo $clientId;} ?>">
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