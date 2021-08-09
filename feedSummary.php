<?php
    session_start();
    include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/classes.php";
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
                        <h1>Feed Stock Summary</h1>
                    </div>
                </div>
                <!-- dashboard title ends here -->

                <!-- Div for containing Feed Summary: Feed Consumed and Feed Remaining -->
                
                <canvas id="piechart_3d"></canvas>
                                
                <!-- End of Div for containing Feed Summary -->

            </div>
        </main>
        
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php";?>
    </div>
    <script src="script.js"></script>
    <script src = "drawpychart.js"></script>
    <script type="text/javascript">
    var remainingFeed = '<?php echo $remainingFeed;?>';
    var feedConsumed = '<?php echo $totalFeedConsumed;?>';
    var data = [ [ "feed consumed",parseInt(feedConsumed) ], [ "feed remaing", parseInt(remainingFeed)]]; 
    var colors = [ "#c23410", "#0860e4"];  
  
// using the function  
     drawPieChart( data, colors, "Remaining feed vs Total consumed",900,500)
    </script>
</body>
</html>