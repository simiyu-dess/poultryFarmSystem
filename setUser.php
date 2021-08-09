<?PHP
session_start();
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/classes.php";

if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}
	$user_id = 0;
	$employee = 0;
	$employeeC = 0;
	$username = "";

	//Select all users from USER
	$users = array();
	$user_names = array();
	$sql_users = "SELECT User.User_ID, User.Username, User.setupDate, Ugroup.Ugroup_ID,
	               Ugroup.Ugroup_Name, Employee.Employee_ID, Employee.FirstName, Employee.LastName 
	               FROM User LEFT JOIN Ugroup ON Ugroup.Ugroup_ID = User.Ugroup_ID
				   LEFT JOIN Employee ON User.Employee_ID = Employee.Employee_ID
				   WHERE User.User_ID != 0 
				   ORDER BY Username";

    $query_users = $databaseObject->connect()->query($sql_users);
    
				   
	
	while($row_users=mysqli_fetch_assoc($query_users)){
		$users[] = $row_users;
		$user_names[] = $row_users['Username'];
	}

	//Select all usergroups from UGROUP
	
	$result_Usergroups  = $userObject->viewMethod("Ugroup");
	

	// Select all employees from EMPLOYEE

	$result_Employess = $userObject->viewMethod("Employee");
	

	// Select employees from EMPLOYEE who are already associated with a user
	$sql_empl_assoc = "SELECT Employee_ID FROM Employee WHERE Employee_ID != 0 AND Employee_ID
	IN (SELECT Employee_ID FROM User)";  
	$query_empl_assoc = $databaseObject->connect()->query($sql_empl_assoc);                    
	$empl_assoc = array();
	while($row_empl_assoc = mysqli_fetch_assoc($query_empl_assoc)){
		$empl_assoc[] = $row_empl_assoc['Employee_ID'];
	}

