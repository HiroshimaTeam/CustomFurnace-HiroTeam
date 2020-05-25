<?php

namespace HiroTeam\customfurnace;

use pocketmine\event\Listener;
use pocketmine\inventory\FurnaceRecipe;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;


class CustomFurnace extends PluginBase implements Listener
{

    public function onEnable()
    {
        $this -> getServer() -> getPluginManager() -> registerEvents($this, $this);

        @mkdir($this -> getDataFolder());
        if (! file_exists($this -> getDataFolder() . "config.yml")) {
            $this -> saveResource('config.yml');
        }
        $this -> furnaceDataCache();
    }
    public function furnaceDataCache(): void{

        $pk = new CraftingDataPacket();
        foreach ($this->getAllAdd() as $recipe) {

            $result = $this->getItem($recipe["result"]);
            $recipes = $this->getItem($recipe["recipe"]);

            $recipe = new FurnaceRecipe(
                $result,
                $recipes
            );
            $pk->addFurnaceRecipe($recipe);
            $this->getServer()->getCraftingManager()->registerFurnaceRecipe($recipe);
        }
    }
    public function getAllAdd() : array{
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $all = $config->getAll()["add"];
        return $all;
    }
    public function getItem(array $item) : Item
    {
        $result = Item::get($item[0],$item[1]);
        return $result;
    }

}