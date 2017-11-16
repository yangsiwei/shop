<?php
require_once APP_ROOT_PATH."system/libs/fair_fetch.php";

class wy_fair_fetch extends fair_fetch
{
	
	public function __construct()
	{
		$this->type = "wy";
		$this->waitsec = 120;
		$this->name = "重庆时时彩";
		$this->check_link = "http://caipiao.163.com/award/cqssc/__DATE__.html";
		parent::__construct();
	}
	
	
	/**
	 * 获取指定日期的初始化彩票数据信息列表
	 * @param $datestr 指定日期字符串，格式：yyyyMMdd
	 */
	protected function getLotteryInitInfo($datestr) {
		$today = strtotime($datestr);
		$tomorrow = $today + 86400;
		$shortdatestr = substr($datestr, 2);
		$todaystr = date('Y-m-d', $today);
		$tomorrowstr = date('Y-m-d', $tomorrow);
		return array(
			array('date' => $datestr, 'period' => $shortdatestr.'001', 'drawtime' => $todaystr.' 00:05:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'002', 'drawtime' => $todaystr.' 00:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'003', 'drawtime' => $todaystr.' 00:15:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'004', 'drawtime' => $todaystr.' 00:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'005', 'drawtime' => $todaystr.' 00:25:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'006', 'drawtime' => $todaystr.' 00:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'007', 'drawtime' => $todaystr.' 00:35:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'008', 'drawtime' => $todaystr.' 00:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'009', 'drawtime' => $todaystr.' 00:45:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'010', 'drawtime' => $todaystr.' 00:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'011', 'drawtime' => $todaystr.' 00:55:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'012', 'drawtime' => $todaystr.' 01:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'013', 'drawtime' => $todaystr.' 01:05:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'014', 'drawtime' => $todaystr.' 01:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'015', 'drawtime' => $todaystr.' 01:15:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'016', 'drawtime' => $todaystr.' 01:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'017', 'drawtime' => $todaystr.' 01:25:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'018', 'drawtime' => $todaystr.' 01:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'019', 'drawtime' => $todaystr.' 01:35:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'020', 'drawtime' => $todaystr.' 01:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'021', 'drawtime' => $todaystr.' 01:45:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'022', 'drawtime' => $todaystr.' 01:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'023', 'drawtime' => $todaystr.' 01:55:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'024', 'drawtime' => $todaystr.' 10:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'025', 'drawtime' => $todaystr.' 10:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'026', 'drawtime' => $todaystr.' 10:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'027', 'drawtime' => $todaystr.' 10:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'028', 'drawtime' => $todaystr.' 10:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'029', 'drawtime' => $todaystr.' 10:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'030', 'drawtime' => $todaystr.' 11:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'031', 'drawtime' => $todaystr.' 11:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'032', 'drawtime' => $todaystr.' 11:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'033', 'drawtime' => $todaystr.' 11:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'034', 'drawtime' => $todaystr.' 11:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'035', 'drawtime' => $todaystr.' 11:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'036', 'drawtime' => $todaystr.' 12:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'037', 'drawtime' => $todaystr.' 12:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'038', 'drawtime' => $todaystr.' 12:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'039', 'drawtime' => $todaystr.' 12:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'040', 'drawtime' => $todaystr.' 12:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'041', 'drawtime' => $todaystr.' 12:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'042', 'drawtime' => $todaystr.' 13:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'043', 'drawtime' => $todaystr.' 13:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'044', 'drawtime' => $todaystr.' 13:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'045', 'drawtime' => $todaystr.' 13:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'046', 'drawtime' => $todaystr.' 13:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'047', 'drawtime' => $todaystr.' 13:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'048', 'drawtime' => $todaystr.' 14:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'049', 'drawtime' => $todaystr.' 14:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'050', 'drawtime' => $todaystr.' 14:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'051', 'drawtime' => $todaystr.' 14:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'052', 'drawtime' => $todaystr.' 14:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'053', 'drawtime' => $todaystr.' 14:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'054', 'drawtime' => $todaystr.' 15:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'055', 'drawtime' => $todaystr.' 15:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'056', 'drawtime' => $todaystr.' 15:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'057', 'drawtime' => $todaystr.' 15:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'058', 'drawtime' => $todaystr.' 15:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'059', 'drawtime' => $todaystr.' 15:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'060', 'drawtime' => $todaystr.' 16:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'061', 'drawtime' => $todaystr.' 16:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'062', 'drawtime' => $todaystr.' 16:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'063', 'drawtime' => $todaystr.' 16:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'064', 'drawtime' => $todaystr.' 16:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'065', 'drawtime' => $todaystr.' 16:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'066', 'drawtime' => $todaystr.' 17:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'067', 'drawtime' => $todaystr.' 17:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'068', 'drawtime' => $todaystr.' 17:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'069', 'drawtime' => $todaystr.' 17:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'070', 'drawtime' => $todaystr.' 17:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'071', 'drawtime' => $todaystr.' 17:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'072', 'drawtime' => $todaystr.' 18:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'073', 'drawtime' => $todaystr.' 18:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'074', 'drawtime' => $todaystr.' 18:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'075', 'drawtime' => $todaystr.' 18:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'076', 'drawtime' => $todaystr.' 18:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'077', 'drawtime' => $todaystr.' 18:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'078', 'drawtime' => $todaystr.' 19:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'079', 'drawtime' => $todaystr.' 19:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'080', 'drawtime' => $todaystr.' 19:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'081', 'drawtime' => $todaystr.' 19:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'082', 'drawtime' => $todaystr.' 19:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'083', 'drawtime' => $todaystr.' 19:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'084', 'drawtime' => $todaystr.' 20:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'085', 'drawtime' => $todaystr.' 20:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'086', 'drawtime' => $todaystr.' 20:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'087', 'drawtime' => $todaystr.' 20:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'088', 'drawtime' => $todaystr.' 20:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'089', 'drawtime' => $todaystr.' 20:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'090', 'drawtime' => $todaystr.' 21:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'091', 'drawtime' => $todaystr.' 21:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'092', 'drawtime' => $todaystr.' 21:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'093', 'drawtime' => $todaystr.' 21:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'094', 'drawtime' => $todaystr.' 21:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'095', 'drawtime' => $todaystr.' 21:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'096', 'drawtime' => $todaystr.' 22:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'097', 'drawtime' => $todaystr.' 22:05:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'098', 'drawtime' => $todaystr.' 22:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'099', 'drawtime' => $todaystr.' 22:15:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'100', 'drawtime' => $todaystr.' 22:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'101', 'drawtime' => $todaystr.' 22:25:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'102', 'drawtime' => $todaystr.' 22:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'103', 'drawtime' => $todaystr.' 22:35:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'104', 'drawtime' => $todaystr.' 22:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'105', 'drawtime' => $todaystr.' 22:45:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'106', 'drawtime' => $todaystr.' 22:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'107', 'drawtime' => $todaystr.' 22:55:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'108', 'drawtime' => $todaystr.' 23:00:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'109', 'drawtime' => $todaystr.' 23:05:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'110', 'drawtime' => $todaystr.' 23:10:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'111', 'drawtime' => $todaystr.' 23:15:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'112', 'drawtime' => $todaystr.' 23:20:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'113', 'drawtime' => $todaystr.' 23:25:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'114', 'drawtime' => $todaystr.' 23:30:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'115', 'drawtime' => $todaystr.' 23:35:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'116', 'drawtime' => $todaystr.' 23:40:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'117', 'drawtime' => $todaystr.' 23:45:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'118', 'drawtime' => $todaystr.' 23:50:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'119', 'drawtime' => $todaystr.' 23:55:00'),
			array('date' => $datestr, 'period' => $shortdatestr.'120', 'drawtime' => $tomorrowstr.' 00:00:00')
		);
	}
	
