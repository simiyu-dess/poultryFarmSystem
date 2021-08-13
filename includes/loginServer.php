<?php
session_start();
 include_once "{$_SERVER['DOCUMENT_ROOT']}/poultryFarm/includes/database.php";
    class LoginServer extends Database{
        

        public $error;

        // method to validate login information

        // $field parameter is associative array where key is equal to field and value is HTML field name
        public function loginValidation($field){
            $count = 0; 
            foreach($field as $key => $value){
                if(empty($value)){ // Value is HTML field. 
                    $count++; // increase count value for displaying error
                    $this->error= "<p>" . $key . " is required!</p>";
                }
            }
            // check if count value is 0 then it will return true, otherwise produce error
            if($count == 0){
                return true;
            }else{

            }
        }

        // method to check if right login information was entered
        public function canLogin($table, $username, $password){
            


            // query
            $sql = "SELECT * FROM " .$table. " WHERE  Username = '$username'";
            $query = $this->connect()->query($sql);
            $array = mysqli_fetch_array($query);
            if(password_verify($password, $array['Password']))
            {
                $_SESSION['ugroupid'] = $array['Ugroup_ID'];
                $_SESSION['loguser'] = $array['User_ID'];
                return true;
            }else{
                $this->error = "<p>Wrong Data</p>";
            }
        }
    }
    $db = new Database();

?>