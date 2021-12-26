<header>
    <div class="hLeft">
    <img id="logo" src="/sinegorie/images/logo.png" alt="Синегорье | логотип">
    <h1>
        Синегорье
    </h1>
    </div>
    <div class="hRight">
        <!-- <?php if ($_SESSION['loggedin']) { echo '<a href="/sinegorie/accounts/index.php?action=admin" id="welcomeMessage">'; echo $_SESSION['clientData']['clientLogin']; echo'</a>';}?>
        <?php if (!$_SESSION['loggedin']) { echo '<a href="/sinegorie/accounts/index.php?action=login-view" id="myaccount">Войти</a>'; }?>
        <?php if ($_SESSION['loggedin']) { echo '<a href="/sinegorie/accounts/index.php?action=logout" id="logout"> Выйти</a>'; }?> -->
        
        
        <?php if (isset($_SESSION['clientData'])){echo '<a href="/sinegorie/accounts/index.php?action=admin" id="welcomeMessage">'; echo $_SESSION['clientData']['clientLogin']; echo'</a>';}?>
        <?php if (!isset($_SESSION['clientData'])){echo '<a href="/sinegorie/accounts/index.php?action=login-view" id="myaccount">Войти</a>'; }?>
        <?php if (isset($_SESSION['clientData'])){echo '<a href="/sinegorie/accounts/index.php?action=logout" id="logout"> Выйти</a>';} ?>
    </div>
</header>