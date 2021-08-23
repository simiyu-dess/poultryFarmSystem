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
    <script src = "drawpychart.js"></script>
    <script type="text/javascript">
    var feedExpense = '<?php echo $feedExpense;?>';
    var birdseExpense = '<?php echo $birdsExpense;?>';
    var medicineeExpense = '<?php echo $medicineExpense;?>';
    var wagesExpense = '<?php echo $totalWages; ?>';
    var otherExpense = '<?php echo $expense; ?>';
    var data = [ [ "birds expense", parseInt(birdsExpense)],
                 [ "feeds expense", parseInt(feedExpense)],
                 
                 [ "medicine expense", parseInt(medicineExpense)],
                 [ "wages expense", parseInt(wagesExpense)],
                 [ "other expenses", parseInt(otherExpense)]];
                
                 

    var colors = [ "#c23410", "#0860e4",  "#cfd308d5", "#f38630", "#fff"];  
  
// using the function  
     drawPieChart( data, colors, "Expense VS Income",900,500)
    </script>
</body>
</html>