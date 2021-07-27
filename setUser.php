<?PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "includes/database.php";
include "includes/action.php";
	$user_id = 0;
	$employee = 0;

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

		<!-- LEFT SIDE: Create New User Form -->
		
			<?php if(isset($_GET['update_User']))
			{
				$id = $_GET['update_User'] ?? null;
				$where = array("User_ID" => $id);
				$existing_Users = $userObject->selectMethod("User", $where);
				?>
				<p class="heading">Update User</p>

  <form action="includes/action.php" method="post" onSubmit="">

	<table id="tb_set" style="margin:auto;">
		<tr>
			<td>Username</td>
			<td><input type="text" name="user_name" 
			placeholder="Username" value="<?php echo $existing_Users['Username'];?> " /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="user_pw" placeholder="Password" /></td>
		</tr>
		<tr>
			<td>Repeat Password</td>
			<td><input type="password" name="Conf_Password" placeholder="Repeat Password" /></td>
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
				<select name="Employee_ID" size="1">
					<option value="0">None</option>
					<?PHP
						foreach($result_Employess as $row_employees){?>
		
							<option value="<?php echo $row_employees['Employee_ID']?> ">
							<?php echo $row_employees['FirstName'].' '.$row_employees['LastName'];?></option>
						<?php }?>
					
				</select>
			</td>
		</tr>
	</table>
	<input type="submit" name="save_changes" value="Save Changes" />
	<input type="hidden" name="user_id" value="<?PHP echo $user_id; ?>" />
</form>
<?php } 
          else
		  {
?>

				<p class="heading"> Create User</p>

				<form action="setUser.php" method="post" onSubmit="">

					<table id="tb_set" style="margin:auto;">
						<tr>
							<td>Username</td>
							<td><input type="text" name="user_name" placeholder="Username" value="" /></td>
						</tr>
						<tr>
							<td>Password</td>
							<td><input type="password" name="user_pw" placeholder="Password" /></td>
						</tr>
						<tr>
							<td>Repeat Password</td>
							<td><input type="password" name="Conf_Password" placeholder="Repeat Password" /></td>
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
								<select name="Employee_ID" size="1">
									<option value="0">None</option>
									<?PHP
										foreach($result_Employess as $row_employees){?>
						
											<option value="<?php echo $row_employees['Employee_ID']?> ">
											<?php echo $row_employees['FirstName'].' '.$row_employees['LastName'];?></option>
										<?php }?>
									
								</select>
							</td>
						</tr>
					</table>
					<input type="submit" name="save_changes" value="Save Changes" />
					<input type="hidden" name="user_id" value="<?PHP echo $user_id; ?>" />
				</form>
				<?php } ?>
			
		

		
			<form action="includes/action.php" method="post">
				<table id="tb_table">
					<colgroup>
						<col width="20%">
						<col width="20%">
						<col width="20%">
						<col width="16%">
						<col width="24%">
					</colgroup>
					<tr>
						<th class="title" colspan="5">Existing Users</th>
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
										<td><?php $row_user['FirstName'].' '.$row_user['LastName']?></td>
										<td><?php $row_user['setupDate']?></td>
										<td>
											<a href="setUser.php?update_User=<?php echo $row_user['User_ID'] ?>">
												<p class="edit_btn">Edit</p>
											</a>
										</td>
										<td>
										<a href="inludes/action.php?delete_User=<?php echo $row_user['User_ID'] ?>">
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
	</body>
</html>
