<?php

abstract class fair_fetch
{
	/**
	 * 采集延时(秒)
	 * @var unknown_type
	 */
	var $waitsec;
	var $name;
	/**
	 * 
	 * 类型名称
	 */
	var $type;  
	/**
	 * 数据库对象
	 */
	var $db;
	
	/**
	 * 获取指定日期的初始化彩票数据信息列表
	 * @param $datestr 指定日期字符串，格式：yyyyMMdd
	 */
	abstract protected function getLotteryInitInfo($datestr);
	
	/**
	 * 从彩票源网站采集指定日期和指定期号后（含指定期号）的彩票数据
	 * @param $datestr 指定日期，格式：yyyyMMdd
	 * @param $period 指定期号，期号为空时获取指定日期的所有彩票数据
	 * @return 采集到的彩票数据列表
	 */
	abstract protected function queryLotteryData($datestr, $period);
	
	/**
	 * 构造函数
	 * @param $db 数据库对象
	 */
	function __construct()
	{
		$this->db = $GLOBALS['db'];
	}
	
	/**
	 * 删除字符串中的所有空格
	 * @param $str 原始字符串
	 * @return 删除空格后的字符串
	 */
	protected function removeSpace($str) {
		return preg_replace('/\s+/', '', $str); 
	}
	

	
	
	/**
	 * 创建指定日期的彩票记录到表中（日期、期号、开奖时间、开奖号码等）
	 * @param $dates 指定日期列表，字符串类型，格式：yyyyMMdd
	 */
	protected function createDateData($dates) {
		foreach($dates as $date) {
			// 日期为空时跳过
			if (empty($date)) {
				continue;
			}
			// 记录存在时跳过
			$result = $this->db->getRow("select id from ".DB_PREFIX."fair_fetch where fair_type = '".$this->type."' and drawdate='".$date."' limit 0,1");
			if ($result) {
				continue;
			}
			// 插入初始化记录
			$items = $this->getLotteryInitInfo($date);
			$values = '';
			foreach($items as $item) {
				if (!empty($values)) {
					$values .= ',';
				}
				$values .= "('".$this->type."','".$item['date']."','".$item['period']."','".$item['drawtime']."',null,".NOW_TIME.",null)";
			}
			$sql = "insert into ".DB_PREFIX."fair_fetch(fair_type,drawdate,period,drawtime,number,addtime,updatetime)values".$values.";";
			//echo $sql;
			$this->db->query($sql);
		}
	}
	
	/**
	 * 从表中获取待采集的彩票信息列表，列表元素包含彩票日期和起始彩票期号
	 * @return 待采集的彩票信息列表
	 */
	protected function getToCollectInfo() {
		$sql = "select drawdate as date,min(period) as period from ".DB_PREFIX."fair_fetch where (fair_type = '".$this->type."' and (number is null or trim(number)='')) group by drawdate";
		return $this->db->getAll($sql);
	}
	
	/**
	 * 保存采集到的彩票数据到表中
	 * @param $infos 待保存彩票数据列表，列表元素信息：['date'=>'','period'=>'','number'=>'']
	 */
	protected function saveData($infos) {
		foreach($infos as $info) {
			$sql = "update ".DB_PREFIX."fair_fetch set number='".$info['number']."',updatetime=".NOW_TIME." where fair_type = '".$this->type."' and drawdate='".$info['date']."' and period='".$info['period']."'";
			$this->db->query($sql);
		}
	}
	
	
	
	/**
	 * 从表中获取最新的已初始化数据的彩票日期（字符串类型，格式：yyyyMMdd）
	 * @return 最新的已初始化数据的彩票日期（字符串类型，格式：yyyyMMdd）
	 */
	protected function getLastDatdDate() {
		$sql = "select max(drawdate) from ".DB_PREFIX."fair_fetch where fair_type = '".$this->type."'";
		return $this->db->getOne($sql);
	}
	
	/**
	 * 从表中获取下一个待采集彩票的开奖时间，作为下一次采集时间
	 * @return 下一次采集时间，UNIX时间戳
	 */
	public function getNextCollectTime() {
		$sql = "select min(drawtime) as drawtime from ".DB_PREFIX."fair_fetch where number is null or trim(number) = ''";
		$drawtime = $this->db->getOne($sql);
		if ($drawtime) {
			return to_timespan($drawtime);
		} else {
			return null;
		}
	}
	
	/**
	 * 创建今天及之后n天内的未创建的彩票记录
	 */
	public function createData() {
		// 从表中获取最新的已初始化数据的彩票日期
		$lastdate = $this->getLastDatdDate();
		
		// 至少要初始化今天及明天的两天数据
		$todaytime = NOW_TIME;
		$tomorrowtime = $todaytime + 86400;
		$todaystr = date('Ymd', $todaytime);
		$tomorrowstr = date('Ymd', $tomorrowtime);
		$dates = array();
		if (empty($lastdate) || $lastdate < $todaystr) {
			array_push($dates, $todaystr);
			array_push($dates, $tomorrowstr);
		} else if ($lastdate == $todaystr) {
			array_push($dates, $tomorrowstr);
		}
		// 创建指定日期的彩票记录
		$this->createDateData($dates);
	}
	
	/**
	 * 采集所有待采集且可采集的彩票数据，并将采集结果数据更新到表中，最后设置下次执行采集时间
	 * @return  $infos 待保存彩票数据列表，列表元素信息：['date'=>'','period'=>'','number'=>'']
	 */
	public function collectData() {
		// 获取待采集的彩票信息列表
		$queryinfos = $this->getToCollectInfo();
		// 获取所有采集彩票数据
		$saveinfos = array();
		foreach($queryinfos as $queryinfo) {
			$items = $this->queryLotteryData($queryinfo['date'], $queryinfo['period']);
			foreach($items as $item) {
				array_push($saveinfos, $item);
			}
		}
		$count = count($saveinfos);
		// 保存采集到的彩票数据
		if ($count > 0) {
			$this->saveData($saveinfos);
		}

		// 返回
		return $saveinfos;
	}
	
	protected $check_link;
	/**
	 * 查询地址
	 * @param unknown_type $date  Ymd
	 * @return mixed
	 */
	public function get_check_link($date)
	{
		return str_replace("__DATE__", $date, $this->check_link);
	}
	
}

?>