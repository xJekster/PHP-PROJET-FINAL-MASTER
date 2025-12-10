<?php
/*
 * Plan d'une maison
 * 1 Toit
 * 4 Murs
 * 1 Porte
 * 1 Fondation
 * 1 Fenêtre
 */

//Convention de nommage : PascalCase
class House{

    private $wall = 4;
    private $door = 1;
    private $fundation = 1;
    private $window = 1;
    private $floor;
    private $stairs;

    public function __construct(int $floor=0){
        for($cpt=0;$cpt<$floor;$cpt++){
            $this->addFloor();
        }
    }


    //Propriétés de la classe
    private $roof = 1;

    /**
     * @return int
     */
    public function getRoof(): int
    {
        return $this->roof;
    }

    /**
     * @return int
     */
    public function getWall(): int
    {
        return $this->wall;
    }


    /**
     * @return int
     */
    public function getDoor(): int
    {
        return $this->door;
    }

    /**
     * @param int $door
     */
    public function setDoor(int $door): void
    {
        if($door < 1) return;
        $this->door = $door;
    }

    /**
     * @return int
     */
    public function getFundation(): int
    {
        return $this->fundation;
    }

    /**
     * @return int
     */
    public function getWindow(): int
    {
        return $this->window;
    }

    /**
     * @param int $window
     */
    public function setWindow(int $window): void
    {
        $this->window = $window;
    }

    /**
     * @return mixed
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * @return mixed
     */
    public function getStairs()
    {
        return $this->stairs;
    }

    public function addFloor(){
        $this->floor++;
        $this->wall+=4;
        $this->window++;
        $this->stairs++;
    }

}
//L'instance de la classe House
//Création de l'objet myHouse à partir de la classe House
$myHouse1 = new House(3);
$myHouse2 = new House();
//Modification de l'attribut
//La propriété est privée pour éviter d'avoir une maison non fonctionnelle
//$myHouse2->floor++;
$myHouse2->addFloor();

echo "La maison 1 possède ".$myHouse1->getFloor()." étages";

/*
echo "<pre>";
print_r($myHouse1);
print_r($myHouse2);
*/