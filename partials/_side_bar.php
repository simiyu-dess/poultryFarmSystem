<div id="sidebar">
        <div class="sidebar__title">
          <div class="sidebar__img">
            <h1>KPMS</h1>
          </div>
          <i
            onclick="closeSidebar()"
            id="sidebarIcon"
            aria-hidden="true"
          ></i>
        </div>

        <div class="sidebar__menu" id="sidebarDiv">
          <div class="sidebar__link active_menu_link">
            <a href="dashboard.php">Dashboard</a>
          </div>
          <?php if($_SESSION['perm_eggs'] == 1 || $_SESSION['perm_admin'] == 1): ?>
          <h2>EGGS</h2>
          <div class="sidebar__link">
            <a href="production.php">Production</a>
          </div>
          <?php endif ?>
          <?php if($_SESSION['perm_sales'] == 1 || $_SESSION['perm_admin'] == 1):?>
          <div class="sidebar__link">
            <a href="sales.php">Sales</a>
          </div>
          <?php endif ?>
          <?php if($_SESSION['perm_birds'] == 1 || $_SESSION['perm_admin'] == 1):?>
          <h2>BIRDS</h2>
          <div class="sidebar__link">
            <a href="birdsPurchase.php">Purchase</a>
          </div>
          
          <div class="sidebar__link">
            <a href="birdsMortality.php">Mortality</a>
          </div>
          <?php endif ?>
          <?php if($_SESSION['perm_feeds'] == 1 || $_SESSION['perm_admin'] == 1) :?>
          <h2>FEED</h2>
          
          
          <div class="sidebar__link">
            <a href="feedConsumption.php">Consumption</a>
          </div>
          
          <?php if($_SESSION['perm_purchase'] == 1 || $_SESSION['perm_admin'] == 1):?>
            
          <div class="sidebar__link">
            <a href="feedPurchase.php">Purchase</a>
          </div>
          <?php endif ?>
          <?php endif ?>
          <?php if($_SESSION['perm_medicine'] == 1  ):?>
            <h2>MEDICINE</h2>
          <div class="sidebar__link">
            <a href="MedicineConsumption.php">Consumption</a>
          </div>
          
          <?php if($_SESSION['perm_purchase'] == 1 || $_SESSION['perm_admin'] == 1):?>
          <div class="sidebar__link">
            <a href="MedicinePurchase.php">Purchase</a>
          </div>
          <?php endif ?>
         
          <?php endif ?>
          <?php if ($_SESSION['perm_admin'] == '1'):?>
          <h2>EMPLOYEES</h2>
          <div class="sidebar__link">
            <a href="currentEmployees.php">Employees</a>
          </div>
          <div class="sidebar__link">
          <a href="newEmployee.php"> New Employee</a>
          </div>
          <?php endif ?>
          <div class="sidebar__logout">
            <a href="logout.php">Log out</a>
          </div>
        </div>
      </div>