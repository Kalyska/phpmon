<?php
require("./Attack.php");

// création de la classe Pokemon
class Pokemon
{
    public int $id;
    public string $name;
    public int $puissance;
    public string $type;
    public string $weak;
    private $attacks = array();
    public int $life;

    // constructeurs de la classe
    public function __construct(int $id, string $name, int $puissance, string $type, string $weak, int $life = 100)
    {
        $this->id = $id;
        $this->name = $name;
        $this->puissance = $puissance;
        $this->type = $type;
        $this->weak = $weak;
        $this->life = $life;
    }

    //méthodes
    public function addAttack(string $name, int $damage, string $type)
    {
        $attack = new Attack($name, $damage, $type);
        array_push($this->attacks, $attack);
    }

    public function getAttacks(): array
    {
        return $this->attacks;
    }
}
