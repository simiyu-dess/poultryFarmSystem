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
                        <th>Medicine Name</th>
                        <th>Consumed On</th>
                        <th>Quantity</th>
                        <th>Employee Responsible</th>
                        <th colspan="2">Action</th>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $medicineObject->viewMethod("MedicineUsage");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['MedicineName'];?></td>
                                <td><?php echo $row['Quantity'];?></td>
                                <td><?php echo $row['Date'];?></td>
                                <td>
                                    <?php 
                                        $employee = $row['Employee'];
                                        $sql = "select FirstName, LastName from Employee, FeedConsumption where Employee.Employee_ID = $employee";
                                        $query = new Database();
                                        $result = $query->connect()->query($sql);
                                        $result = mysqli_fetch_assoc($result);
                                     
                                        echo $result['FirstName'].' '.$result['LastName'];
                                    ?>
                                </td>      
                                <td>
                                    <a class="edit_btn" href="MedicineConsumption.php?medusageUpdate=1&id=<?php echo $row["MedicineUsage_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" href="includes/action.php?medusageDelete=1&id=<?php echo $row["MedicineUsage_ID"]; ?>">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
                
                <?php
                    if(isset($_GET["medusageUpdate"])){
                        // Get the Employee_ID for the employee record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("MedicineUsage_ID" => $id);
                        // Call the selectEmployee method that displays the record to be edited
                        $row = $medicineObject->selectMethod("MedicineUsage", $where);
                        ?>
                            <form action="includes/action.php" method="post">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="input-group">
                                    <label for="">MedicineName</label>
                                    <input type="text" name="MedName" value="<?php echo $row["MedicineName"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Quantity</label>
                                    <input type="number" step="any" name="Quantity" value="<?php echo $row["Quantity"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Date</label>
                                    <input type="Date"  name="ConsumpDate" value="<?php echo $row["Date"]; ?>" required>
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
                            <form action="includes/action.php" method="post">
                                <div class="input-group">
                                    <label for="">MedicineName</label>
                                    <input type="text" name="MedName" value="" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Quantity</label>
                                    <input type="number" step="any" name="Quantity" value="" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Date</label>
                                    <input type="Date"  name="ConsumpDate" value="" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Employee Assigned</label>
                                    <select name="Employee_incharge" id="" required>
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
            </div>
        </main>
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php";?>
    </div>
    <script src="script.js"></script>
</body>
</html>