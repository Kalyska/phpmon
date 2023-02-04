<?php
// création de la classe Attack
class Attack
{
    public string $name;
    public int $damage;
    public string $type;

    // constructeurs de la classe
    public function __construct(string $name, int $damage, string $type)
    {
        $this->name = $name;
        $this->damage = $damage;
        $this->type = $type;
    }
}
