<?php

	class Spell
	{
		public $name;
		public $level;
		public $type; //preparation or combat
		public $subtype; //buff / debuff / damage
		public $statAffected; //use instead of the above?
		public $aoe; //number of people affected
		public $element;
		public $minEffect;
		public $maxEffect;
		public $manaCost;
		public $photo;
		
		//Constructor
		function __construct($name, $level, $type, $subtype, $statAffected, $aoe, $element, $minEffect, $maxEffect, $manaCost)
		{
			$this->name = $name;
			$this->level = $level;
			$this->type = $type;
			$this->subtype = $subtype;
			$this->statAffected = $statAffected;
			$this->aoe = $aoe;
			$this->element = $element;
			$this->minEffect = $minEffect;
			$this->maxEffect = $maxEffect;
			$this->manaCost = $manaCost;
			$this->photo = "/gfx/spells/$name.jpg";
		}
	}
	
	$preparationSpells = [
		0 => new Spell("Kamienna skóra", 1, "preparation", "buff", "armor", 1, "earth", 3, 5, 10),
		1 => new Spell("Odbicie magii", 1, "preparation", "buff", "magicdefense", 1, "air", 3, 5, 10),
		2 => new Spell("Tarcza płomieni", 1, "preparation", "buff", "reflect", 1, "fire", 2, 3, 15),
		3 => new Spell("Śliskoskórność", 1, "preparation", "buff", "dodge", 1, "water", 3, 5, 15),
	];
	$combatSpells = [
		0 => new Spell("Magiczny płomień", 1, "combat", "damage", "hp", 1, "fire", 5, 6, 10),
	];

?>