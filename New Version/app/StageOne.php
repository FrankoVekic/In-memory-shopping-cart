<?php 

include_once 'Item.php';
include_once 'Inventory.php';
include_once 'StageTwo.php';
new StageOne;

class StageOne 
{
    private $inventory;

    public function __construct()
    {
        echo "Add your item here: \n";
        $line = trim(fgets(STDIN,35));
        $input = explode(" ",$line);
        $this->inventory = new Inventory(); 
        $this->systemStart($input);      
    }

    public function systemStart($input)
    {
        
        if(trim($input[0]) == "ADD" || $input[0]=="add"){
            if($this->checkSku($input) && $this->checkName($input) && $this->checkQuantity($input) && $this->checkPrice($input)){

                $item = new Item($input[1],$input[2],$input[3],$input[4]);
                $this->inventory->addItem($item);
            }
                
        }
        else if(trim($input[0] == "END") || trim($input[0]) == "end"){
            $this->end();
            return;
        }
        else {
            echo "You can only use ADD or END commands. \n";
        }
        echo "Add your item here: \n";
        $line = trim(fgets(STDIN,35));
        $input = explode(" ",$line);
        $this->systemStart($input); 
    }
       
    public function end()
    {
        if($this->inventory->isEmpty()){
            echo "Your inventory currently has no items. You'll need to add some first. \n";
            new StageOne;
       }else {
           new StageTwo($this->inventory);
       }
    }

    public function checkSku($input)
    {
        if(empty($input[1])){
            echo "You have to enter SKU. \n";
            return false;
        }
        if($input[1]<=0){
            echo "SKU must be a (full) positive number. \n";
            return false;
        }
        if(!preg_match('/^[0-9]*$/', $input[1])){
            echo "SKU must be a (full) number. \n";
            return false;
        }
         return $input[1];     
    }

    public function checkName($input)
    {
        if(empty($input[2])){
            echo "You have to enter the name \n";
            return false;
        }
        if(!is_string($input[2])){
            echo "Name input error. Try diffrent input. \n";
        }
        return $input[2];     
    }

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
        return $input[3];
    }

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
        return $input[4];
    }
}