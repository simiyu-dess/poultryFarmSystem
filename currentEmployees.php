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
               
            <?php if (isset($_GET['emplUpdate']))
           {
               $id = $_GET['id'] ?? null;
               $where = array("Employee_ID" => $id);
               $row = $employeeObject->selectMethod("Employee",$where);
                ?>
                <form action="includes/action.php" method="POST">
                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                
                                <div class="input-group">
                                    <label for="">First Name</label>
                                    <input type="text" name="FirstName" value="<?php echo $row["FirstName"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Last Name</label>
                                    <input type="text" name="LastName" value="<?php echo $row["LastName"]; ?>" required>
                                </div>
                                <div class="select-group">
                                <label for="">Gender</label>
                                <select name="Gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                                </select>
                                </div>
                                <div class="my-div-error" id="location"></div>
                                <div class = "input-group">
                                <label for="">Location</label>
                                <input type="text" id="Location" name="Location" 
                                value="<?php echo $row['Location']?>"/>
                                </div>
                                <div class="input-group">
                                    <label for="">Phone</label>
                                    <input type="text" name="Phone" value="<?php echo $row["Phone"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Job</label>
                                    <input type="text" name="Job" value="<?php echo $row["Job"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Salary</label>
                                    <input type="number" name="Salary" value="<?php echo $row["Salary"]; ?>" required>
                                </div>
                                <div class="input-group">
                                    <label for="">Start Date</label>
                                    <input type="Date" id="Date" name="StartDate" 
                                    value="<?php echo $row['startDate'] ?>">
                                </div>
                                <div class="input-group">
                                    <label for="">End Date</label>
                                    <input type="Date" id="Date" name="EndDate" 
                                    value="<?php echo $row['endDate'] ?>">
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="emplEdit" class="btn">Update</button>
                                </div>
                </form>
            
             <?php }?>

           <?php if (!isset($_GET['emplUdate']))
            {?>

            
                <table>
                    <thead>
                        <th>Name:</th>
                        <th>Location</th>
                        <th>Gender</th>
                        <th>Salary</th>
                        <th>Start Date</th>
                        <th colspan="2">Action</th>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $employeeObject->viewMethod("Employee");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['FirstName'].' '.$row['LastName'];?></td>
                                <td><?php echo $row['Location'];?></td>
                                <td><?php echo $row['Gender'];?></td>
                                <td><?php echo $row['Salary'];?></td>
                                <td><?php echo $row['startDate'];?></td>      
                                <td>
                                    <a class="edit_btn" href="currentEmployees.php?emplUpdate=1&id=<?php echo $row["Employee_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" href="includes/action.php?emplDelete=1&id=<?php echo $row["Employee_ID"]; ?>">Delete</a>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                    </tbody>
                </table>
                <?php }?>
            </div>
        </main>
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php";?>
    </div>
    <script src="script.js"></script>
</body>
</html>