	/**
	 * 从彩票源网站采集指定日期和指定期号后（含指定期号）的彩票数据
	 * @param $datestr 指定日期，格式：yyyyMMdd
	 * @param $period 指定期号，期号为空时获取指定日期的所有彩票数据
	 * @return 采集到的彩票数据列表
	 */
	protected function queryLotteryData($datestr, $period) {

		// 获取指定日期的所有彩票数据，并按期号从小到大排序
		$url = 'http://caipiao.163.com/award/daily_refresh.html?gameEn=ssc&date='.$datestr;
		$html = $this->getCURL($url);

		preg_match_all('/<td\s+class=["\']start["\']\s+data-win-number=["\'][^"\']*["\']\s+data-period=["\']\d+["\']>/', $html, $matches);
		$records = array_unique($matches[0]);
		$items = array();
		foreach($records as $record){
			preg_match_all('/data-win-number=["\']([^"\']*)["\']\s+data-period=["\'](\d+)["\']/', $record, $m);
			$item = array('date'=>$datestr, 'period'=>$m[2][0], 'number'=>$this->removeSpace($m[1][0]));
			array_push($items, $item);
		}
		sort($items);
		// 提取出指定期号及之后期号的数据
		if (empty($period)) {
			return $items;
		} else {
			$perioditems = array();
			$found = false;
			foreach($items as $item){
				if ($item['period'] == $period) {
					$found = true;
				}
				if ($found) {
					array_push($perioditems, $item);
				}
			}
			return $perioditems;
		}
	}
	protected function getCURL($url,$referer = "http://www.fanwe.com"){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_TIMEOUT,60);
	    if(!empty($referer))
	        curl_setopt ($ch, CURLOPT_REFERER,$referer);
	
	    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
	    curl_setopt($ch, CURLOPT_ENCODING,'gzip,deflate');
	    curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
	    curl_setopt($ch, CURLOPT_MAXREDIRS,11);
	    curl_setopt($ch, CURLOPT_NOBODY, 0);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $content = curl_exec($ch);
	
	    curl_close($ch);
	    return $content;
	}
	
}

?>