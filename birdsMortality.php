<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}
include 'includes/database.php';
include 'includes/action.php';
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
                <?php if(isset($_SESSION['msg'])): ?>
                    <div class="msg">
                    <p>
                        <?php 
                            echo $_SESSION['msg'];
                            unset($_SESSION['msg']);
                        ?>
                    </p>
                    </div>
                <?php endif ?>
                <table>
                    <thead>
                        <th>Date</th>
                        <th>Number of Deaths</th>
                        <th colspan="2">Action</th>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $birdsMortalityObject->viewMethod("BirdsMortality");
                        $i = 0;
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['Date'];?></td>
                                <td><?php echo $row['Deaths'];?></td>
                                <td>
                            
                                    <a class="edit_btn" href="birdsMortality.php?birdsmortupdate=1&id=<?php echo $row["BirdsMortality_ID"]; ?>">Edit</a>
                                    
                                </td>
                                <td>
                                <a class="del_btn" href="includes/action.php?birdsmortdelete=1&id=<?php echo $row["BirdsMortality_ID"]; ?>">Delete</a>                    
                                </td>
                            </tr>
                            <?php
                            
                        } 
                    ?>
                    </tbody>
                </table>
                
                <?php
                    if(isset($_GET["birdsmortupdate"])){
                        // Get the id of the record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("BirdsMortality_ID" => $id);
                        // Call the select method that displays the record to be edited
                        $row = $birdsPurchaseObject->selectMethod("BirdsMortality", $where);
                        ?>
                            <div id="error" style="text-align: center; color:  #e65061;"></div>
                            <form id="form" action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input id="Date" type="date"  name="Date" value="<?php echo $row["Date"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorType"></div>
                                    <label for="">Birds Type</label>
                                    <input type="number" id="typeOfBirds" step="any" name="Deaths" value="<?php echo $row["Deaths"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorNumber"></div>
                                    <label for="">Number of Deaths</label>
                                    <input  type="number" id="numberOfDeaths" step="any" name="Deaths" value="<?php echo $row["Deaths"]; ?>">
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="birdsmortedit" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>
                            <div id="error" style="text-align: center; color:  #e65061;"></div>
                            <form id="form" action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input id="Date" type="date" max="<?php echo date('Y-m-d');?>" name="Date" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorType"></div>
                                    <label for="">Type of Birds</label>
                                    <input  type="text" id="typeOfBirds" name="Deaths" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorNumber"></div>
                                    <label for="">Number of Deaths</label>
                                    <input  type="number" step="any" id="numberOfDeaths" name="Deaths" value="">
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="birdsmortsave" class="btn">Save</button>
                                </div>
                            </form>
                        <?php
                    }
                        ?>
            </div>
        </main>
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php";?>
    </div>
    <script>
                    function validate(){
                        var dates = document.getElementById("Date").value;
                        var types = document.getElementById("typeOfBirds").value;
                        var number = document.getElementById("numberOfDeaths").value;
                        
                       
                        
                        // Getting error divs ID
                        var errordate = document.getElementById('errorDate');
                        var errortype = document.getElementById("errorType");
                        var errornumber = document.getElementById("errorNumber");
                        
                        
                        
                        var truth = true;
                        
                       
                    
                        if(number == " "){
                            errornumber.innerHTML = " This  field is required";
                            truth = false;
                        }
                        if(number < 1)
                        {
                            errornumber.innerHTML = "Number of deaths must be greater than zero";
                            truth = false;
                        }
                        if(dates == "")
                        {
                            errordate.innerHTML = "This field is required";
                            truth = false;
                        }
                        if(types == ""){
                            errortype.innerHTML = "This field is required";
                            truth = false;
                        }

                    


                        return truth;

                    }
                    </script>
</body>
</html>