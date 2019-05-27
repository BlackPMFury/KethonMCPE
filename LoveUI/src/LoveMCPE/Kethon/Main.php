<?php

namespace LoveMCPE\Kethon;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use jojoe7777\FormAPI;

class Main extends PluginBase implements Listener{
	public $love = "§6[§aLove§cUI§6]";
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getLogger()->info($this->love . " §l§aEnable Plugin.");
		@mkdir($this->getDataFolder());
		$this->nolove = new Config($this->getDataFolder()."nolove.txt", Config::ENUM);
		$this->saveDefaultConfig();
	}
	
	public function onDisable(){
		$this->nolove->save();
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch($cmd->getName()){
			case "love":
			if(!($sender instanceof Player)){
				$this->getServer()->getLogger()->notice("Dont Use That Command In Here.");
				return true;
			}
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null) {
				}
				switch ($result) {
					case 0:
					$sender->sendMessage("§c");
					break;
					case 1:
					$sender->sendMessage("Nhập Tên Vào Input xD");
					break;
					case 2:
					$this->ketHon($sender);
					break;
					case 3:
					$this->lyHon($sender);
					break;
				}
			});
			
			$form->setTitle("§b-=-=-=| ".$this->love."§b |=-=-=-");
			$form->setContent("§bBuild By BlackPMFury");
			$form->addButton("§cEXIT", 0);
			$form->addButton("§bHELP", 1);
			$form->addButton("§aKết Hôn", 2);
			$form->addButton("§cLy Hôn", 3);
			$form->sendToPlayer($sender);
		}
		return true;
	}
	
	public function ketHon($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $sender, $data){
			if(!(isset($data[0]))){
				return false;
			}
			$yeu = array_shift($data);
			if($this->nolove->exists(strtolower($yeu))){
				$sender->sendMessage($this->love . " §l§aBạn Không Thể Yêu §6". $yeu);
				return true;
			}else{
				$yeunguoichoi = $this->getServer()->getPlayer($yeu);
				$papers = Item::get(339,0,1);
				if($yeunguoichoi !== null and $yeunguoichoi->isOnline()){
					$yeunguoichoi->sendMessage("§l§a Bạn Và§6 ".$data[0]."§a Đã Két Hôn!");
					$papers->setCustomName("§a-=• §eGiấy Chứng Nhận Kết Hôn§a •=-");
					$sender->getInventory()->addItem($papers);
					$this->getServer()->broadcastMessage("§b ".$sender->getName()."§a Muôn kết hôn với §b". $data[0]);
					if(isset($data[0])){
						$yeunguoichoi->sendMessage("Reason: " . implode(" ", $data));
					}
					$yeunguoichoi->sendMessage("§a Hãy Đợi Người ấy Biết Và Họ sẽ Như thế nào, Tôi biết Đấy xD");
					return true;
				}else{
					$sender->sendMessage("§c Không Có Sẵn!");
					return true;
				}
			}
		});
		$form->setTitle("§b-=-=-=| ".$this->love."§b |=-=-=-");
		$form->addInput("§aName:");
		$form->addLabel("§bGood Luck.");
		$form->sendToPlayer($sender);
		return true;
	}
	
	public function lyHon($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createCustomForm(Function (Player $sender, $data){
			if(!(isset($data[0]) || isset($data[1]))){
				return false;
			}
			if($data[1] == "koyeu"){
				$this->nolove->set(strtolower($sender->getName()));
				$sender->sendMessage($this->love . "§c Bạn Sẽ Không Còn Được Yêu. #ForeverAlone");
				return true;
			}elseif($data[1] == "lyhon"){
				$this->nolove->remove(strtolower($sender->getName()));
				$sender->sendMessage($this->love  . "§c Bạn Đã Ly Hôn!");
				$this->getServer()->broadcastMessage("§a ".$sender->getName()."§c Đã Ly Hôn §a". $data[0]);
				return true;
			}else{
				return false;
			}
		});
		$form->setTitle("§b-=-=-=| ".$this->love."§b |=-=-=-");
		$form->addInput("§aName");
		$form->addInput("§aAnwser");
		$form->addLabel("§6tag:\n §akoyeu\n§a lyhon");
		$form->sendToPlayer($sender);
		return true;
	}
	
	
}