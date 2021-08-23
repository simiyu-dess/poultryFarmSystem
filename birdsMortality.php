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
                <?php if(isset($_SESSION['error_msg'])): ?>
                    <div class="error_msg">
                    <p>
                        <?php 
                            echo $_SESSION['error_msg'];
                            unset($_SESSION['error_msg']);
                        ?>
                    </p>
                    </div>
                <?php endif ?>
                
                <?php
                    if(isset($_GET["birdsmortupdate"])){
                        // Get the id of the record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("BirdsMortality_ID" => $id);
                        // Call the select method that displays the record to be edited
                        $row = $birdsPurchaseObject->selectMethod("BirdsMortality", $where);
                        ?>
                         <p class="heading">Edit Birds Mortality Record</p>
                            <div id="error" style="text-align: center; color:  #e65061;"></div>
                            <form id="form" action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input id="Date" type="date"  name="date" value="<?php echo $row["Date"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorType"></div>
                                    <label for="">Birds Type</label>
                                    <input type="number" id="typeOfBirds" step="any" name="typeofbirds" value="<?php echo $row["Deaths"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorNumber"></div>
                                    <label for="">Number of Deaths</label>
                                    <input  type="number" id="numberOfDeaths" step="any" name="number" value="<?php echo $row["Deaths"]; ?>">
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="birdsmortedit" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>
                        <p class="heading">Add Birds Mortality Record</p>
                            <div id="error" style="text-align: center; color:  #e65061;"></div>
                            <form id="form" action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input id="Date" type="date" max="<?php echo date('Y-m-d');?>" name="date" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorType"></div>
                                    <label for="">Type of Birds</label>
                                    <input  type="text" id="typeOfBirds" name="typeofbirds" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorNumber"></div>
                                    <label for="">Number of Deaths</label>
                                    <input  type="number" step="any" id="numberOfDeaths" name="number" value="">
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="birdsmortsave" class="btn">Save</button>
                                </div>
                            </form>
                        <?php
                    }
                        ?>
                          
                <table id="tb_table">
                    <thead>
                        <th>Date</th>
                        <th>Number of Deaths</th>
                        <th>Updated by:</th>
                        <?php if($_SESSION['perm_admin'] == 1 || $_SESSION['perm_action'] == 1):?>
                        <th colspan="2">Action</th>
                        <?php endif ?>
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
                                <?php 

                                $userid = $row['User_ID'];
                                $user = getUserName($userid);
                                echo $user;
                                ?>
                                </td>
                                <?php if($_SESSION['perm_admin'] == 1 || $_SESSION['perm_action'] == 1):?>
                                <td>
                            
                                    <a class="edit_btn" href="birdsMortality.php?birdsmortupdate=1&id=<?php echo $row["BirdsMortality_ID"]; ?>">Edit</a>
                                    
                                </td>
                                <td>
                                <a class="del_btn" href="includes/action.php?birdsmortdelete=1&id=<?php echo $row["BirdsMortality_ID"]; ?>">Delete</a>                    
                                </td>
                                <?php endif ?>
                            </tr>
                            <?php
                            
                        } 
                    ?>
                    </tbody>
                </table>
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
                        var remainingBirds = <?php echo $remainingBirds ; ?>

                        
                       
                        
                        // Getting error divs ID
                        var errordate = document.getElementById('errorDate');
                        var errortype = document.getElementById("errorType");
                        var errornumber = document.getElementById("errorNumber");
                        
                        
                        
                        var truth = true;
                        
                       
                    
                       
                        

                       if(number > remainingBirds)
                       {
                           errornumber.innerHTML = "Total birds are less the number indicated";
                           truth = false;
                       }
                        if(number > 1)
                        {
                        if(number % 1 != 0)
                        {
                            errornumber.innerHTML = "Enter a valid number";
                            truth = false;
                        }
                        }
                        if(number < 1)
                        {
                            errornumber.innerHTML = "Number of deaths must be greater than zero";
                            truth = false;
                        }
                        if(number == " "){
                            errornumber.innerHTML = " This  field is required";
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