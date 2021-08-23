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
                <h style="font-weight: bold; font-size:20px;">Feed Consumption</h>
                
                <?php
                    if(isset($_GET["feedconsupdate"])){
                        // Get the Employee_ID for the employee record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("FeedConsumption_ID" => $id);
                        // Call the selectEmployee method that displays the record to be edited
                        $row = $feedConsumptionObject->selectMethod("FeedConsumption", $where);
                        ?>
                        <p class="heading">Edit Feed Consumption Record</p>
                            <form action="includes/action.php" method="post" onsubmit=" return validate()">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="date" name="ConsDate" id="date"max="<?php echo date('Y-m-d'); ?>" value="<?php echo $row["ConsDate"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorName"></div>
                                    <label for="">Feed name</label>
                                    <input type="text" step="any" id="feedname" name="feedname" value="<?php echo $row["Feed_name"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorQuantity"></div>
                                    <label for="">Quantity</label>
                                    <input type="number" step="any" id="quantity" name="Quantity" value="<?php echo $row["Quantity"]; ?>">
                                </div>
                                
                                <div class="input-group">
                                    <label for="">Employee Assigned</label>
                                        <select name="Employee" id="">
                                        <?php
                                            $myrow = $employeeObject->viewMethod("Employee");
                                            foreach($myrow as $row){
                                                $foreignID = $row["Employee_ID"];
                                            ?>                                    
                                            <option class="selectoptions" value="<?php echo $foreignID; ?>"><?php echo $row["FirstName"] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="feedconsedit" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>
                        <p class="heading">Insert Feed Consumption Record</p>
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="date" name="ConsDate" id="date" max="<?php echo date('Y-m-d');?>" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorName"></div>
                                    <label for="">Name</label>
                                    <input type="text" name="feedname" id="feedname" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorQuantity"></div>
                                    <label for="">Quantity(KGS)</label>
                                    <input type="number" step="any" name="Quantity" id="quantity" value="">
                                </div>
                                
                                <div class="input-group">
                                    <label for="">Employee Assigned</label>
                                    <select name="Employee" id="">
                                    <?php
                                        $myrow = $feedConsumptionObject->viewMethod("Employee");
                                        foreach($myrow as $row){
                                            $foreignID = $row["Employee_ID"];
                                    ?>                                    
                                        <option class="selectoptions" value="<?php echo $foreignID; ?>"><?php echo $row["FirstName"] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="feedconssave" class="btn">Save</button>
                                </div>
                            </form>
                        <?php
                    }
                        ?>

<table id="tb_table">
                    <thead>
                        <th>Consumed On</th>
                        <th>Quantity(KGS)</th>
                        <th>Feed name</th>
                        <th>Employee Incharge</th>
                        <th>Updated by:</th>
                        <?php if($_SESSION['perm_admin'] == 1 || $_SESSION['perm_action'] == 1):?>
                        <th colspan="2">Action</th>
                        <?php endif ?>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $feedConsumptionObject->viewMethod("FeedConsumption");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['ConsDate'];?></td>
                                <td><?php echo $row['Quantity'];?></td>
                                <td><?php echo $row['Feed_name'];?></td>
                                <td>
                                    <?php 
                                        
                                        $employee = $row['Employee'];
                                        $sql = "SELECT FirstName, LastName From Employee, FeedConsumption where Employee.Employee_ID = $employee";
                                        $result = $databaseObject->connect()->query($sql);
                                        $result = mysqli_fetch_array($result);
                                
                                        echo $result['FirstName'].' '.$result['LastName'];
                                    ?>
                                </td> 
                                <td>
                                <?php 

                                $userid = $row['User_ID'];
                                $user = getUserName($userid);
                                echo $user;
                                ?>
                                </td> 
                                <?php if($_SESSION['perm_admin'] == 1 || $_SESSION['perm_action'] == 1):?>
                                <td>
                                    <a class="edit_btn" href="feedConsumption.php?feedconsupdate=1&id=<?php echo $row["FeedConsumption_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" href="includes/action.php?feedconsdelete=1&id=<?php echo $row["FeedConsumption_ID"]; ?>">Delete</a>
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
                        var dates = document.getElementById("date").value;
                        var name = document.getElementById("feedname").value;
                        var quantity = document.getElementById("quantity").value;
                       
                       
                        
                        // Getting error divs ID
                        var errordate = document.getElementById('errorDate');
                        var errorname = document.getElementById("errorName");
                        var errorquantity = document.getElementById("errorQuantity");
                        
                        
                        
                      
                        
                        var truth = true;
                        if(name == ""){
                            errorname.innerHTML = "The feedname cannot be empty";
                            truth = false;
                        }
                        if(quantity < 0){
                            errorquantity.innerHTML = "PLeasea enter a valid quantity";
                            truth =  false;
                        }
                        if(quantity == ""){
                            errorquantity.innerHTML = "Quantity cannot be empty";
                            truth = false;
                        }
                        if(quantity == ""){
                            errorquantity.innerHTML = "feed quantity is  field is required";
                            truth = false;
                        }
                       

                        return truth;

                    }
                    </script>
    <script src="script.js"></script>
</body>
</html>