?>
<!DOCTYPE HTML>
<html>
	
	
	
	<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_head.php";?>
	
	

	<body>
		<!-- MENU -->
		
	<div class="container">
	<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_top_navbar_settings.php";?>
	<main>
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

		<!-- LEFT SIDE: Create New User Form -->
		
			<?php if(isset($_GET['update_User']))
			{
				$id = $_GET['update_User'] ?? null;
				$user_id = $id;
				$where = array("User_ID" => $id);
				$existing_Users = $userObject->selectMethod("User", $where);
				$username =  $existing_Users['Username'];
				?>
				<p class="heading">Update User</p>

  <form action="includes/action.php" method="post" onsubmit="return validate()">

	<table id="tb_set" style="margin:auto;">
		<tr>
		<div class="my-div-error" id="errorUsername"></div>
		
			<td>Username</td>
			<td><input type="text" name="username" 
			placeholder="Username" id="username" value="<?php echo $existing_Users['Username'];?> " /></td>
		</tr>
		<tr>
		<div class="my-div-error" id="erroPassword"></div>
			<td>Password</td>
			<td><input type="password" id="password" name="password" placeholder="Password" /></td>
		</tr>
		<tr>
			<td>Repeat Password</td>
			<td><input type="password" id="conf_password" name="conf_Password" placeholder="Repeat Password" /></td>
		</tr>
		<tr>
			<td>Usergroup</td>
			<td class="center">
				<select name="ugroup" size="1" >
				<?php 
				$where = array("Ugroup_ID" => $existing_Users['Ugroup_ID']);
				$curGroup = $ugroupObject->selectMethod("Ugroup", $where);
				?>
				<option value="<?php echo $curGroup['Ugroup_ID'] ?>">
				<?php echo $curGroup['Ugroup_name']; ?>
				</option>
					<?PHP
					foreach($result_Usergroups as $row_ugroup){?>
					<option value="<?php echo $row_ugroup['Ugroup_ID']?>">
					<?php echo $row_ugroup['Ugroup_name'] ?>
					</option>
							
							
					<?php 	}
					?>
				</select>
			</td>
		</tr>
		<tr>
		<div class="my-div-error" id="errorEmployee"></div>
			<td>Employee:</td>
			<td>
				<select name="employee_id" id="employee" size="1">
				<?php
                  $where = array("Employee_ID" => $existing_Users['Employee_ID']);
				 
				$curEmpl = $employeeObject->selectMethod("Employee", $where);
				$employeeC = $curEmpl['Employee_ID'];
				?>
				<option value="<?php echo $curEmpl['Employee_ID']?>">
				<?php echo $curEmpl['FirstName'].' '.$curEmpl['LastName']; ?>
				</option>
					<?PHP
						foreach($result_Employess as $row_employees){?>
		
							<option value="<?php echo $row_employees['Employee_ID']?> ">
							<?php echo $row_employees['FirstName'].' '.$row_employees['LastName'];?></option>
						<?php }?>
					
				</select>
			</td>
		</tr>
	</table>
	<input type="submit" name="edit_User" class="edit_btn" value="Update" />
	<input type="hidden" name="user_id" value="<?PHP echo $_GET['update_User']; ?>" />
</form>
<?php } 
          else
		  {
?>

				<p class="heading"> Create User</p>

				<form action="includes/action.php" method="post" onsubmit="return validate()">
				<div class="my-div-error" id="errorUsername"></div>

					<table id="tb_set" style="margin:auto;">
						<tr>
						
							<td>Username</td>
							<td><input type="text" id="username" name="username" placeholder="Username" value="" /></td>
						</tr>
						<tr>
						<div class="my-div-error" id="errorPassword"></div>
							<td>Password</td>
							<td><input type="password" id="password" name="password" placeholder="Password" /></td>
						</tr>
						<tr>
							<td>Repeat Password</td>
							<td><input type="password" id="conf_password" name="conf_Password" placeholder="Repeat Password" /></td>
						</tr>
						<tr>
							<td>Usergroup</td>
							<td class="center">
								<select name="ugroup" size="1" >
									<?PHP
									foreach($result_Usergroups as $row_ugroup){?>
									<option value="<?php echo $row_ugroup['Ugroup_ID']?>">
									<?php echo $row_ugroup['Ugroup_name'] ?>
									</option>
											
											
									<?php 	}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td>Employee:</td>
							<td>
							<div class="my-div-error" id="errorEmployee"></div>
								<select name="employee_id" id="employee" size="1">
									
									<?PHP
										foreach($result_Employess as $row_employees){?>
						
											<option value="<?php echo $row_employees['Employee_ID']?> ">
											<?php echo $row_employees['FirstName'].' '.$row_employees['LastName'];?></option>
										<?php }?>
									
								</select>
							</td>
						</tr>
					</table>
					<input type="submit" class="edit_btn" name="save_User" value="Save" />
				</form>
				<?php } ?>
			
		

		
			<form method="post">
				<table id="tb_table">
				
					<tr>
						<th class="title" colspan="6">Existing Users</th>
					</tr>
					<tr>
						<th>User Name</th>
						<th>User Group</th>
						<th>Employee</th>
						<th>Changed</th>
						<th colspan="2">Action</th>
					</tr>
					<?PHP
					foreach ($users as $row_user){?>
						<tr>
										<td><?php echo $row_user['Username']?></td>
										<td><?php echo $row_user['Ugroup_Name']?></td>
										<td><?php echo $row_user['FirstName'].' '.$row_user['LastName']?></td>
										<td><?php echo $row_user['setupDate']?></td>
										<td>
											
											<a href="setUser.php?update_User=<?php echo $row_user['User_ID'] ?>">
												<p class="edit_btn">Edit</p>
											</a>
					                       </td>
										   <td>
										<a href="includes/action.php?delete_User=<?php echo $row_user['User_ID'] ?>">
										<p class="del_btn"> Delete </p>
										</a>
					                    </td>
										
									</tr>
					<?php }
					?>
				</table>
			</form>
			
		
		</main>
	<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php"; ?>
	</div>
	<script>
                    function validate(){
                        var username = document.getElementById("username").value;
                        var password = document.getElementById("password").value;
                        var conf_password = document.getElementById("conf_password").value;
						var employee_id = Number(document.getElementById("employee").value);
						var employees = <?php echo json_encode($empl_assoc); ?>;
						var usernames = <?php echo json_encode($user_names); ?>;
                        
                       
                        
                        // Getting error divs ID
                        var errorusername = document.getElementById('errorUsername');
                        var errorpassword = document.getElementById("errorPassword");
                        var erroremployee = document.getElementById("errorEmployee");
						var employee = <?php echo $employeeC; ?>;
						var id = Number(employee_id);
						var name = username;


                        
                        
                        
                        var truth = true;
                        
                       
						
                        if(username == ""){
							
							errorusername.innerHTML="User name is required";
                            
                            truth = false;
						
                        }
                        if(password == "")
                        {
							errorpassword.innerHTML = "Password field is required";
                            
                            truth = false;
                        }
                        if(conf_password == "")
                        {
                           
                            truth = false;
                        }
						if(conf_password != password)
						{
							errorpassword.innerHTML = "passwords do not match";
							truth = false;
						}
						for(i = 0; i < employees.length; i++)
						{
							if(employees[i] == id)
							{
								erroremployee.innerHTML = "employee already associated with another account";
								truth = false;
							}
							for(i = 0; i < usernames.length; i++)
						{
							if(usernames[i] == username)
							{
								errorusername.innerHTML = "User name already exists";
								truth = false;
							}
						}
						}

						
						
                       

                        return truth;

                    }
                    </script>
	</body>
</html>
