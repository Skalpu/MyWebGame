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
	
	$preparationSpells = [];
	$combatSpells = [];

	/* PREPARATION SPELLS */
	$magicznaTarcza = new Spell("Magiczna tarcza", 1, "preparation", "buff", "armor", 1, "earth", 3, 5, 10);
	array_push($preparationSpells, $magicznaTarcza);
	$antymagicznaTarcza = new Spell("Tarcza antymagiczna", 1, "preparation", "buff", "magicdefense", 1, "water", 3, 5, 10);
	array_push($preparationSpells, $antymagicznaTarcza);
	
	/* COMBAT SPELLS */
	$plomyk = new Spell("Magiczny płomyk", 1, "combat", "damage", "hp", 1, "fire", 5, 6, 10);
	array_push($combatSpells, $plomyk);
	
	
?>