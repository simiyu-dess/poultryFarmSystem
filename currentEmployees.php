<?php
session_start();
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}
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
                <script>
                    function validate(){
                        var fname = document.getElementById("Fname").value;
                        var lname = document.getElementById("Lname").value;
                        var phone = document.getElementById("Phone").value;
                        var job = document.getElementById("Job").value;
                        var salary = document.getElementById("Salary").value;
                        var location = document.getElementById("Location").value;
                        var startDate = document.getElementById('Date').value;
                        var endDate = document.getElementById('endDate').value;
                       
                        
                        // Getting error divs ID
                        var errorFname = document.getElementById('errorFname');
                        var errorLname = document.getElementById('errorLname');
                        var errorPhone = document.getElementById('errorPhone');
                        var errorJob = document.getElementById('errorJob');
                        var errorSalary = document.getElementById('errorSalary');
                        var errorDate = document.getElementById('errorDate');
                        var errorLocation = document.getElementById('errorLocation');
                        var errorEndDate = document.getElementById('errorEndDate');
                        
                        
                        // Defining REGEX
                        var fnameP = /[A-Za-z]+/;
                        var lnameP = /[A-Za-z]+/;
                        var phoneP = /^0[0-9]\d{8}$/;
                        var jobP = /^(?![\s.]+$)[a-zA-Z\s.]*$/;
                        var salaryP = /^(\d+)(?:\.(\d{1,2}))?$/;
                        
                        var truth = true;
                        if(!fnameP.test(fname)){
                            errorFname.innerHTML = "Please enter a valid first name";
                            truth = false;
                        }
                        if(fname == ""){
                            errorFname.innerHTML = "This field is required";
                            truth =  false;
                        }
                        if(!lnameP.test(lname)){
                            errorLname.innerHTML = "Please enter a valid last name";
                            truth = false;
                        }
                        if(lname == ""){
                            errorLname.innerHTML = "Last name field is required";
                            truth = false;
                        }
                        if(location == "")
                        {
                            errorLocation.innerHTML = "Location field is required";
                            truth = false;
                        }
                        if(!phoneP.test(phone)){
                            errorPhone.innerHTML = "Pleaser enter a valid phone number";
                            truth = false;
                        }

                        if(phone == ""){
                            errorPhone.innerHTML = "Phone field is required";
                            truth = false;
                        }
                        if(!jobP.test(job)){
                            errorJob.innerHTML = "Pleaser enter a valid job title";
                            truth =false;
                        }

                        if(job == ""){
                            errorJob.innerHTML = "Job field is required";
                            truth = false;
                        }
                        if(!salaryP.test(salary)){
                            errorSalary.innerHTML = "Pleaser enter a valid salary";
                            truth = false;
                        }

                        if(salary == ""){
                            errorSalary.innerHTML = "Salary field is required";
                            truth = false;
                        }
                        if(startDate == "")
                        {
                            errorDate.innerHTML = "Date field is required";
                            truth = false;  
                        }
                        var today = new Date().getTime(),
                       idate = startDate.split("/");

                       idate = new Date(idate[2], idate[1] - 1, idate[0]).getTime();
                       if ((today - idate) < 0)
                       {
                           errorDate.innerHTML = "Date cannot be in the future";
                           truth = false;
                       }


                        return truth;

                    }
                    </script>
                <form action="includes/action.php" method="POST" onsubmit="return validate()">
                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                
                                <div class="input-group">
                                <div class="my-div-error" id="errorFname"></div>
                                    <label for="">First Name</label>
                                    <input type="text" name="FirstName" id ="Fname" value="<?php echo $row["FirstName"]; ?>" required>
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorLname"></div>
                                    <label for="">Last Name</label>
                                    <input type="text" name="LastName" id="Lname" value="<?php echo $row["LastName"]; ?>" required>
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
                                <div class="my-div-error" id="errorPhone"></div>
                                    <label for="">Phone</label>
                                    <input type="text" id = "Phone" name="Phone" value="<?php echo $row["Phone"]; ?>" required>
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorPhone"></div>
                                    <label for="">Job</label>
                                    <input type="text" name="Job" id="Job" value="<?php echo $row["Job"]; ?>" required>
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorSalary"></div>
                                    <label for="">Salary</label>
                                    <input type="number" name="Salary" id = "Salary" value="<?php echo $row["Salary"]; ?>" required>
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Start Date</label>
                                    <input type="Date" id="Date" name="StartDate" max="<?php echo date('Y-m-d');?>"
                                    value="<?php echo $row['startDate'] ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorEndDate"></div>
                                    <label for="">End Date</label>
                                    <input type="Date" id="endDate" name="EndDate" max="<?php echo date('Y-m-d');?>"
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