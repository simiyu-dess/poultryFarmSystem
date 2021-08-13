<nav class="navbar">
        <div class="nav_icon" >
          <i class="fa fa-bars" aria-hidden="true"></i>
        </div>
        <div class="navbar__left">
          <a href="eggsSummary.php">Eggs</a>
          <a href="birdsSummary.php">Birds</a>
          <a href="feedSummary.php">Feed</a>
          <a href="incomes.php">Accounting</a>
         
        </div>
        <div class="navbar__right">
            <a href="setUser.php"> Settings</a>
            <h1 style="font-size: 15px; color: green; color: #2e4a66; margin-right: 10px;">
            <?php echo 'Logged in as ' . $_SESSION["Username"]; ?></h1>
        </div>
      </nav>