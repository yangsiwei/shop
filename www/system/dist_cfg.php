<?php

return array(
		"CACHE_CLIENT"	=>	"", //备选配置,使用到的有memcached,memcacheSASL,DBCache
		"CACHE_PORT"	=>	"", //备选配置（memcache使用的端口，默认为11211,DB为3306）
		"CACHE_USERNAME"	=>	"",  //备选配置
		"CACHE_PASSWORD"	=>	"",  //备选配置
		"CACHE_DB"	=>	"",  //备选配置,用DB做缓存时的库名
		"CACHE_TABLE"	=>	"",  //备选配置,用DB做缓存时的表名
		
		"SESSION_CLIENT"	=>	"", //备选配置,使用到的有memcached,memcacheSASL,DBCache
		"SESSION_PORT"	=>	"", //备选配置（memcache使用的端口，默认为11211,DB为3306）
		"SESSION_USERNAME"	=>	"",  //备选配置
		"SESSION_PASSWORD"	=>	"",  //备选配置
		"SESSION_DB"	=>	"",  //备选配置,用DB做缓存时的库名
		"SESSION_TABLE"	=>	"",  //备选配置,用DB做缓存时的表名
		"SESSION_FILE_PATH"	=>	"public/session", //session保存路径(为空表示web环境默认路径)
		//"SESSION_FILE_PATH"	=>	"",
		"DB_CACHE_APP"	=>	array(
			"index"
		),	
		"DB_CACHE_TABLES"	=>	array(
				),  //支持查询缓存的表
				
		"DB_DISTRIBUTION" => array(
				// 			array(
				// 				'DB_HOST'=>'localhost',
				// 				'DB_PORT'=>'3306',
				// 				'DB_NAME'=>'o2onew1',
				// 				'DB_USER'=>'root',
				// 				'DB_PWD'=>'',
				// 			),
				// 			array(
				// 				'DB_HOST'=>'localhost',
				// 				'DB_PORT'=>'3306',
				// 				'DB_NAME'=>'o2onew2',
				// 				'DB_USER'=>'root',
				// 				'DB_PWD'=>'',
				// 			),
		), //数据只读查询的分布
		
		"OSS_DOMAIN"	=>	"",  //远程存储域名
		"OSS_FILE_DOMAIN"	=>	"",	//远程存储文件域名(主要指脚本与样式)
		"OSS_BUCKET_NAME"	=>	"", //针对阿里oss的bucket_name
		"OSS_ACCESS_ID"	=>	"",
		"OSS_ACCESS_KEY"	=>	"",
		
		"CACHE_TYPE" => "File",
		"CACHE_LOG" => false,
		"SESSION_TYPE" => "File",
		"ALLOW_DB_DISTRIBUTE" => false,
		"CSS_JS_OSS" => false,
		"OSS_TYPE" => "",
		"DOMAIN_ROOT" => "",
		"COOKIE_PATH" => "/"
		
);


?>