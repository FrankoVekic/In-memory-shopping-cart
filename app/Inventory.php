<?php 

//Including cart so that i can add it later on and creating a session so i can store all data in 2 sessions
include_once 'Cart.php';
session_start();
new Inventory;

class Inventory 
{

    //this will happen when i run the program or when ever the user makes a mistake this will repeat itself
    public function __construct()
    {
        echo "Add your item here:";
        $line = trim(fgets(STDIN,35));
        $input = explode(" ",$line);
        $this->systemStart($input);
    }

    //function that starts the program
    public function systemStart($input)
    {
        //checking if the first input is ADD
        if(trim($input[0]) == "ADD" || $input[0]=="add"){
            if($this->checkSku($input) && $this->checkName($input) && $this->checkQuantity($input) && $this->checkPrice($input)){
                Inventory::add($input);
            }
        }
        //checking if first input is end
        else if(trim($input[0] == "END") || trim($input[0]) == "end"){
            Inventory::end($input);
        }
        //if first input isn't END or ADD we restart the program and echo the following line:
        else {
            echo "You can only use ADD or END commands. \n";
        }
        new Inventory;
    }
    
    //static function for adding a new item
    public static function add($input)
    {
        //if session is empty we add data to the first array key
        if(empty($_SESSION['inventory'])){
            $_SESSION['inventory'][0]=[
               'sku'=> trim($input[1]),
               'name'=>trim($input[2]),
               'quantity'=>trim($input[3]),
               'price'=>trim($input[4])
           ];
           echo "Item added. You currently have 1 item in the inventory. \n";
          }
          //if session isn't empty we add new item after the last added item
          else
            {
           $count = count($_SESSION['inventory']);
           $_SESSION['inventory'][$count]=[
               'sku'=> trim($input[1]),
               'name'=>trim($input[2]),
               'quantity'=>trim($input[3]),
               'price'=>trim($input[4])
           ];
           echo "You added another item. You currently have " . $count+1 ." items in the inventory. \n";
          }
       }
       
    //static function that will send us to the secound stage if inventory session is not empty
    public static function end()
    {
        if(empty($_SESSION['inventory'])){
            echo "Your inventory currently has no items. You'll need to add some first. \n";
       }else {
           new Cart;
       }
    }

    //error checkers 

    //checking if sku input is correct
    public function checkSku($input)
    {
        //checking if 2nd input is empty
        if(empty($input[1])){
            echo "You have to enter SKU. \n";
            return false;
        }
        //checking if input isn't a positive number
        if($input[1]<=0){
            echo "SKU must be a (full) positive number. \n";
            return false;
        }
        //checking if sku input isn't a number
        if(!preg_match('/^[0-9]*$/', $input[1])){
            echo "SKU must be a (full) number. \n";
            return false;
        }
        //checking if the SKU already exists in the session
        if(!$this->inputExists($input)){
            echo "SKU is already in use. \n";
            return false;
        }
         return true;     
    }

    //checking if name data is correct
    public function checkName($input)
    {
        if(empty($input[2])){
            echo "You have to enter the name \n";
            return false;
        }
        if(!is_string($input[2])){
            echo "Name input error. Try diffrent input. \n";
        }
        return true;     
    }

    //checking if quantity data is correct
    public function checkQuantity($input)
    {
        if(empty($input[3])){
            echo "You must enter the quantity. \n";
            return false;
        }
        if(!preg_match('/^[0-9]*$/', $input[3])){
            echo "Quantity must be a (full) number. \n";
            return false;
        }
        if($input[3]<=0){
            echo "Quantity must be a (full) positive number. \n";
            return false;
        }
        return true;
    }

    //checking if price data is correct
    public function checkPrice($input)
    {
        if(empty($input[4])){
            echo "You must enter the price. \n";
            return false;
        }
        if(!is_numeric($input[4])){
            echo "Invalid price. \n";
            return false;
        }
        if($input[4]<=0){
            echo "Price must be a positive number. \n";
            return false;
        }
        return true;
    }

    //function that finds if SKU already exists in the session
    public function inputExists($input)
    {
     if(!empty($_SESSION['inventory'])){
            for($i=0;$i<count($_SESSION['inventory']);$i++){
                if($_SESSION['inventory'][$i]['sku'] == trim($input[1])){
                    return false;
                }
            }
         }
         return true;
    }
}