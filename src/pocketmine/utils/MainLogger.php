<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\utils;

use LogLevel;
use pocketmine\Thread;
use pocketmine\Worker;

class MainLogger extends \AttachableThreadedLogger {
	protected $logFile;
	protected $logStream;
	protected $shutdown;
	protected $logDebug;
	private $logResource;
	/** @var MainLogger */
	public static $logger = null;

	private $consoleCallback;

	/** Extra Settings */
	protected $write = false;

	public $shouldSendMsg = "";
	public $shouldRecordMsg = false;
	private $lastGet = 0;

	/**
	 * @param $b
	 */
	public function setSendMsg($b){
		$this->shouldRecordMsg = $b;
		$this->lastGet = time();
	}

	/**
	 * @return string
	 */
	public function getMessages(){
		$msg = $this->shouldSendMsg;
		$this->shouldSendMsg = "";
		$this->lastGet = time();
		return $msg;
	}

	/**
	 * @param string $logFile
	 * @param bool   $logDebug
	 *
	 * @throws \RuntimeException
	 */
	public function __construct($logFile, $logDebug = false){
		if(static::$logger instanceof MainLogger){
			throw new \RuntimeException("主记录器已被创建");
		}
		static::$logger = $this;
		touch($logFile);
		$this->logFile = $logFile;
		$this->logDebug = (bool) $logDebug;
		$this->logStream = new \Threaded;
		$this->start();
	}

	/**
	 * @return MainLogger
	 */
	public static function getLogger(){
		return static::$logger;
	}

	/**
	 * @param string $message
	 * @param string $name
	 */
	public function emergency($message, $name = "致命错误"){
		$this->send($message, \LogLevel::EMERGENCY, $name, TextFormat::RED);
	}

	/**
	 * @param string $message
	 * @param string $name
	 */
	public function alert($message, $name = "二级警告"){
		$this->send($message, \LogLevel::ALERT, $name, TextFormat::RED);
	}

	/**
	 * @param string $message
	 * @param string $name
	 */
	public function critical($message, $name = "严重错误"){
		$this->send($message, \LogLevel::CRITICAL, $name, TextFormat::RED);
	}

	/**
	 * @param string $message
	 * @param string $name
	 */
	public function error($message, $name = "错误"){
		$this->send($message, \LogLevel::ERROR, $name, TextFormat::DARK_RED);
	}

	/**
	 * @param string $message
	 * @param string $name
	 */
	public function warning($message, $name = "一级警告"){
		$this->send($message, \LogLevel::WARNING, $name, TextFormat::YELLOW);
	}

	/**
	 * @param string $message
	 * @param string $name
	 */
	public function notice($message, $name = "注意"){
		$this->send(TextFormat::BOLD . $message, \LogLevel::NOTICE, $name, TextFormat::AQUA);
	}

	/**
	 * @param string $message
	 * @param string $name
	 */
	public function info($message, $name = "信息"){
		$this->send($message, \LogLevel::INFO, $name, TextFormat::WHITE);
	}

	/**
	 * @param string $message
	 * @param string $name
	 */
	public function debug($message, $name = "程序除错"){
		if($this->logDebug === false){
			return;
		}
		$this->send($message, \LogLevel::DEBUG, $name, TextFormat::GRAY);
	}

	/**
	 * @param bool $logDebug
	 */
	public function setLogDebug($logDebug){
		$this->logDebug = (bool) $logDebug;
	}

	/**
	 * @param \Throwable $e
	 * @param null       $trace
	 */
	public function logException(\Throwable $e, $trace = null){
		if($trace === null){
			$trace = $e->getTrace();
		}
		$errstr = $e->getMessage();
		$errfile = $e->getFile();
		$errno = $e->getCode();
		$errline = $e->getLine();

		$errorConversion = [
			0 => "异常",
			E_ERROR => "致命错误",
			E_WARNING => "警告",
			E_PARSE => "解析错误",
			E_NOTICE => "未知变量",
			E_CORE_ERROR => "插件加载时出现严重错误",
			E_CORE_WARNING => "一般性错误",
			E_COMPILE_ERROR => "编译错误",
			E_COMPILE_WARNING => "不推荐使用此语法",
			E_USER_ERROR => "用户定义错误",
			E_USER_WARNING => "脚本执行失败",
			E_USER_NOTICE => "此脚本可能会出现错误",
			E_STRICT => "此编码不会兼容上一个版本的PHP",
			E_RECOVERABLE_ERROR => "严重错误",
			E_DEPRECATED => "函数过时",
			E_USER_DEPRECATED => "用户定义的函数过时",
		];
		if($errno === 0){
			$type = LogLevel::CRITICAL;
		}else{
			$type = ($errno === E_ERROR or $errno === E_USER_ERROR) ? LogLevel::ERROR : (($errno === E_USER_WARNING or $errno === E_WARNING) ? LogLevel::WARNING : LogLevel::NOTICE);
		}
		$errno = isset($errorConversion[$errno]) ? $errorConversion[$errno] : $errno;
		if(($pos = strpos($errstr, "\n")) !== false){
			$errstr = substr($errstr, 0, $pos);
		}
		$errfile = \pocketmine\cleanPath($errfile);
		$this->log($type, get_class($e) . ": \"$errstr\" ($errno) 在 \"$errfile\" 的第 $errline 行");
		foreach(@\pocketmine\getTrace(1, $trace) as $i => $line){
			$this->debug($line);
		}
	}

