<?php
    session_start();
    if (!isset($_SESSION['Username'])) {
        header("Location: index.php");
        exit();
    }
    include 'includes/database.php';
    include 'includes/action.php';
    $query = "SELECT * FROM `Employee`";
    $result_emp = $databaseObject->connect()->query($query);

    
?>
<!DOCTYPE html>
<html lang="en">
<!-- head -->
<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_head.php";?>
<body id="body">
    <div class="container">
        <!-- top navbar -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_top_navbar.php";?>
        <main>
            <div class="main__container">
                <!-- dashboard title and greetings -->
                <div class="main__title">
                </div>
                <!-- dashboard title ends here -->

                <!-- Cards for displaying CRUD insights -->
                <!--
                <div class="main__cards">
                    <div class="card">
                        <div class="card_inner">
                            <p class="text-primary-p">No. of Birds</p>
                            <span class="font-bold text-title">578</span>
                            <span class="font-bold text-title">
                                <?php
                                    echo $totalNumberOfBirds;
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card_inner">
                            <p class="text-primary-p">Mortality Rate</p>
                            <span class="font-bold text-title">578</span> 
                            <span class="font-bold text-title">
                                <?php
                                    echo $mortalityRate . '%';
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card_inner">
                            <p class="text-primary-p">No. of Eggs</p>
                             <span class="font-bold text-title">578</span> 
                            <span class="font-bold text-title">
                                <?php
                                    echo $totalNumberOfEggs;
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card_inner">
                            <p class="text-primary-p">No. of Employees</p>
                            <span class="font-bold text-title">578</span> 
                            <span class="font-bold text-title">
                                <?php
                                    echo $totalNumberOfEmployees;
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                 End of cards -->
                <!-- Start of charts-->
                <div class="main_card">

                    <div class="charts__right">
                        <div class="charts__right__title">
                        </div>

                        <div class="charts__right__cards">
                            <div class="card">
                            <div class="card1">
                            <h1>Total Wages</h1>
                            <p><?php echo 'KSH'. $totalWages; ?></p>
                        </div>
                        </div>
                       <div class="card">
                        <div class="card2">
                            <h1>Revenue</h1>
                            <p><?php echo 'KSH'. $sales; ?></p>
                        </div>
                        </div>
                          
                         <div class="card"> 
                        <div class="card3">
                            <h1>Remaining Feed</h1>
                            <?php
                                if($remainingFeed > 0){ ?>
                                    <p><?php echo $remainingFeed . ' Kg'; ?></p>
                                <?php
                                }else{?>
                                    <p style="color: red;"><?php echo 'Please refill the feed stock!'; ?></p>
                                <?php
                                }
                                ?>
                        </div>
                        </div>
                       
                       <div class="card">
                        <div class="card4">
                            <h1>Eggs Left</h1>
                            <?php
                                if($remainingEggs > 0){ ?>
                                    <p><?php echo $remainingEggs; ?></p>
                                <?php
                                }else{?>
                                    <p style="color: red;"><?php echo 'Nothing to sell!'; ?></p>
                                <?php
                                }
                                ?>
                        </div>
                        </div>
                    <div class="card">
                        <div class="card5">
                            <h1>No. birds</h1>
                            <p><?php echo 'KSH'. $totalNumberOfBirds; ?></p>
                        </div>
                        </div>
                            <div class="card">
                            <div class="card6">
                            <h1>Mortality rate:</h1>
                            <p><?php echo  $mortalityRate.' %'; ?></p>
                        </div>
                        </div>
                       
                    <div class="card">
                        <div class="card7">
                            <h1>No. of employee</h1>
                            <p><?php echo $totalNumberOfEmployees; ?></p>
                        </div>
                        </div>
                   <div class="card">
                        <div class="card8">
                            <h1>Total Wages</h1>
                            <p><?php echo 'KSH'. $totalWages; ?></p>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- End of charts-->
            </div>
        </main>
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php"; ?>
    </div>
    <script src = "drawpychart.js"></script>
    <script type="text/javascript">
    var data = [];
    <?php while($row = mysqli_fetch_assoc($result_emp))
    {?>
    data.push(['<?php echo $row['Job'];?>',<?php echo $row['Salary'];?>]);
    <?php }?>
    var colors = [ "#c23410"];  
  
// using the function  
     drawPieChart( data, colors, "",450,250)
    </script>
    <script src="script.js"></script>
</body>
</html>