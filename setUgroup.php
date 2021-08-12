<?PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/classes.php";
if (!isset($_SESSION['Username'])) {
    header("Location: index.php");
    exit();
}


	$ugroup_id = 0;
	$error = "no";

	//Select all Usergroups from UGROUP
	$ugroups = array();
	$result_usergroups = $ugroupObject->viewMethod("Ugroup");
	$ugroup_names = array();
	foreach($result_usergroups as  $row_ugroups){
		$ugroups[] = $row_ugroups;
		$ugroup_names[] = $row_ugroups['Ugroup_name'];
	}

?>
<!DOCTYPE HTML>
<html>
		
	<?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_head.php"; ?>

	<body>
    <div class="container">
    <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_top_navbar_settings.php"; ?>
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
		
		
				

                <?php if(isset($_GET['update_Ugroup'])){
                $id = $_GET['update_Ugroup'] ?? null;
                $where = array("Ugroup_ID" => $id);
                $row_groups = $ugroupObject->selectMethod("Ugroup", $where);
                ?>

                
              <?PHP echo '<p class="heading">Edit User</p>'; ?>
				<form action="includes/action.php" method="post" onsubmit="return validate()">
					<table id="tb_set" style="margin:auto;">
						<tr>
							<td>Usergroup Name</td>
							<td>
                            <input type="text" name="ugroup_name" placeholder="Usergroup Name" value="<?PHP echo $row_groups['Ugroup_name']  ?>"/>
                            </td>
						</tr>
						<tr>
							<td>Permissions</td>
							<td>
								<input type="checkbox" name="ugroup_admin" <?PHP if($row_groups['Ugroup_admin'] == 1) echo 'checked="checked" '; ?> />
								Admin</td>
						</tr>
						<tr>
							<td>
                            </td>
							<td>
								<input type="checkbox" name="ugroup_birds" <?PHP if($row_groups['Ugroup_birds'] == 1) echo 'checked="checked" '; ?> />
								Birds</td>
						</tr>
						<tr>
							<td>
                            </td>
							<td>
								<input type="checkbox" name="ugroup_sales" <?PHP if($row_groups['Ugroup_sales'] == 1) echo 'checked="checked" '; ?> />
								Sales</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="ugroup_purchases" <?PHP if($row_groups['Ugroup_purchase'] == 1) echo 'checked="checked" '; ?> />
								Purchase</td>
						</tr>
                        <tr>
							<td></td>
							<td>
								<input type="checkbox" name="ugroup_medicine" <?PHP if($row_groups['Ugroup_medicine'] == 1) echo 'checked="checked" '; ?> />
								medicine</td>
						</tr>
                        <tr>
							<td></td>
							<td>
								<input type="checkbox" name="ugroup_feeds" <?PHP if($row_groups['Ugroup_feeds'] == 1) echo 'checked="checked" '; ?> />
								feeds</td>
						</tr>
                        <tr>
							<td></td>
							<td>
								<input type="checkbox" name="ugroup_eggs" <?PHP if($row_groups['Ugroup_eggs'] == 1){ echo 'checked="checked" ';} ?> />
								eggs</td>
						</tr>
					</table>
					<input type="submit"  class = "edit_btn" name="edit_Ugroup" value="Update" />
					<input type="hidden" name="ugroup_id" value="<?PHP echo $_GET['update_Ugroup'];?>" />
				</form>
                <?php } 
                else
                {?>
                    <form action="includes/action.php" method="post" onsubmit="return validate()">
					<table id="tb_set" style="margin:auto;">
					<div class="my-div-error" id="errorUgroup"></div>
						<tr>
							<td>Usergroup Name</td>
							<td>
                            <input type="text" name="ugroup_name" id="ugroupname" placeholder="Usergroup Name" value=""/>
                            </td>
						</tr>
						<tr>
							<td>Permissions</td>
							<td>
								<input type="checkbox" name="ugroup_admin" />
								Administrator</td>
						</tr>
						<tr>
							<td>
                            </td>
							<td>
								<input type="checkbox" name="ugroup_birds" />
								Birds</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="ugroup_sales"  />
								Sales</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="ugroup_purchases" />
								Purchase Action</td>
						</tr>
                        <tr>
							<td></td>
							<td>
								<input type="checkbox" name="ugroup_medicine" />
								medicine Action</td>
						</tr>
                        <tr>
							<td></td>
							<td>
								<input type="checkbox" name="ugroup_feeds" />
								Feeds Action</td>
						</tr>
                        <tr>
							<td></td>
							<td>
								<input type="checkbox" name="ugroup_disease" />
								Disease Action</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" name="ugroup_eggs"/>
								eggs Action</td>
						</tr>
					</table>
					<input type="submit" class="edit_btn" name="save_Ugroup" value="Save" />
				</form>
               <?php  }
                ?>
                
		

	
	
			<table id="tb_table">
				
				<tr>
					<th colspan="10" class="title">Existing Usergroups</th>
				</tr>
				<tr>
					<th rowspan="2">User Group Name</th>
					<th colspan="6">Permissions</th>
					<th colspan="2" rowspan="2">Action</th>
					
				</tr>
				<tr>
					<th style="background-color:#a7dbd8">Administrator</th>
					<th style="background-color:#a7dbd8">Purchase</th>
					<th style="background-color:#a7dbd8">Sales</th>
                    <th style="background-color:#a7dbd8">Medicine</th>
                    <th style="background-color:#a7dbd8">Feeds</th>
                    <th style="background-color:#a7dbd8">Birds</th>
				</tr>
				<?PHP
					foreach ($result_usergroups as $row_ugroups){?>
						<tr>
									<td><?php echo $row_ugroups['Ugroup_name']; ?></td>
									<td>
										<input type="checkbox" disabled="disabled" 
										<?php if ($row_ugroups['Ugroup_admin']==1) echo 'checked="checked" ';?>/>
									</td>
									<td>
										<input type="checkbox" disabled="disabled" 
										<?php if ($row_ugroups['Ugroup_purchase'] ==1) echo 'checked="checked" ';?>/>
									</td>
									<td>
										<input type="checkbox" disabled="disabled" 
										<?php if ($row_ugroups['Ugroup_sales'] ==1) echo 'checked="checked" ';?> />
									</td>
                                    <td>
										<input type="checkbox" disabled="disabled" 
										<?php if ($row_ugroups['Ugroup_medicine'] ==1) echo 'checked="checked" ';?> />
									</td>
                                    <td>
										<input type="checkbox" disabled="disabled" 
										<?php if ($row_ugroups['Ugroup_feeds'] ==1) echo 'checked="checked" ';?>/>
									</td>
                                    <td>
										<input type="checkbox" disabled="disabled" 
										<?php if ($row_ugroups['Ugroup_birds'] ==1) echo 'checked="checked" ';?> />
									</td>
						   <td>
					
										<a href="setUgroup.php?update_Ugroup=<?php echo $row_ugroups['Ugroup_ID']; ?>">
											<p class="edit_btn">Edit</p>
										</a>
						</td>
									<td>
						
										<a href="includes/action.php?delete_Ugroup=<?php echo $row_ugroups['Ugroup_ID']; ?>">
											<p class="del_btn">Delete</p>
										</a>
						  </td>
								</tr>
					<?php } ?>

	
			</table>
        </main>
        <?php include "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/partials/_side_bar.php"; ?>
    </div>
	<script>
    function validate(){
                        var name = document.getElementById("ugroupname").value;
						var errorUgroup = document.getElementById("errorUgroup");
						var ugroup_names = <?php echo json_encode($ugroup_names); ?>;
						var ugroup_name = name;
                    
                       
                        
                        var truth = true;
                         if(ugroup_name == "")
						 {
							 errorUgroup.innerHTML = "Name cannot be empty";
							 truth = false;
						 }

						 for(i=0; i < ugroup_names.length; i++)
						 {
							 if(ugroup_names[i] == ugroup_name)
							 {
								 errorUgroup.innerHTML = "Ugroup name already exist";
								 truth = false;
							 }
						 }
                    
                        return truth;

                    }
                    </script>
	</body>
</html>
