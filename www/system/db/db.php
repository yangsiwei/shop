<?php

class mysql_db {
	var $link_id = NULL;
	var $settings = array ();
	var $queryCount = 0;
	var $queryTime = '';
	var $queryLog = array ();
	var $max_cache_time = 30; // 最大的缓存时间，以秒为单位
	var $cache_data_dir = 'public/runtime/app/db_caches/';
	var $root_path = '';
	var $error_message = array ();
	var $platform = '';
	var $version = '';
	var $dbhash = '';
	var $starttime = 0;
	var $timeline = 0;
	var $timezone = 0;
    var $nowSql="";//保存当前执行的sql语句
	var $link_list = array (); // 分布查询链接池
	function __construct($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'utf8', $pconnect = 0, $quiet = 0) {
		$this->mysql_db ( $dbhost, $dbuser, $dbpw, $dbname, $charset, $pconnect, $quiet );
	}
	function mysql_db($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'utf8', $pconnect = 0, $quiet = 0) {
		if (defined ( 'APP_ROOT_PATH' ) && ! $this->root_path) {
			$this->root_path = APP_ROOT_PATH;
		}
		
		if ($quiet) {
			$this->connect ( $dbhost, $dbuser, $dbpw, $dbname, $charset, $pconnect, $quiet );
			foreach ( $GLOBALS ['distribution_cfg'] ['DB_DISTRIBUTION'] as $k => $cfg ) {
				$this->connect_pid ( $k );
			}
		} else {
			$this->settings = array (
					'dbhost' => $dbhost,
					'dbuser' => $dbuser,
					'dbpw' => $dbpw,
					'dbname' => $dbname,
					'charset' => $charset,
					'pconnect' => $pconnect 
			);
		}
	}
	
