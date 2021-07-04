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
        <div class="empl_header">
        <a href="newEmployee.php">New Employee</a>
        <a href="currentEmployees">Current Employees</a>
        <a href="payroll.php">Payroll</a>
        </div>
        <div class="main__container">
                <?php
                    if(isset($_GET["update"])){
                        // Get the Employee_ID for the employee record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("Employee_ID" => $id);
                        // Call the selectMethod() method that displays the record to be edited
                        $row = $employeeObject->selectMethod("Employee", $where);
                        ?>
                       
                            <form action="includes/action.php" method="post">
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
                                    <label for="">Gender:</label>
                                    <select name="gender" size = "1">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    </select>
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
                                    <button type="submit" name="edit" class="btn">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>
                    <script>
                    function validate(){
                        var fname = document.getElementById("FirstName").value;
                        var lname = document.getElementById("LastName").value;
                        var phone = document.getElementById("Phone").value;
                        var job = document.getElementById("Job").value;
                        var salary = document.getElementById("Salary").value;
                        // Getting error divs ID
                        var errorFname = document.getElementById('errorFname');
                        var errorLname = document.getElementById('errorLname');
                        var errorPhone = document.getElementById('errorPhone');
                        var errorJob = document.getElementById('errorJob');
                        var errorSalary = document.getElementById('errorSalary');
                        // Defining REGEX
                        var fnameP = /[A-Za-z]+/;
                        var lnameP = /[A-Za-z]+/;
                        var phoneP = /^0[0-9]\d{8}$/;
                        var jobP = /^(?![\s.]+$)[a-zA-Z\s.]*$/;
                        var salaryP = /^(\d+)(?:\.(\d{1,2}))?$/;
                        
                        var truth = true;
                        if(!fnameP.test(fname)){
                            errorFname.innerHTML = "Please enter a valid first name";
                            truth = truth && false;
                        }
                        if(fname == ""){
                            errorFname.innerHTML = "This field is required";
                            truth = truth && false;
                        }
                        if(!lnameP.test(lname)){
                            errorLname.innerHTML = "Please enter a valid last name";
                            truth = truth && false;
                        }
                        if(lname == ""){
                            errorLname.innerHTML = "Last name field is required";
                            truth = truth && false;
                        }
                        if(!phoneP.test(phone)){
                            errorPhone.innerHTML = "Pleaser enter a valid phone number";
                            truth = truth && false;
                        }

                        if(phone == ""){
                            errorPhone.innerHTML = "Phone field is required";
                            truth = truth && false;
                        }
                        if(!jobP.test(job)){
                            errorJob.innerHTML = "Pleaser enter a valid job title";
                            truth = truth && false;
                        }

                        if(job == ""){
                            errorJob.innerHTML = "Job field is required";
                            truth = truth && false;
                        }
                        if(!salaryP.test(salary)){
                            errorSalary.innerHTML = "Pleaser enter a valid salary";
                            truth = truth && false;
                        }

                        if(salary == ""){
                            errorSalary.innerHTML = "Salary field is required";
                            truth = truth && false;
                        }

                        return truth;

                    }
                    </script>
                            <form id="payrollForm" action="includes/action.php" method="post" onsubmit= "return validate()">
                                <div class="my-div-error" id="errorFname"></div>
                                <div class="input-group">
                                    <label for="">First Name</label>
                                    <input type="text" id="FirstName" name="FirstName" value="">
                                </div>
                                <div class="my-div-error" id="errorLname"></div>
                                <div class="input-group">
                                    <label for="">Last Name</label>
                                    <input type="text" id="LastName" name="LastName" value="">
                                </div>
                                <div class="my-div-error" id="errorPhone"></div>
                                <div class="input-group">
                                    <label for="">Phone</label>
                                    <input type="text"  id="Phone" name="Phone" value="">
                                </div>
                                <div class="my-div-error" id="errorJob"></div>
                                <div class="input-group">
                                    <label for="">Job</label>
                                    <input type="text"  id="Job" name="Job" value="">
                                </div>
                                <div class="my-div-error" id="errorSalary"></div>
                                <div class="input-group">
                                    <label for="">Salary</label>
                                    <input type="text" id="Salary" name="Salary" value="">
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="save" class="btn">Save</button>
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