<?php 

class Inventory {

    protected array $inventory = [];


    public function __construct()
    {
        $this->inventory = array();    
    }

    public function getInventory(){
        return $this->inventory;
    }

    public function setInventory($inventory){
        $this->inventory = $inventory;
    }

    public function countItems()
    {
        return count($this->inventory);
    }

    public function findInventoryItem($sku)
    {
        return $this->inventory[$sku] ?? null;
    }

    public function isEmpty()
    {
        return (empty($this->inventory));
    }

    public function addItem(Item $item){

        if(!empty($this->inventory)){
            foreach($this->inventory as $oldItem){
                if($oldItem->getSku() == $item->getSku()){
                    echo "This SKU is already in use.\n";
                    return;
                }
            }
        }

       if(empty($this->inventory)){
        $sku = $item->getSku();
        $this->inventory[$sku] = $item;
        echo "You successfully added " . $item->getName() . " to the Inventory. \nYou currently have 1 item.\n";
       }
       else {
        $sku = $item->getSku();
        $this->inventory[$sku] = $item;
        echo "You successfully added " . $item->getName() . " to the Inventory. \nYou currently have " . count($this->inventory) . " items.\n";
       }   
    }

    public function readItems(){
        foreach($this->inventory as $array){

            echo $array->name . ' ' . $array->quantity . ' ' . $array->price . ' = ' . $array->quantity*$array->price . "\n";
            
        }
    }

    public function getItem($sku){
        foreach($this->inventory as $item){
            if($item->sku == $sku){
                return $item;
            }
        }
    }

    public function totalPrice(){
        $total = 0;
        foreach($this->inventory as $item){
            $total += $item->getPrice()*$item->getQuantity();
        }
        return "Total: " ."$" .  $total . "\n";
    }
}