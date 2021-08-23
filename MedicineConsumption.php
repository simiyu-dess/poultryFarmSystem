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
                    if(isset($_GET["medusageUpdate"])){
                        // Get the Employee_ID for the employee record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("MedicineUsage_ID" => $id);
                        // Call the selectEmployee method that displays the record to be edited
                        $row = $medicineObject->selectMethod("MedicineUsage", $where);
                        ?>
                         <h style="font-weight: bold; font-size:20px">Update Medicine Consumption Record</h>
                            <form action="includes/action.php" method="post" onsubmit="return validate()" >
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorName"></div>
                                    <label for="">MedicineName</label>
                                    <input type="text" name="MedName" id="medicineName" value="<?php echo $row["MedicineName"]; ?>" required>
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorQuantity"></div>
                                    <label for="">Quantity(Litres)</label>
                                    <input type="number" step="any" name="Quantity" value="<?php echo $row["Quantity"]; ?>" required>
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="Date" id="date" name="ConsumpDate" value="<?php echo $row["Date"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Employee Assigned</label>
                                        <select name="Employee_incharge" id="" required>
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
                                    <button type="submit" name="medusageUpdate" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>
                         <h style="font-weight: bold; font-size:20px">Insert Medicine Consumption Record</h>
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                <div class="my-div-error" id="errorName"></div>
                                    <label for="">MedicineName</label>
                                    <input type="text" name="MedName" id="medicineName" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorQuantity"></div>
                                    <label for="">Quantity(Litres)</label>
                                    <input type="number" step="any" id="quantity" name="Quantity" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="Date"  name="ConsumpDate" id="date" value="">
                                </div>
                                <div class="input-group">
                                    <label for="">Employee Assigned</label>
                                    <select name="Employee_incharge">
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
                                    <button type="submit" name="medusageSave" class="btn">Save</button>
                                </div>
                            </form>
                        <?php
                    }
                        ?>
                         <table id="tb_table">
                    <thead>
                        <th>Date</th>
                        <th>Medicine name</th>
                        <th>Quantity(Litres)</th>
                        <th>Employee Incharge</th>
                        <th>Updated by:</th>
                        <?php if($_SESSION['perm_admin'] == 1 || $_SESSION['perm_action'] == 1):?>
                        <th colspan="2">Action</th>
                        <?php endif ?>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $medicineObject->viewMethod("MedicineUsage");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['Date'];?></td>
                                <td><?php echo $row['MedicineName'];?></td>
                                <td><?php echo $row['Quantity'];?></td>
                                <td>
                                    <?php 
                                        $employee = $row['Employee'];
                                        $sql = "SELECT FirstName, LastName from Employee, FeedConsumption where Employee.Employee_ID = $employee";
                                    
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
                                    <a class="edit_btn" href="MedicineConsumption.php?medusageUpdate=1&id=<?php echo $row["MedicineUsage_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" href="includes/action.php?medusageDelete=1&id=<?php echo $row["MedicineUsage_ID"]; ?>">Delete</a>
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
                        var quantity = document.getElementById("quantity").value;
                        var name = document.getElementById("medicineName").value;
                       
                       
                        
                        // Getting error divs ID
                        var errordate = document.getElementById('errorDate');
                        var errorQuantity = document.getElementById("errorQuantity");
                        var errorName = document.getElementById("errorName");
                       
                        
                        
                        // Defining REGEX
                       
                        
                        var truth = true;
                        if(dates == ""){
                            errordate.innerHTML = "This field is required";
                            truth = false;
                        }
                       
                        if(quantity < 0)
                        {
                            errorQuantity.innerHTML = "The  quantity must be a positive integer";
                            truth = false;
                        }
                        if(quantity == ""){
                            errorQuantity.innerHTML = "This field is required";
                            truth =  false;
                        }
                        if(name == "")
                        {
                            errorName.innerHTML = "This field is required";
                            truth = false;
                        }
                    
                        return truth;

                    }
                    </script>
    
</body>
</html>