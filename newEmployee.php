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

                    </head>
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
                <script>
                    function validate(){
                        var fname = document.getElementById("FirstName").value;
                        var lname = document.getElementById("LastName").value;
                        var phone = document.getElementById("Phone").value;
                        var job = document.getElementById("Job").value;
                        var salary = document.getElementById("Salary").value;
                        var location = document.getElementById("location").value;
                        var startDate = document.getElementById('Date').value;
                       
                        
                        // Getting error divs ID
                        var errorFname = document.getElementById('errorFname');
                        var errorLname = document.getElementById('errorLname');
                        var errorPhone = document.getElementById('errorPhone');
                        var errorJob = document.getElementById('errorJob');
                        var errorSalary = document.getElementById('errorSalary');
                        var errorDate = document.getElementById('errorDate');
                        var errorLocation = document.getElementById('errorLocation');
                        
                        
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
                                <div class="select-group">
                                <label for="">Gender</label>
                                <select name="Gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                                </select>
                                </div>
                                <div class="my-div-error" id="errorLocation"></div>
                                <div class = "input-group">
                                <label for="">Location</label>
                                <input type="text" id="location" name="Location" value=""/>
                                </div>
                                <div class="my-div-error" id="errorPhone"></div>
                                <div class="input-group">
                                    <label for="">Phone</label>
                                    <input type="phone"  id="Phone" name="Phone" value="">
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
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Start Date</label>
                                    <input type="Date" id="Date" name="StartDate" max="<?php echo date('Y-m-d');?>" value="">
                                </div>
                               
                                <div class="input-group">
                                    <button type="submit" name="emplSave" class="btn" value=""> Save</button>
                                </div>
                            </form>
            </div>
        </main>
        <!-- sidebar nav -->
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php";?>
    </div>
   
   
                    
</body>
</html>