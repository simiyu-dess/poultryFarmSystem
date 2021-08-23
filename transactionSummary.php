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
                
                <div class="main__title">
                    
                    <div class="main__greeting">
                        <h1>Transaction Summary For Last 60 days</h1>
                    </div>
                </div>
              
                
                <canvas id="piechart_3d"></canvas>
                                
                

            </div>
        </main>
        
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php";?>
    </div>
    <script src="script.js"></script>
    <script src = "drawpychart.js"></script>
    <script type="text/javascript">
    var expense = '<?php echo $totalExpense;?>';
    var income = '<?php echo $totalRevenue;?>';
    var data = [ [ "expense",parseInt(expense) ], [ "revenue", parseInt(income)]]; 
    var colors = [ "#c23410", "#0860e4"];  
  
// using the function  
     drawPieChart( data, colors, "Expense VS Income",900,500)
    </script>
</body>
</html>