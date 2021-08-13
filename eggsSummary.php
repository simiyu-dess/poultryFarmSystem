<?php
   include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/includes/action.php";

   include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/functions.php";
   
   checkLogin();
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
                    <!-- <img src="images/hello.svg" alt=""> -->
                    <div class="main__greeting">
                        <h1>Eggs Summary</h1>
                    </div>
                </div>
                <!-- dashboard title ends here -->

                <!-- Div for containing Feed Summary: Feed Consumed and Feed Remaining -->
                
                <canvas id="piechart_3d" style="width: 900px; height: 500px;"></canvas>
                                
                <!-- End of Div for containing Feed Summary -->

            </div>
        </main>
        
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php";?>
    </div>
    <script src="script.js"></script>
    <script src = "drawpychart.js"></script>
    <script type="text/javascript">
    var remaining_eggs = '<?php echo $remainingEggs;?>';
    var sold_eggs = '<?php echo $totalEggsSold;?>';
    var data = [ [ "eggs sold",parseInt(sold_eggs) ], [ "eggs remaining", parseInt(remaining_eggs)]]; 
    var colors = [ "#c23410", "#0860e4"];  
  
// using the function  
     drawPieChart( data, colors, "Eggs sold vs Eggs remaining",900,500);  
   
    </script>
</body>
</html>