	/**
	 * 连接指定的连接池
	 *
	 * @param unknown_type $pid        	
	 */
	function connect_pid($pid, $charset = 'utf8') {
		$dbhost = $GLOBALS ['distribution_cfg'] ['DB_DISTRIBUTION'] [$pid] ['DB_HOST'];
		$dbport = $GLOBALS ['distribution_cfg'] ['DB_DISTRIBUTION'] [$pid] ['DB_PORT'];
		$dbuser = $GLOBALS ['distribution_cfg'] ['DB_DISTRIBUTION'] [$pid] ['DB_USER'];
		$dbpw = $GLOBALS ['distribution_cfg'] ['DB_DISTRIBUTION'] [$pid] ['DB_PWD'];
		$dbname = $GLOBALS ['distribution_cfg'] ['DB_DISTRIBUTION'] [$pid] ['DB_NAME'];
		$dbhost .= ":" . $dbport;
		
		if (PHP_VERSION >= '4.2') {
			$this->link_list [$pid] = @mysql_connect ( $dbhost, $dbuser, $dbpw, true );
		} else {
			$this->link_list [$pid] = @mysql_connect ( $dbhost, $dbuser, $dbpw );
		}
		if ($this->link_list [$pid]) {
			$this->version = mysql_get_server_info ( $this->link_list [$pid] );
			/* 如果mysql 版本是 4.1+ 以上，需要对字符集进行初始化 */
			if ($this->version > '4.1') {
				if ($charset != 'latin1') {
					mysql_query ( "SET character_set_connection=$charset, character_set_results=$charset, character_set_client=binary", $this->link_list [$pid] );
				}
				if ($this->version > '5.0.1') {
					mysql_query ( "SET sql_mode=''", $this->link_list [$pid] );
				}
			}
			if ($dbname) {
				if (mysql_select_db ( $dbname, $this->link_list [$pid] ) === false) {
					@mysql_close ( $this->link_list [$pid] );
					$this->link_list [$pid] = null;
				} else {
					return true;
				}
			} else {
				@mysql_close ( $this->link_list [$pid] );
				$this->link_list [$pid] = null;
			}
		}
		
		logger::write ( "db_distribution_init_err:" . $pid, logger::ERR, logger::FILE, "db_distribution" );
		return false;
	}
	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'utf8', $pconnect = 0, $quiet = 0) {
		if ($pconnect) {
			if (! ($this->link_id = @mysql_pconnect ( $dbhost, $dbuser, $dbpw ))) {
				if (! $quiet) {
					$this->ErrorMsg ( "Can't pConnect MySQL Server($dbhost)!" );
				}
				
				return false;
			}
		} else {
			if (PHP_VERSION >= '4.2') {
				$this->link_id = @mysql_connect ( $dbhost, $dbuser, $dbpw, true );
			} else {
				$this->link_id = @mysql_connect ( $dbhost, $dbuser, $dbpw );
				
				mt_srand ( ( double ) microtime () * 1000000 ); // 对 PHP 4.2
					                                                // 以下的版本进行随机数函数的初始化工作
			}
			if (! $this->link_id) {
				if (! $quiet) {
					$this->ErrorMsg ( "Can't Connect MySQL Server($dbhost)!" );
				}
				
				return false;
			}
		}
		
		$this->dbhash = md5 ( $this->root_path . $dbhost . $dbuser . $dbpw . $dbname );
		$this->version = mysql_get_server_info ( $this->link_id );
		
		/* 如果mysql 版本是 4.1+ 以上，需要对字符集进行初始化 */
		if ($this->version > '4.1') {
			if ($charset != 'latin1') {
				mysql_query ( "SET character_set_connection=$charset, character_set_results=$charset, character_set_client=binary", $this->link_id );
			}
			if ($this->version > '5.0.1') {
				mysql_query ( "SET sql_mode=''", $this->link_id );
			}
		}
		
		$sqlcache_config_file = $this->root_path . $this->cache_data_dir . 'sqlcache_config_file_' . $this->dbhash . '.php';
		
		$this->starttime = time ();
		
		if (! file_exists ( $sqlcache_config_file )) {
			if ($dbhost != '.') {
				$result = mysql_query ( "SHOW VARIABLES LIKE 'basedir'", $this->link_id );
				$row = mysql_fetch_assoc ( $result );
				if (! empty ( $row ['Value'] {1} ) && $row ['Value'] {1} == ':' && ! empty ( $row ['Value'] {2} ) && $row ['Value'] {2} == "\\") {
					$this->platform = 'WINDOWS';
				} else {
					$this->platform = 'OTHER';
				}
			} else {
				$this->platform = 'WINDOWS';
			}
			
			if ($this->platform == 'OTHER' && ($dbhost != '.' && strtolower ( $dbhost ) != 'localhost:3306' && $dbhost != '127.0.0.1:3306') || (PHP_VERSION >= '5.1' && date_default_timezone_get () == 'UTC')) {
				$result = mysql_query ( "SELECT UNIX_TIMESTAMP() AS timeline, UNIX_TIMESTAMP('" . date ( 'Y-m-d H:i:s', $this->starttime ) . "') AS timezone", $this->link_id );
				$row = mysql_fetch_assoc ( $result );
				
				if ($dbhost != '.' && strtolower ( $dbhost ) != 'localhost:3306' && $dbhost != '127.0.0.1:3306') {
					$this->timeline = $this->starttime - $row ['timeline'];
				}
				
				if (PHP_VERSION >= '5.1' && date_default_timezone_get () == 'UTC') {
					$this->timezone = $this->starttime - $row ['timezone'];
				}
			}
			
			$content = '<' . "?php\r\n" . '$this->mysql_config_cache_file_time = ' . $this->starttime . ";\r\n" . '$this->timeline = ' . $this->timeline . ";\r\n" . '$this->timezone = ' . $this->timezone . ";\r\n" . '$this->platform = ' . "'" . $this->platform . "';\r\n?" . '>';
			
			@file_put_contents ( $sqlcache_config_file, $content );
		}
		@include ($sqlcache_config_file);
		
		/* 选择数据库 */
		if ($dbname) {
			if (mysql_select_db ( $dbname, $this->link_id ) === false) {
				if (! $quiet) {
					$this->ErrorMsg ( "Can't select MySQL database($dbname)!" );
				}
				
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}
	function select_database($dbname) {
		return mysql_select_db ( $dbname, $this->link_id );
	}
	function query($sql, $type = "SILENT", $pid = -1) {
        $this->nowSql=$sql;
		if (! IS_DEBUG&&!SHOW_DEBUG&&!SHOW_LOG)
			$type = "SILENT";
		
		$query_link = null;
		if ($pid >= 0) {
			if ($this->link_list [$pid] === NULL) {
				$this->connect_pid ( $pid );
			}
			$query_link = $this->link_list [$pid];
		}
		
		if ($query_link === NULL) {
			if ($this->link_id === NULL) {
				$this->connect ( $this->settings ['dbhost'], $this->settings ['dbuser'], $this->settings ['dbpw'], $this->settings ['dbname'], $this->settings ['charset'], $this->settings ['pconnect'] );
				$this->settings = array ();
			}
			$query_link = $this->link_id;
		}
		
		/* 当当前的时间大于类初始化时间的时候，自动执行 ping 这个自动重新连接操作 */
		if (PHP_VERSION >= '4.3' && time () > $this->starttime + 1) {
			mysql_ping ( $query_link );
		}
		
		if (PHP_VERSION >= '5.0.0') {
			$begin_query_time = microtime ( true );
		} else {
			$begin_query_time = microtime ();
		}
		
		if (! ($query = mysql_query ( $sql, $query_link )) && $type != 'SILENT') {
			$message['message'] = 'MySQL Query Error';
			if ($pid)
			$message['message'] = 'MySQL Query Error:' . $pid;
			$message['sql'] = $sql;
			$message['error'] = mysql_error ( $query_link );
			$message['errno'] = mysql_errno ( $query_link );
			$this->error_message[] = $message;
			
			$this->ErrorMsg ($message['message'].":".$message['error']."<br />errno:".$message['errno']."<br />sql:".$message['sql']);
			
			return false;
		}
		if (PHP_VERSION >= '5.0.0') {
			$query_time = microtime ( true ) - $begin_query_time;
		} else {
			list ( $now_usec, $now_sec ) = explode ( ' ', microtime () );
			list ( $start_usec, $start_sec ) = explode ( ' ', $begin_query_time );
			$query_time = ($now_sec - $start_sec) + ($now_usec - $start_usec);
		}
		$this->queryTime += $query_time;
		
		if ($this->queryCount ++ <= 99) {
			$this->queryLog [] = $sql . " " . $query_time;
		}
		
		if (SHOW_LOG) {
			$str = $query_time.":".$sql;
			logger::write ( $str, logger::DEBUG, logger::FILE, "db" );
		}
		// echo
		// $sql."<br/><br/>======================================<br/><br/>";
		return $query;
	}
	function affected_rows() {
		return mysql_affected_rows ( $this->link_id );
	}
	function error() {
		return mysql_error ( $this->link_id );
	}
	function errno() {
		return mysql_errno ( $this->link_id );
	}
	function insert_id() {
		return mysql_insert_id ( $this->link_id );
	}
    function getLastSql(){
        return  $this->nowSql;
    }
	function close() {
		return mysql_close ( $this->link_id );
	}
	function ErrorMsg($message = '', $sql = '') {
		if ($message) {
			echo "<b>error info</b>: $message\n\n<br /><br />";
		} else {
			echo "<b>MySQL server error report:";
		}
		
		exit ();
	}
	
	/**
	 * 检测查询语句中的表是否支持查询缓存
	 *
	 * @param unknown_type $sql
	 *        	true:即时查询 false:缓存查询
	 */
	function is_immediate($sql, $is_immediate) {
		if (! $is_immediate) {
			if (in_array ( APP_INDEX, $GLOBALS ['distribution_cfg'] ['DB_CACHE_APP'] ) && $GLOBALS ['distribution_cfg'] ['CACHE_TYPE'] != "File") {
				return false;
			} else {
				return true;
			}
		} else {
			if (in_array ( APP_INDEX, $GLOBALS ['distribution_cfg'] ['DB_CACHE_APP'] ) && $GLOBALS ['distribution_cfg'] ['CACHE_TYPE'] != "File") {
				preg_match_all ( "/" . DB_PREFIX . "([\S]+)/", $sql, $matches );
				if ($matches) {
					foreach ( $matches [1] as $k => $table ) {
						if (in_array ( $table, $GLOBALS ['distribution_cfg'] ['DB_CACHE_TABLES'] )) {
							return false;
						}
					}
				}
			}
		}
		return true;
	}
	function getReadDbPid($sql) {
		$c = count ( $GLOBALS ['distribution_cfg'] ['DB_DISTRIBUTION'] );
		if ($c == 0 || ! $GLOBALS ['distribution_cfg'] ['ALLOW_DB_DISTRIBUTE'])
			return - 1;
		else {
			preg_match_all ( "/" . DB_PREFIX . "([\S]+)/", $sql, $matches );
			if ($matches) {
				foreach ( $matches [1] as $k => $table ) {
					if (! in_array ( $table, $GLOBALS ['distribution_cfg'] ['DB_CACHE_TABLES'] )) {
						return - 1;
					}
				}
			}
			
			// 通过sql散列
			$pid = hash_table ( $sql, $c );
			return $pid;
		}
	}
	
	/**
	 *获取一条的第一个字段
	 * @param unknown_type $sql        	
	 * @param unknown_type $is_immediate
	 *        	是否为立即查 询，默认为true,则再按缓存配置读取, false时直接按指定方式
	 * @return unknown Ambigous
	 */
	function getOne($sql, $is_immediate = true) {
        $this->nowSql=$sql;
		$immediate = $this->is_immediate ( $sql, $is_immediate );
		$res = false;
		if (! IS_DEBUG && ! $immediate) {
			$GLOBALS ['cache']->set_dir ( APP_ROOT_PATH . $this->cache_data_dir );
			$res = $GLOBALS ['cache']->get ( $sql );
		}
		if ($res !== false) {
			return $res;
		}
		
		$res = $this->query ( $sql, "", $this->getReadDbPid ( $sql ) );
		if ($res !== false) {
			$row = mysql_fetch_row ( $res );
			
			if ($row !== false) {
				if (! IS_DEBUG && ! $immediate) {
					
					$GLOBALS ['cache']->set_dir ( APP_ROOT_PATH . $this->cache_data_dir );
					$GLOBALS ['cache']->set ( $sql, $row [0], $this->max_cache_time );
				}
				return $row [0];
			} else {
				if (! IS_DEBUG && ! $immediate) {
					
					$GLOBALS ['cache']->set_dir ( APP_ROOT_PATH . $this->cache_data_dir );
					$GLOBALS ['cache']->set ( $sql, '', $this->max_cache_time );
				}
				return '';
			}
		} else {
			if (! IS_DEBUG && ! $immediate) {
				
				$GLOBALS ['cache']->set_dir ( APP_ROOT_PATH . $this->cache_data_dir );
				$GLOBALS ['cache']->set ( $sql, '', $this->max_cache_time );
			}
			return false;
		}
	}
	function getAll($sql, $is_immediate = true) {
        $this->nowSql=$sql;
		$immediate = $this->is_immediate ( $sql, $is_immediate );
		
		$res = false;
		if (! IS_DEBUG && ! $immediate) {
			
			$GLOBALS ['cache']->set_dir ( APP_ROOT_PATH . $this->cache_data_dir );
			$res = $GLOBALS ['cache']->get ( $sql );
		}
		if ($res !== false) {
			return $res;
		}
		
		$res = $this->query ( $sql, "", $this->getReadDbPid ( $sql ) );
		if ($res !== false) {
			$arr = array ();
			while ( $row = mysql_fetch_assoc ( $res ) ) {
				$arr [] = $row;
			}
			
			if (! IS_DEBUG && ! $immediate) {
				
				$GLOBALS ['cache']->set_dir ( APP_ROOT_PATH . $this->cache_data_dir );
				$GLOBALS ['cache']->set ( $sql, $arr, $this->max_cache_time );
			}
			return $arr;
		} else {
			if (! IS_DEBUG && ! $immediate) {
				
				$GLOBALS ['cache']->set_dir ( APP_ROOT_PATH . $this->cache_data_dir );
				$GLOBALS ['cache']->set ( $sql, '', $this->max_cache_time );
			}
			return false;
		}
	}

    /**
     * 获取一条数据
     * @param $sql
     * @param bool $is_immediate
     * @return array|bool|resource
     */
    function getRow($sql, $is_immediate = true) {
        $this->nowSql=$sql;
		$immediate = $this->is_immediate ( $sql, $is_immediate );
		$res = false;
		if (! IS_DEBUG && ! $immediate) {
			
			$GLOBALS ['cache']->set_dir ( APP_ROOT_PATH . $this->cache_data_dir );
			$res = $GLOBALS ['cache']->get ( $sql );
		}
		if ($res !== false) {
			return $res;
		}
		
		$res = $this->query ( $sql, "", $this->getReadDbPid ( $sql ) );
		if ($res !== false) {
			$res = mysql_fetch_assoc ( $res );
			if (! IS_DEBUG && ! $immediate) {
				
				$GLOBALS ['cache']->set_dir ( APP_ROOT_PATH . $this->cache_data_dir );
				if ($res)
					$GLOBALS ['cache']->set ( $sql, $res, $this->max_cache_time );
				else
					$GLOBALS ['cache']->set ( $sql, '', $this->max_cache_time );
			}
			return $res;
		} else {
			if (! IS_DEBUG && ! $immediate) {
				$GLOBALS ['cache']->set_dir ( APP_ROOT_PATH . $this->cache_data_dir );
				$GLOBALS ['cache']->set ( $sql, '', $this->max_cache_time );
			}
			return false;
		}
	}
	
	/**
	 * 针对数据的查询缓存返回的当前时间戳，用于查询
	 *
	 * @param unknown_type $time        	
	 */
	function getCacheTime($time) {
		return intval ( $time / $this->max_cache_time ) * $this->max_cache_time;
	}

    /**
     * 获取查询的数据第一个字段
     * @param $sql
     * @return array|bool
     */
    function getCol($sql) {
        $this->nowSql=$sql;
		$res = $this->query ( $sql );
		if ($res !== false) {
			$arr = array ();
			while ( $row = mysql_fetch_row ( $res ) ) {
				$arr [] = $row [0];
			}
			return $arr;
		} else {
			return false;
		}
	}
	function autoExecute($table, $field_values, $mode = 'INSERT', $where = '', $querymode = '') {
		$field_names = $this->getCol ( 'DESC ' . $table );
		
		$sql = '';
		if ($mode == 'INSERT') {
			$fields = $values = array ();
			foreach ( $field_names as $value ) {
				if (@array_key_exists ( $value, $field_values ) == true) {
					$fields [] = $value;
					$field_values [$value] = stripslashes ( $field_values [$value] );
					$values [] = "'" . addslashes ( $field_values [$value] ) . "'";
				}
			}
			
			if (! empty ( $fields )) {
				$sql = 'INSERT INTO ' . $table . ' (' . implode ( ', ', $fields ) . ') VALUES (' . implode ( ', ', $values ) . ')';
			}

		} else {
			$sets = array ();
			foreach ( $field_names as $value ) {
				if (array_key_exists ( $value, $field_values ) == true) {
					$field_values [$value] = stripslashes ( $field_values [$value] );
					$sets [] = $value . " = '" . addslashes ( $field_values [$value] ) . "'";
				}
			}
			
			if (! empty ( $sets )) {
				$sql = 'UPDATE ' . $table . ' SET ' . implode ( ', ', $sets ) . ' WHERE ' . $where;
			}
		}
		$this->nowSql=$sql;
		if ($sql) {
			return $this->query ( $sql, $querymode );
		} else {
			return false;
		}
	}
}

?>