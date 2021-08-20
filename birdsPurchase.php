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
                    if(isset($_GET["birdspurchupdate"])){
                        // Get the id of the record to be edited
                        $id = $_GET["id"] ?? null;
                        $where = array("BirdsPurchase_ID" => $id);
                        // Call the select method that displays the record to be edited
                        $row = $birdsPurchaseObject->selectMethod("BirdsPurchase", $where);
                        ?>
                         <h style="font-weight: bold; font: size 20px;"> Edit Birds Purchase</h>
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                </div>
                                <div class="my-div-error" id="errorDate"></div>
                                <div class="input-group">
                                    <label for="">Date</label>
                                    <input type="date" id="date" name="date" max="<?php echo date('Y-m-d');?>" value="<?php echo $row["Date"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorType"></div>
                                    <label for="">Birds Type</label>
                                    <input type="text" step="any" id="typeOfBirds" name="typeofbirds" value="<?php echo $row["Bird_type"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorNumber"></div>
                                    <label for="">Number of Birds</label>
                                    <input type="number" step="any" id="numberOfBirds" name="numberofbirds" value="<?php echo $row["NumberOfBirds"]; ?>">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorPrice"></div>
                                    <label for="">Price(Ksh)</label>
                                    <input type="number" step="any" id="price" name="price" value="<?php echo $row["Price"]; ?>">
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="birdspurchedit" class="btn" value="">Update</button>
                                </div>
                            </form>
                        <?php
                    }else{
                        ?>  
                            <form action="includes/action.php" method="post" onsubmit="return validate()">
                                <div class="input-group">
                                <div class="my-div-error" id="errorDate"></div>
                                    <label for="">Date</label>
                                    <input type="date" id="date" max="<?php echo date('Y-m-d');?>" name="date" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorType"></div>
                                    <label for="">Birds Type</label>
                                    <input type="text"  name="typeofbirds" id="typeOfBirds" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorNumber"></div>
                                    <label for="">Number of Birds</label>
                                    <input type="number" id ="numberOfBirds" step="any" name="numberofbirds" value="">
                                </div>
                                <div class="input-group">
                                <div class="my-div-error" id="errorPrice"></div>
                                    <label for="">Price(Ksh)</label>
                                    <input type="number" id="price" step="any" name="price" value="" >
                                </div>
                                <div class="input-group">
                                    <button type="submit" name="birdspurchsave" class="btn">Save</button>
                                </div>
                            </form>
                        <?php
                    }
                        ?>
                         
                <table id="tb_table">
                    <thead>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Number</th>
                        <th>Price(Ksh)</th>
                        <th>Updated by:</th>
                        <th colspan="2">Action</th>
                    </thead>
                    <tbody>
                    <?php
                        // calling viewMethod() method
                        $myrow = $birdsPurchaseObject->viewMethod("BirdsPurchase");
                        foreach($myrow as $row){
                            // breaking point
                            ?>
                            <tr>
                                <td><?php echo $row['Date'];?></td>
                                <td><?php echo $row['Bird_type']; ?></td>
                                <td><?php echo $row['NumberOfBirds'];?></td>
                                <td><?php echo $row['Price'];?></td>
                                <td>
                                <?php 

                                $userid = $row['User_ID'];
                                $user = getUserName($userid);
                                echo $user;
                                ?>
                                </td>
                                <td>
                                    <a class="edit_btn" href="birdsPurchase.php?birdspurchupdate=1&id=<?php echo $row["BirdsPurchase_ID"]; ?>">Edit</a>
                                </td>
                                <td>
                                    <a class="del_btn" href="includes/action.php?birdspurchdelete=1&id=<?php echo $row["BirdsPurchase_ID"]; ?>">Delete</a>
                                </td>
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
                        var type = document.getElementById("typeOfBirds").value;
                        var number = document.getElementById("numberOfBirds").value;
                        var price = document.getElementById("price").value;
                       
                        
                        // Getting error divs ID
                        var errordate = document.getElementById('errorDate');
                        var errortype = document.getElementById("errorType");
                        var errornumber = document.getElementById("errorNumber");
                        var errorprice = document.getElementById("errorPrice");
                        
                        
                        var truth = true;
                       
                        if(type == ""){
                            errortype.innerHTML = "This field is required";
                            truth =  false;
                        }
                        if(number < 1)
                        {
                            errornumber.innerHTML = "Enter a positive integer";
                            truth = false;
                        }
                        if(number > 1)
                        {
                        if(number % 1 != 0)
                        {
                            errornumber.innerHTML = "Enter a valid number";
                            truth = false;
                        }
                        }

                        if(dates == "")
                        {
                            errordate.innerHTML = "This field is required";
                            truth = false;
                        }
                        
                        if(number == ""){
                            errornumber.innerHTML = " number is  field is required";
                            truth = false;
                        }
                        if(price < 1)
                        {
                            errorprice.innerHTML = "Price must be a positiveinteger";
                            truth = false;
                        }
                       
                        if(price == ""){
                            errorprice.innerHTML = "price field is required";
                            truth = false;
                        }

                    


                        return truth;

                    }
                    </script>
    <script src="script.js"></script>
</body>
</html>