	/**
	 * @param mixed  $level
	 * @param string $message
	 */
	public function log($level, $message){
		switch($level){
			case LogLevel::EMERGENCY:
				$this->emergency($message);
				break;
			case LogLevel::ALERT:
				$this->alert($message);
				break;
			case LogLevel::CRITICAL:
				$this->critical($message);
				break;
			case LogLevel::ERROR:
				$this->error($message);
				break;
			case LogLevel::WARNING:
				$this->warning($message);
				break;
			case LogLevel::NOTICE:
				$this->notice($message);
				break;
			case LogLevel::INFO:
				$this->info($message);
				break;
			case LogLevel::DEBUG:
				$this->debug($message);
				break;
		}
	}

	public function shutdown(){
		$this->shutdown = true;
	}

	/**
	 * @param $message
	 * @param $level
	 * @param $prefix
	 * @param $color
	 */
	protected function send($message, $level, $prefix, $color){
		$now = time();

		$thread = \Thread::getCurrentThread();
		if($thread === null){
			$threadName = "服务器主线程";
		}elseif($thread instanceof Thread or $thread instanceof Worker){
			$threadName = $thread->getThreadName() . " 进程";
		}else{
			$threadName = (new \ReflectionClass($thread))->getShortName() . " 进程";
		}

		if($this->shouldRecordMsg){
			if((time() - $this->lastGet) >= 10) $this->shouldRecordMsg = false; // 10 secs timeout
			else{
				if(strlen($this->shouldSendMsg) >= 10000) $this->shouldSendMsg = "";
				$this->shouldSendMsg .= $color . "|" . $prefix . "|" . trim($message, "\r\n") . "\n";
			}
		}

		$message = TextFormat::toANSI(TextFormat::AQUA . "[GenisysPlus] " . TextFormat::RESET . $color . "[" . $threadName . "/" . $prefix . "]:" . " " . $message . TextFormat::RESET);
		//$message = TextFormat::toANSI(TextFormat::AQUA . "[GenisysPlus]->[" . date("H:i:s", $now) . "] " . TextFormat::RESET . $color . "[$prefix]:" . " " . $message . TextFormat::RESET);
		//$message = TextFormat::toANSI(TextFormat::AQUA . "[" . date("H:i:s") . "] ". TextFormat::RESET . $color ."<".$prefix . ">" . " " . $message . TextFormat::RESET);
		$cleanMessage = TextFormat::clean($message);

		if(!Terminal::hasFormattingCodes()){
			echo $cleanMessage . PHP_EOL;
		}else{
			echo $message . PHP_EOL;
		}

		if(isset($this->consoleCallback)){
			call_user_func($this->consoleCallback);
		}

		if($this->attachment instanceof \ThreadedLoggerAttachment){
			$this->attachment->call($level, $message);
		}

		$this->logStream[] = date("Y-m-d", $now) . " " . $cleanMessage . "\n";
		if($this->logStream->count() === 1){
			$this->synchronized(function(){
				$this->notify();
			});
		}
	}

	/*public function run(){
		$this->shutdown = false;
		if($this->write){
			$this->logResource = fopen($this->logFile, "a+b");
			if(!is_resource($this->logResource)){
				throw new \RuntimeException("Couldn't open log file");
			}

			while($this->shutdown === false){
				if(!$this->write) {
					fclose($this->logResource);
					break;
				}
				$this->synchronized(function(){
					while($this->logStream->count() > 0){
						$chunk = $this->logStream->shift();
						fwrite($this->logResource, $chunk);
					}

					$this->wait(25000);
				});
			}

			if($this->logStream->count() > 0){
				while($this->logStream->count() > 0){
					$chunk = $this->logStream->shift();
					fwrite($this->logResource, $chunk);
				}
			}

			fclose($this->logResource);
		}
	}*/

	public function run(){
		$this->shutdown = false;
		while($this->shutdown === false){
			$this->synchronized(function(){
				while($this->logStream->count() > 0){
					$chunk = $this->logStream->shift();
					if($this->write){
						$this->logResource = file_put_contents($this->logFile, $chunk, FILE_APPEND);
					}
				}

				$this->wait(200000);
			});
		}

		if($this->logStream->count() > 0){
			while($this->logStream->count() > 0){
				$chunk = $this->logStream->shift();
				if($this->write){
					$this->logResource = file_put_contents($this->logFile, $chunk, FILE_APPEND);
				}
			}
		}
	}

	/**
	 * @param $write
	 */
	public function setWrite($write){
		$this->write = $write;
	}

	/**
	 * @param $callback
	 */
	public function setConsoleCallback($callback){
		$this->consoleCallback = $callback;
	}
}
