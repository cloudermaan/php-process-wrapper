<?php
use UK\CO\UKMaker\ProcessWrapper\ProcessWrapper;
use UK\CO\UKMaker\ProcessWrapper\ChildProcess;
use UK\CO\UKMaker\ProcessWrapper\IProcessWrapperEventHandler;
use UK\CO\UKMaker\ProcessWrapper\RespawningEventHandler;

$sRoot = __DIR__.'/../../lib/UK/CO/UKMaker/ProcessWrapper';

require_once $sRoot."/ProcessWrapper.php";
require_once $sRoot."/ChildProcess.php";
require_once $sRoot."/IProcessWrapperEventHandler.php";
require_once $sRoot."/RespawningEventHandler.php";
/**
* Example script to demonstrate use of the ProcessWrapper
**/
class DemoProcess extends ChildProcess {

	public function run() {
	
		for($i=0; $i<200; $i++) {
			echo "Child ".$this->getPid()." loop $i\n";
			sleep(1);
			
			if(rand(1,100) > 60) {
				echo "CRASH\n";
				$a = $b->noMethod();
			}
		}
		
		return 1;
	}
}


$fnCreate = function($iChildId) {

	return new DemoProcess($iChildId);
	
};

$iInitialChildren = 4;
$iMaxChildren = 10;

$oWrapper = new ProcessWrapper($iInitialChildren, $iMaxChildren, $fnCreate);

$oHandler = new RespawningEventHandler(RespawningEventHandler::RESPAWN_FIXED, 10, $iInitialChildren);
$oWrapper->addEventHandler($oHandler);

$oStats = new StatisticsGatheringEventHandler(200);
$oWrapper->addEventHandler($oStats);


$oWrapper->run();
