<?php
// +----------------------------------------------------------------------
// | Fanwe 方维商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------

// +----------------------------------------------------------------------

class AppTemplate
{
    var $template_dir   = '';
    var $cache_dir      = '';
    var $compile_dir    = '';
    var $cache_lifetime = 3600; // 缓存更新时间, 默认 3600 秒
    var $direct_output  = false;
    var $caching        = false;
    var $template       = array();
    var $force_compile  = false;

    var $_var           = array();
    var $_hash        = '554fcae493e564ee0dc75bdf2ebf94ca';
    var $_foreach       = array();
    var $_current_file  = '';
    var $_expires       = 0;
    var $_errorlevel    = 0;
    var $_nowtime       = null;
    var $_checkfile     = true;
    var $_foreachmark   = '';
    var $_seterror      = 0;

    var $_temp_key      = array();  // 临时存放 foreach 里 key 的数组
    var $_temp_val      = array();  // 临时存放 foreach 里 item 的数组
    
    var $template_dir_sub = "";  //子模板目录
    var $template_dir_channel = ""; //行业模板目录

    function __construct()
    {
        $this->APP_TMPL_PATH();
    }

    function APP_TMPL_PATH()
    {
    	if(IS_DEBUG)
    	{
        	$this->_errorlevel = E_ALL^E_NOTICE^E_WARNING;
        	if(PHP_VERSION>='5.5.0')
        	{
        		$this->_errorlevel = E_ALL^E_DEPRECATED^E_STRICT^E_NOTICE^E_WARNING;
        	}
    	}
        else 
        $this->_errorlevel = 0;
        $this->_nowtime    = time();
        header('Content-type: text/html; charset=utf-8');
    }

    /**
     * 注册变量
     *
     * @access  public
     * @param   mix      $tpl_var
     * @param   mix      $value
     *
     * @return  void
     */
    function assign($tpl_var, $value = '')
    {
        if (is_array($tpl_var))
        {
            foreach ($tpl_var AS $key => $val)
            {
                if ($key != '')
                {
                    $this->_var[$key] = $val;
                }
            }
        }
        else
        {
            if ($tpl_var != '')
            {
                $this->_var[$tpl_var] = $value;
            }
        }
    }

    /**
     * 处理模板文件
     *
     * @access  public
     * @param   string      $filename
     * @param   sting      $cache_id
     *
     * @return  sring
     */
    function fetch($filename, $cache_id = '')
    {
    	if($cache_id)$cache_id.=SITE_DOMAIN;
    	$relate_filename = $filename;
        if (!$this->_seterror)
        {
            error_reporting($this->_errorlevel);
        }
        $this->_seterror++;

        if (strncmp($filename,'str:', 4) == 0)
        {
            $out = $this->_eval($this->fetch_str(substr($filename, 4)));
            $out = $this->es_tmpl($out);
        }
        else
        {
        	
        	if($this->template_dir_channel)
        	{
        		$sub_filename = $this->template_dir . '/channel_' .$this->template_dir_channel."/".$filename;
        		if(file_exists($sub_filename))
        		{
        			$filename = "channel_".$this->template_dir_channel."/".$filename;
        		}
        	}
        	
        	if($this->template_dir_sub)
        	{
        		$sub_filename = $this->template_dir . '/' .$this->template_dir_sub."/".$filename;
        		if(file_exists($sub_filename))
        		{
        			$filename = $this->template_dir_sub."/".$filename;
           		}        		
        	}
        	
            if ($this->_checkfile)
            {
                if (!file_exists($filename))
                {
                    $filename = $this->template_dir . '/' . $filename;
                }
            }
            else
            {
                $filename = $this->template_dir . '/' . $filename;
            }

            if ($this->direct_output)
            {
                $this->_current_file = $filename;
                $out = $this->_eval($this->fetch_str(file_get_contents($filename)));
                $out = $this->es_tmpl($out);
            }
            else
            {            	
                if (app_conf("TMPL_CACHE_ON")==1&&$cache_id && $this->caching&&!IS_DEBUG)
                {                	
                    $out = $this->template_out;                                                     
                }
                else
                {
                    if (!in_array($filename, $this->template))
                    {
                        $this->template[] = $filename;
                    }

                    $out = $this->make_compiled($filename,$relate_filename);                    
                    $out = $this->es_tmpl($out);
                    if (app_conf("TMPL_CACHE_ON")==1&&$cache_id)
                    {
                        $cachename = basename($filename, strrchr($filename, '.')) . '_' . $cache_id;
                       
                        $data = serialize(array('template' => $this->template, 'expires' => $this->_nowtime + $this->cache_lifetime, 'maketime' => $this->_nowtime));
                        $out = str_replace("\r", '', $out);

                        while (strpos($out, "\n\n") !== false)
                        {
                            $out = str_replace("\n\n", "\n", $out);
                        }

                        $hash_dir = $this->cache_dir . '/c' . substr(md5($cachename), 0, 1);
                        //$hash_dir = $this->cache_dir;
                        /* by hc 迁移缓存
                        if (!is_dir($hash_dir))
                        {
                            mkdir($hash_dir);
                            @chmod($hash_dir, 0777);
                        }
                        if (file_put_contents($hash_dir . '/' . $cachename . '.php', '<?php exit;?>' . $data . $out, LOCK_EX) === false)
                        {
                            trigger_error('can\'t write:' . $hash_dir . '/' . $cachename . '.php');
                        }*/
                        $cache_key = $hash_dir . '/' . $cachename . '.php';
                        $cache_data = '<?php exit;?>' . $data . $out;
                        $GLOBALS['cache']->set_dir($this->cache_dir."/");
                        $GLOBALS['cache']->set($cache_key,$cache_data,$this->cache_lifetime);
                        $this->template = array();
                    }
                }
                
            }
            
           
		        
        }

        $this->_seterror--;
        if (!$this->_seterror)
        {
            error_reporting($this->_errorlevel);
        }

        
        
        //end
        
        return $out; // 返回html数据
    }

    function es_tmpl($out)
    {
    		if(!$GLOBALS['no_lazy'])
    		{
	    		//对延时加载的修正 .lazy
	    		preg_match_all("/<img.*?lazy=[\"|\']\S*[\"\'][^\>]*>/",$out,$lazy_array);
	    		foreach($lazy_array[0] as $lazy_item)
	    		{
	  				$lazy_item_replace = str_replace(" src="," data-src=",$lazy_item);
	  				$out = str_replace($lazy_item,$lazy_item_replace,$out);
	    		}
    		}
    	
    	 	//对图片路径的修复
    		if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']!="NONE")
			{
				$domain = $GLOBALS['distribution_cfg']['OSS_DOMAIN'];
			}
			else
			{
				$domain = SITE_DOMAIN.APP_ROOT;
			}
	        $out = str_replace(APP_ROOT."./public/",$domain."/public/",$out);	
	        $out = str_replace("./public/",$domain."/public/",$out);	

	        //修复url的路径
	        $out = str_replace("./",SITE_DOMAIN.APP_ROOT."/",$out);	
	        
	        return $out; 
    }
    /**
     * 显示页面函数
     *
     * @access  public
     * @param   string      $filename
     * @param   sting      $cache_id
     *
     * @return  void
     */
    function display($filename, $cache_id = '')
    {
        $this->_seterror++;
        error_reporting($this->_errorlevel);

        $this->_checkfile = false;
        $out = $this->fetch($filename, $cache_id);
        if (strpos($out, $this->_hash) !== false)
        {
            $k = explode($this->_hash, $out);
            foreach ($k AS $key => $val)
            {
                if (($key % 2) == 1)
                {
                    $k[$key] = $this->insert_mod($val);
                }
            }
            $out = implode('', $k);
        }
        error_reporting($this->_errorlevel);
        $this->_seterror--;      
        
        gzip_out($out.run_info());
        
        if($GLOBALS['distribution_cfg']['OSS_TYPE']&&$GLOBALS['distribution_cfg']['OSS_TYPE']=="ES_FILE")
		{
			if(count($GLOBALS['curl_param']['images'])>0)
			{
				$GLOBALS['curl_param']['images'] =  base64_encode(serialize($GLOBALS['curl_param']['images']));
				curl_setopt($GLOBALS['syn_image_ci'], CURLOPT_POSTFIELDS, $GLOBALS['curl_param']);
				$rss = curl_exec($GLOBALS['syn_image_ci']);
			}
			curl_close($GLOBALS['syn_image_ci']);
			//echo $rss;exit;
		}

    }
    
    
    /**
     * 编译模板函数
     *
     * @access  public
     * @param   string      $filename
     *
     * @return  sring        编译后文件地址
     */
	function make_compiled($filename,$relate_filename="")
    {
    	if($relate_filename!="")
    	{
    		$dir_arr = explode("/", $relate_filename);
    		$prefix = "";
    		if(count($dir_arr)>1)
    		{
    			foreach($dir_arr as $k=>$v)
    			{
    				if($k<count($dir_arr)-1)
    				{
    					$prefix.=$v."_";
    				}
    			}
    		}
    		$name = $this->compile_dir . '/'.$prefix . basename($filename) . '.php';
    	}
    	else
        $name = $this->compile_dir . '/' . basename($filename) . '.php';
		if(!IS_DEBUG)
    	$source = @$this->_require($name);
		
		
        if (empty($source))
        {
            $this->_current_file = $filename;
            $source = $this->fetch_str(@file_get_contents($filename));
			@file_put_contents($name, $source, LOCK_EX);

            $source = $this->_eval($source);
        }

        return $source;
    }

    /**
     * 处理字符串函数
     *
     * @access  public
     * @param   string     $source
     *
     * @return  sring
     */
    function fetch_str($source)
    {
        return preg_replace("/{([^\}\{\n]*)}/e", "\$this->select('\\1');", $source);
    }

    
    public function clear_cache($filename,$cached_id)
    {
    	 $cachename = basename($filename, strrchr($filename, '.')) . '_' . $cached_id;
    	 $hash_dir = $this->cache_dir . '/c' . substr(md5($cachename), 0, 1);
//     	 @unlink($hash_dir . '/' . $cachename . '.php');
    	 
    	 $cache_key = $hash_dir . '/' . $cachename . '.php';
    	 $GLOBALS['cache']->set_dir($this->cache_dir."/");
    	 $data = $GLOBALS['cache']->rm($cache_key);
    	
    }
    
    
    public function clear()
    {
    	$GLOBALS['cache']->set_dir($this->cache_dir."/");
    	$GLOBALS['cache']->clear();
    }
    /**
     * 判断是否缓存
     *
     * @access  public
     * @param   string     $filename
     * @param   sting      $cache_id
     *
     * @return  bool
     */
    function is_cached($filename, $cache_id = '')
    {
    	if($cache_id)$cache_id.=SITE_DOMAIN;
        $cachename = basename($filename, strrchr($filename, '.')) . '_' . $cache_id;
        if (app_conf("TMPL_CACHE_ON")==1&&$this->caching == true && $this->direct_output == false&&!IS_DEBUG)
        {
            $hash_dir = $this->cache_dir . '/c' . substr(md5($cachename), 0, 1);
            //$hash_dir = $this->cache_dir;
        	//if ($data = @file_get_contents($hash_dir . '/' . $cachename . '.php')) by hc 迁移缓存
        	$cache_key = $hash_dir . '/' . $cachename . '.php';
        	$GLOBALS['cache']->set_dir($this->cache_dir."/");
        	$data = $GLOBALS['cache']->get($cache_key);
        	if($data)
            {
                $data = substr($data, 13);
                $pos  = strpos($data, '<');
                $paradata = substr($data, 0, $pos);
                $para     = @unserialize($paradata);
                if ($para === false || $this->_nowtime > $para['expires'])
                {
                    $this->caching = false;

                    return false;
                }
                $this->_expires = $para['expires'];

                $this->template_out = substr($data, $pos);
                foreach ($para['template'] AS $val)
                {
                    $stat = @stat($val);
                    if ($para['maketime'] < $stat['mtime'])
                    {
                        $this->caching = false;

                        return false;
                    }
                }
            }
            else
            {
                $this->caching = false;

                return false;
            }

            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 处理{}标签
     *
     * @access  public
     * @param   string      $tag
     *
     * @return  sring
     */
    function select($tag)
    {
        $tag = stripslashes(trim($tag));

        if (empty($tag))
        {
            return '{}';
        }
        elseif ($tag{0} == '*' && substr($tag, -1) == '*') // 注释部分
        {
            return '';
        }
        elseif ($tag{0} == '$') // 变量
        {
            return '<?php echo ' . $this->get_val(substr($tag, 1)) . '; ?>';
        }
        elseif ($tag{0} == '/') // 结束 tag
        {
            switch (substr($tag, 1))
            {
                case 'if':
                    return '<?php endif; ?>';
                    break;

                case 'foreach':
                    if ($this->_foreachmark == 'foreachelse')
                    {
                        $output = '<?php endif; unset($_from); ?>';
                    }
                    else
                    {
                        array_pop($this->_patchstack);
                        $output = '<?php endforeach; endif; unset($_from); ?>';
                    }
                    $output .= "<?php \$this->pop_vars();; ?>";

                    return $output;
                    break;

                case 'literal':
                    return '';
                    break;

                default:
                    return '{'. $tag .'}';
                    break;
            }
        }
        else
        {
            $tag_sel = array_shift(explode(' ', $tag));
            switch ($tag_sel)
            {
                case 'if':

                    return $this->_compile_if_tag(substr($tag, 3));
                    break;

                case 'else':

                    return '<?php else: ?>';
                    break;

                case 'elseif':

                    return $this->_compile_if_tag(substr($tag, 7), true);
                    break;

                case 'foreachelse':
                    $this->_foreachmark = 'foreachelse';

                    return '<?php endforeach; else: ?>';
                    break;

                case 'foreach':
                    $this->_foreachmark = 'foreach';
                    if(!isset($this->_patchstack))
                    {
                        $this->_patchstack = array();
                    }
                    return $this->_compile_foreach_start(substr($tag, 8));
                    break;

                case 'assign':
                    $t = $this->get_para(substr($tag, 7),0);

                    if ($t['value']{0} == '$')
                    {
                        /* 如果传进来的值是变量，就不用用引号 */
                        $tmp = '$this->assign(\'' . $t['var'] . '\',' . $t['value'] . ');';
                    }
                    else
                    {
                        $tmp = '$this->assign(\'' . $t['var'] . '\',\'' . addcslashes($t['value'], "'") . '\');';
                    }
                    // $tmp = $this->assign($t['var'], $t['value']);

                    return '<?php ' . $tmp . ' ?>';
                    break;

                case 'include':
                    $t = $this->get_para(substr($tag, 8), 0);    

                    if (is_file($GLOBALS['tmpl']->template_dir_sub."/".$t[file]))
                           $t[file] =  $GLOBALS['tmpl']->template_dir_sub."/".$t[file];
                    
                    
            		if(substr($t[file],-4,4)!='html')
                    {
                    	$code =  var_export($this->_var[$t['inc_var']],1);  
	                   	if($t['inc_var']) 
	                   	{
		                   	 return '<?php $this->assign(\'inc_var\','.$code.');echo $this->fetch(' . "$t[file]" . '); ?>';
	                   	}
						else
						{
							return '<?php echo $this->fetch(' . "$t[file]" . '); ?>';
						}
						
                    }
                    else
                    {
                    	$code =  var_export($this->_var[$t['inc_var']],1);  
	                   	if($t['inc_var']) 
	                    return '<?php $this->assign(\'inc_var\','.$code.');echo $this->fetch(' . "'$t[file]'" . '); ?>';
						else
						return '<?php echo $this->fetch(' . "'$t[file]'" . '); ?>';
                    }
                    
                    
					
                    break;

                case 'insert_scripts':
                    $t = $this->get_para(substr($tag, 15), 0);

                    return '<?php echo $this->smarty_insert_scripts(' . $this->make_array($t) . '); ?>';
                    break;

                case 'create_pages':
                    $t = $this->get_para(substr($tag, 13), 0);

                    return '<?php echo $this->smarty_create_pages(' . $this->make_array($t) . '); ?>';
                    break;
                case 'insert' :
                    $t = $this->get_para(substr($tag, 7), false);					
                    $out = "<?php \n" . '$k = ' . preg_replace("/(\'\\$[^,]+)/e" , "stripslashes(trim('\\1','\''));", var_export($t, true)) . ";\n";
                    $out .= 'echo $this->_hash . $k[\'name\'] . \'|\' . base64_encode(serialize($k)) . $this->_hash;' . "\n?>";

                    return $out;
                    break;

                case 'literal':
                    return '';
                    break;

                case 'cycle' :
                    $t = $this->get_para(substr($tag, 6), 0);

                    return '<?php echo $this->cycle(' . $this->make_array($t) . '); ?>';
                    break;

                case 'html_options':
                    $t = $this->get_para(substr($tag, 13), 0);

                    return '<?php echo $this->html_options(' . $this->make_array($t) . '); ?>';
                    break;

                case 'html_select_date':
                    $t = $this->get_para(substr($tag, 17), 0);

                    return '<?php echo $this->html_select_date(' . $this->make_array($t) . '); ?>';
                    break;

                case 'html_radios':
                    $t = $this->get_para(substr($tag, 12), 0);

                    return '<?php echo $this->html_radios(' . $this->make_array($t) . '); ?>';
                    break;

                case 'html_select_time':
                    $t = $this->get_para(substr($tag, 12), 0);

                    return '<?php echo $this->html_select_time(' . $this->make_array($t) . '); ?>';
                    break;
					
				case 'function' :
                    $t = $this->get_para(substr($tag, 8), false);

                    $out = "<?php \n" . '$k = ' . preg_replace("/(\'\\$[^,]+)/e" , "stripslashes(trim('\\1','\''));", var_export($t, true)) . ";\n";
					$out .= 'echo $k[\'name\'](';
					
					$first = true;
					
					foreach($t as $n => $v)
					{
						if($n != "name")
						{
							if($first)
							{
								$out .='$k[\''.$n.'\']';
								$first = false;	
							}
							else
							{
								$out .=',$k[\''.$n.'\']';
							}
						}	
					}
                    $out .= ');' . "\n?>";

                    return $out;
                    break;
                    
                case 'url' :
    				$reg_text = "/\"([^\"]+)\"/";
    				preg_match_all($reg_text,$tag,$matches);
    				
    				if(count($matches[0])>0)
    				{
    					//url格式正确
    					$param_str = "\"\"";
    					if(isset($matches[0][2])&&$matches[0][2]!='')
    					{
    						//有额外传参
    						//return print_r($matches[0][2],true);
    						preg_match_all("/[$]([^\"&]+)/",$matches[0][2],$param_matches);
    						$replacement = array();
    						$finder = array();
    						if(count($param_matches[0])>0)
    						{
    							foreach($param_matches[0] as $m_item)
    							{
    								$finder[] = $m_item;
    							}    							
    							//有参数
    							foreach($param_matches[1] as $p_item)
    							{
    								$p_item_arr = explode(".",$p_item);
    								$var_str = '".$this->_var';
    								foreach($p_item_arr as $var_item)
    								{
    									$var_str = $var_str."['".$var_item."']";
    								}
    								$var_str.='."';
    								$replacement[] = $var_str;
    							}
    						}    						
    						$param_str =  str_replace($finder,$replacement,$matches[0][2]);    						
    					}
    					
    					$app_index = $matches[1][0];
    					$route = $matches[1][1];
    					if(empty($route))
    					$route = "index";
    					
    					
    					$code =  "<?php\r\n";
    					$code.= "echo parse_url_tag(\"";
    					$code.="u:";
    					$code.= $app_index."|".$route."|\"."; 
    					$code.=$param_str.".";
    					$code.="\"\"); \r\n";
    					$code.="?>";
    					
    					return $code;
    				}
    				else
    				{
    					 return '{' . $tag . '}';
    				}
    				
                    break;
                 case 'lang' :
                    	$reg_text = "/\"([^\"]+)\"/";
                    	preg_match_all($reg_text,$tag,$matches);
                    	if(count($matches[0])>0)
                    	{
                    			
                    		$param = "";
                    		if(isset($matches[0][1])&&$matches[0][1]!='')
                    		{
                    			//有额外参数
                    			for($kk=1;$kk<count($matches[0]);$kk++)
                    			{
                    			preg_match_all("/[$]([^\"]+)/",$matches[0][$kk],$param_matches);
                    			if(count($param_matches[0])>0)
                    			{
                    			//有参数
                    			$p_item_arr = explode(".",$param_matches[1][0]);
                    				$var_str = ',$this->_var';
                    				foreach($p_item_arr as $var_item)
                    				{
                    				$var_str = $var_str."['".$var_item."']";
                    			}
                    				$param.=$var_str;
                    			}
                    				else
                    					$param.= ",".$matches[0][$kk];
                    			}
                    			}
                    				
                    
                    			//$app_index = $matches[1][0];
                    			$lang_key = $matches[1][0];
                    				
                    				
                    			$code =  "<?php\r\n";
                    			$code.= "echo lang(\"";
                    			$code.= $lang_key;
                    			$code.="\"".$param."); \r\n";
                    			$code.="?>";
                    
                    			return $code;
                    	}
                    	else
                    	{
                    	return '{' . $tag . '}';
                    	}
                    
                    	break;
                 case "editor":
                     $reg_text = "/\"([^\"]+)\"/";
                     preg_match_all($reg_text,$tag,$matches);
                     if(count($matches[0])>0)
                     {
                          
                         $param = "";
                         if(isset($matches[0][1])&&$matches[0][1]!='')
                         {
                             //有额外参数
                             for($kk=1;$kk<count($matches[0]);$kk++)
                             {
                                 preg_match_all("/[$]([^\"]+)/",$matches[0][$kk],$param_matches);
                                 if(count($param_matches[0])>0)
                                 {
                                 //有参数
                                 $p_item_arr = explode(".",$param_matches[1][0]);
                                     $var_str = ',$this->_var';
                                     foreach($p_item_arr as $var_item)
                                     {
                                     $var_str = $var_str."['".$var_item."']";
                                 }
                                 $param.=$var_str;
                                 }
                                 else
                                     $param.= ",".$matches[0][$kk];
                             }
                          }
                                 $editor_id = $matches[1][0];
                                 $editor_w = $matches[1][1];
                                 $editor_h = $matches[1][2];
                                 $editor_cnt = $matches[1][3];
                                 
                                 $code =  "<?php\r\n";
                                 $code.= "echo create_kindeditor(\"";
                                 $code.= $editor_id."\",";
                                 $code.= "\"".$editor_w."\",";
                                 $code.= "\"".$editor_h."\",";
                                 $code.= "\"".$editor_cnt."\"";
                                 $code.="); \r\n";
                                 $code.="?>";
                                 return $code;
                     }
                     else
                         {
                         return '{' . $tag . '}';
                     }
                     
                     break;
                 case 'adv' :
                     	$reg_text = "/\"([^\"]*)\"/";
                     	preg_match_all($reg_text,$tag,$matches);
                     	if(count($matches[0])>0)
                     	{
                     		 
                     		$param = "";
                     		
                     		$adv_group = $matches[1][0];
                     		$adv_content = $matches[1][1];
                     		$adv_width = intval($matches[1][2]);
                     		$adv_height = intval($matches[1][3]);
                     
                     
                     		$code =  '<?php ';
                     		$code.= 'echo show_adv("';
                     		$code.= $adv_group;
                     		$code.='","'.$adv_content.'","'.$adv_width.'","'.$adv_height.'"); ';
                     		$code.=' ?>';
                     
                     		return $code;
                     	}
                     	else
                     	{
                     		return '{' . $tag . '}';
                     	}
                     
                     	break;
                default:
                    return '{' . $tag . '}';
                    break;
            }
        }
    }

    /**
     * 处理smarty标签中的变量标签
     *
     * @access  public
     * @param   string     $val
     *
     * @return  bool
     */
    function get_val($val)
    {
        if (strrpos($val, '[') !== false)
        {
            $val = preg_replace("/\[([^\[\]]*)\]/eis", "'.'.str_replace('$','\$','\\1')", $val);
        }

        if (strrpos($val, '|') !== false)
        {
            $moddb = explode('|', $val);
            $val = array_shift($moddb);
        }

        if (empty($val))
        {
            return '';
        }

        if (strpos($val, '.$') !== false)
        {
            $all = explode('.$', $val);

            foreach ($all AS $key => $val)
            {
                $all[$key] = $key == 0 ? $this->make_var($val) : '['. $this->make_var($val) . ']';
            }
            $p = implode('', $all);
        }
        else
        {
            $p = $this->make_var($val);
        }

        if (!empty($moddb))
        {
            foreach ($moddb AS $key => $mod)
            {
                $s = explode(':', $mod);
                switch ($s[0])
                {
                    case 'escape':
                        $s[1] = trim($s[1], '"');
                        if ($s[1] == 'html')
                        {
                            $p = 'htmlspecialchars(' . $p . ')';
                        }
                        elseif ($s[1] == 'url')
                        {
                            $p = 'urlencode(' . $p . ')';
                        }
                        elseif ($s[1] == 'decode_url')
                        {
                            $p = 'urldecode(' . $p . ')';
                        }
                        elseif ($s[1] == 'quotes')
                        {
                            $p = 'addslashes(' . $p . ')';
                        }
                        elseif ($s[1] == 'u8_url')
                        {
                            if (EC_CHARSET != 'utf-8')
                            {
                                $p = 'urlencode(ecs_iconv("' . EC_CHARSET . '", "utf-8",' . $p . '))';
                            }
                            else
                            {
                                $p = 'urlencode(' . $p . ')';
                            }
                        }
                        else
                        {
                            $p = 'htmlspecialchars(' . $p . ')';
                        }
                        break;

                    case 'nl2br':
                        $p = 'nl2br(' . $p . ')';
                        break;

                    case 'default':
                        $s[1] = $s[1]{0} == '$' ?  $this->get_val(substr($s[1], 1)) : "'$s[1]'";
                        $p = 'empty(' . $p . ') ? ' . $s[1] . ' : ' . $p;
                        break;

                    case 'truncate':
                        $p = 'sub_str(' . $p . ",$s[1])";
                        break;

                    case 'strip_tags':
                        $p = 'strip_tags(' . $p . ')';
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        return $p;
    }

    /**
     * 处理去掉$的字符串
     *
     * @access  public
     * @param   string     $val
     *
     * @return  bool
     */
    function make_var($val)
    {
        if (strrpos($val, '.') === false)
        {
            if (isset($this->_var[$val]) && isset($this->_patchstack[$val]))
            {
                $val = $this->_patchstack[$val];
            }
            $p = '$this->_var[\'' . $val . '\']';
        }
        else
        {
            $t = explode('.', $val);
            $_var_name = array_shift($t);
            if (isset($this->_var[$_var_name]) && isset($this->_patchstack[$_var_name]))
            {
                $_var_name = $this->_patchstack[$_var_name];
            }
            if ($_var_name == 'smarty')
            {
                 $p = $this->_compile_smarty_ref($t);
            }
            else
            {
                $p = '$this->_var[\'' . $_var_name . '\']';
            }
            foreach ($t AS $val)
            {
                $p.= '[\'' . $val . '\']';
            }
        }

        return $p;
    }

    /**
     * 处理insert外部函数/需要include运行的函数的调用数据
     *
     * @access  public
     * @param   string     $val
     * @param   int         $type
     *
     * @return  array
     */
    function get_para($val, $type = 1) // 处理insert外部函数/需要include运行的函数的调用数据
    {
        $pa = $this->str_trim($val);
        foreach ($pa AS $value)
        {
            if (strrpos($value, '='))
            {
                list($a, $b) = explode('=', str_replace(array(' ', '"', "'", '&quot;'), '', $value));
				
                if ($b{0} == '$')
                {
                    if ($type)
                    {
                        eval('$para[\'' . $a . '\']=' . $this->get_val(substr($b, 1)) . ';');
                    }
                    else
                    {
                        $para[$a] = $this->get_val(substr($b, 1));
                    }
                }
                else
                {
                    $para[$a] = $b;
                }
            }
        }

        return $para;
    }

    /**
     * 判断变量是否被注册并返回值
     *
     * @access  public
     * @param   string     $name
     *
     * @return  mix
     */
    function &get_template_vars($name = null)
    {
        if (empty($name))
        {
            return $this->_var;
        }
        elseif (!empty($this->_var[$name]))
        {
            return $this->_var[$name];
        }
        else
        {
            $_tmp = null;

            return $_tmp;
        }
    }

    /**
     * 处理if标签
     *
     * @access  public
     * @param   string     $tag_args
     * @param   bool       $elseif
     *
     * @return  string
     */
    function _compile_if_tag($tag_args, $elseif = false)
    {
        preg_match_all('/\-?\d+[\.\d]+|\'[^\'|\s]*\'|"[^"|\s]*"|[\$\w\.]+|!==|===|==|!=|<>|<<|>>|<=|>=|&&|\|\||\(|\)|,|\!|\^|=|&|<|>|~|\||\%|\+|\-|\/|\*|\@|\S/', $tag_args, $match);

        $tokens = $match[0];
        // make sure we have balanced parenthesis
        $token_count = array_count_values($tokens);
        if (!empty($token_count['(']) && $token_count['('] != $token_count[')'])
        {
            // $this->_syntax_error('unbalanced parenthesis in if statement', E_USER_ERROR, __FILE__, __LINE__);
        }

        for ($i = 0, $count = count($tokens); $i < $count; $i++)
        {
            $token = &$tokens[$i];
            switch (strtolower($token))
            {
                case 'eq':
                    $token = '==';
                    break;

                case 'ne':
                case 'neq':
                    $token = '!=';
                    break;

                case 'lt':
                    $token = '<';
                    break;

                case 'le':
                case 'lte':
                    $token = '<=';
                    break;

                case 'gt':
                    $token = '>';
                    break;

                case 'ge':
                case 'gte':
                    $token = '>=';
                    break;

                case 'and':
                    $token = '&&';
                    break;

                case 'or':
                    $token = '||';
                    break;

                case 'not':
                    $token = '!';
                    break;

                case 'mod':
                    $token = '%';
                    break;

                default:
                    if ($token[0] == '$')
                    {
                        $token = $this->get_val(substr($token, 1));
                    }
                    break;
            }
        }

        if ($elseif)
        {
            return '<?php elseif (' . implode(' ', $tokens) . '): ?>';
        }
        else
        {
            return '<?php if (' . implode(' ', $tokens) . '): ?>';
        }
    }

    /**
     * 处理foreach标签
     *
     * @access  public
     * @param   string     $tag_args
     *
     * @return  string
     */
    function _compile_foreach_start($tag_args)
    {
        $attrs = $this->get_para($tag_args, 0);
        $arg_list = array();
        $from = $attrs['from'];
        if(!IS_DEBUG&&isset($this->_var[$attrs['item']]) && !isset($this->_patchstack[$attrs['item']]))
        {
            $this->_patchstack[$attrs['item']] = $attrs['item'] . '_' . str_replace(array(' ', '.'), '_', microtime());
            $attrs['item'] = $this->_patchstack[$attrs['item']];
        }
        else
        {
            $this->_patchstack[$attrs['item']] = $attrs['item'];
        }
        $item = $this->get_val($attrs['item']);

        if (!empty($attrs['key']))
        {
            $key = $attrs['key'];
            $key_part = $this->get_val($key).' => ';
        }
        else
        {
            $key = null;
            $key_part = '';
        }

        if (!empty($attrs['name']))
        {
            $name = $attrs['name'];
        }
        else
        {
            $name = null;
        }

        $output = '<?php ';
        $output .= "\$_from = $from; if (!is_array(\$_from) && !is_object(\$_from)) { settype(\$_from, 'array'); }; \$this->push_vars('$attrs[key]', '$attrs[item]');";

        if (!empty($name))
        {
            $foreach_props = "\$this->_foreach['$name']";
            $output .= "{$foreach_props} = array('total' => count(\$_from), 'iteration' => 0);\n";
            $output .= "if ({$foreach_props}['total'] > 0):\n";
            $output .= "    foreach (\$_from AS $key_part$item):\n";
            $output .= "        {$foreach_props}['iteration']++;\n";
        }
        else
        {
            $output .= "if (count(\$_from)):\n";
            $output .= "    foreach (\$_from AS $key_part$item):\n";
        }
        return $output . '?>';
    }

    /**
     * 将 foreach 的 key, item 放入临时数组
     *
     * @param  mixed    $key
     * @param  mixed    $val
     *
     * @return  void
     */
    function push_vars($key, $val)
    {
        if (!empty($key))
        {
            array_push($this->_temp_key, "\$this->_vars['$key']='" .$this->_vars[$key] . "';");
        }
        if (!empty($val))
        {
            array_push($this->_temp_val, "\$this->_vars['$val']='" .$this->_vars[$val] ."';");
        }
    }

    /**
     * 弹出临时数组的最后一个
     *
     * @return  void
     */
    function pop_vars()
    {
        $key = array_pop($this->_temp_key);
        $val = array_pop($this->_temp_val);

        if (!empty($key))
        {
            eval($key);
        }
    }

    /**
     * 处理smarty开头的预定义变量
     *
     * @access  public
     * @param   array   $indexes
     *
     * @return  string
     */
    function _compile_smarty_ref(&$indexes)
    {
        /* Extract the reference name. */
        $_ref = $indexes[0];

        switch ($_ref)
        {
            case 'now':
                $compiled_ref = 'time()';
                break;

            case 'foreach':
                array_shift($indexes);
                $_var = $indexes[0];
                $_propname = $indexes[1];
                switch ($_propname)
                {
                    case 'index':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach['$_var']['iteration'] - 1)";
                        break;

                    case 'first':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach['$_var']['iteration'] <= 1)";
                        break;

                    case 'last':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach['$_var']['iteration'] == \$this->_foreach['$_var']['total'])";
                        break;

                    case 'show':
                        array_shift($indexes);
                        $compiled_ref = "(\$this->_foreach['$_var']['total'] > 0)";
                        break;

                    default:
                        $compiled_ref = "\$this->_foreach['$_var']";
                        break;
                }
                break;

            case 'get':
                $compiled_ref = '$_GET';
                break;

            case 'post':
                $compiled_ref = '$_POST';
                break;

            case 'cookies':
                $compiled_ref = '$_COOKIE';
                break;

            case 'env':
                $compiled_ref = '$_ENV';
                break;

            case 'server':
                $compiled_ref = '$_SERVER';
                break;

            case 'request':
                $compiled_ref = '$_REQUEST';
                break;

            case 'session':
                $compiled_ref = '$_SESSION';
                break;

            default:
                // $this->_syntax_error('$smarty.' . $_ref . ' is an unknown reference', E_USER_ERROR, __FILE__, __LINE__);
                break;
        }
        array_shift($indexes);

        return $compiled_ref;
    }

    function smarty_insert_scripts($args)
    {
        static $scripts = array();

        $arr = explode(',', str_replace(' ', '', $args['files']));

        $str = '';
        foreach ($arr AS $val)
        {
            if (in_array($val, $scripts) == false)
            {
                $scripts[] = $val;
                if ($val{0} == '.')
                {
                    $str .= '<script type="text/javascript" src="' . $val . '"></script>';
                }
                else
                {
                    $str .= '<script type="text/javascript" src="js/' . $val . '"></script>';
                }
            }
        }

        return $str;
    }

   
    function insert_mod($name) // 处理动态内容
    {
        list($fun, $para) = explode('|', $name);
        $para = unserialize(base64_decode($para));
        $insert_func = 'insert_' . $fun;	
        require_once APP_ROOT_PATH."app/Lib/insert_libs.php";	
        if(!function_exists($insert_func))//也可调用module中的静态方法
        {
       		$module = MODULE_NAME."Module";
        	return call_user_func(array($module,$fun),$para);  
        }
        else
	    return $insert_func($para);
    }

    function str_trim($str)
    {
        /* 处理'a=b c=d k = f '类字符串，返回数组 */
        while (strpos($str, '= ') != 0)
        {
            $str = str_replace('= ', '=', $str);
        }
        while (strpos($str, ' =') != 0)
        {
            $str = str_replace(' =', '=', $str);
        }

        return explode(' ', trim($str));
    }

    function _eval($content)
    {
        ob_start();
        eval('?' . '>' . trim($content));
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    function _require($filename)
    {
        ob_start();
        include $filename;
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    function html_options($arr)
    {
        $selected = $arr['selected'];

        if ($arr['options'])
        {
            $options = (array)$arr['options'];
        }
        elseif ($arr['output'])
        {
            if ($arr['values'])
            {
                foreach ($arr['output'] AS $key => $val)
                {
                    $options["{$arr[values][$key]}"] = $val;
                }
            }
            else
            {
                $options = array_values((array)$arr['output']);
            }
        }
        if ($options)
        {
            foreach ($options AS $key => $val)
            {
                $out .= $key == $selected ? "<option value=\"$key\" selected>$val</option>" : "<option value=\"$key\">$val</option>";
            }
        }

        return $out;
    }

    function html_select_date($arr)
    {
        $pre = $arr['prefix'];
        if (isset($arr['time']))
        {
            if (intval($arr['time']) > 10000)
            {
                $arr['time'] = gmdate('Y-m-d', $arr['time'] + 8*3600);
            }
            $t     = explode('-', $arr['time']);
            $year  = strval($t[0]);
            $month = strval($t[1]);
            $day   = strval($t[2]);
        }
        $now = gmdate('Y', $this->_nowtime);
        if (isset($arr['start_year']))
        {
            if (abs($arr['start_year']) == $arr['start_year'])
            {
                $startyear = $arr['start_year'];
            }
            else
            {
                $startyear = $arr['start_year'] + $now;
            }
        }
        else
        {
            $startyear = $now - 3;
        }

        if (isset($arr['end_year']))
        {
            if (strlen(abs($arr['end_year'])) == strlen($arr['end_year']))
            {
                $endyear = $arr['end_year'];
            }
            else
            {
                $endyear = $arr['end_year'] + $now;
            }
        }
        else
        {
            $endyear = $now + 3;
        }

        $out = "<select name=\"{$pre}Year\">";
        for ($i = $startyear; $i <= $endyear; $i++)
        {
            $out .= $i == $year ? "<option value=\"$i\" selected>$i</option>" : "<option value=\"$i\">$i</option>";
        }
        if ($arr['display_months'] != 'false')
        {
            $out .= "</select>&nbsp;<select name=\"{$pre}Month\">";
            for ($i = 1; $i <= 12; $i++)
            {
                $out .= $i == $month ? "<option value=\"$i\" selected>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>" : "<option value=\"$i\">" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
            }
        }
        if ($arr['display_days'] != 'false')
        {
            $out .= "</select>&nbsp;<select name=\"{$pre}Day\">";
            for ($i = 1; $i <= 31; $i++)
            {
                $out .= $i == $day ? "<option value=\"$i\" selected>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>" : "<option value=\"$i\">" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
            }
        }

        return $out . '</select>';
    }

    function html_radios($arr)
    {
        $name    = $arr['name'];
        $checked = $arr['checked'];
        $options = $arr['options'];

        $out = '';
        foreach ($options AS $key => $val)
        {
            $out .= $key == $checked ? "<input type=\"radio\" name=\"$name\" value=\"$key\" checked>&nbsp;{$val}&nbsp;"
                : "<input type=\"radio\" name=\"$name\" value=\"$key\">&nbsp;{$val}&nbsp;";
        }

        return $out;
    }

    function html_select_time($arr)
    {
        $pre = $arr['prefix'];
        if (isset($arr['time']))
        {
            $arr['time'] = gmdate('H-i-s', $arr['time'] + 8*3600);
            $t     = explode('-', $arr['time']);
            $hour  = strval($t[0]);
            $minute = strval($t[1]);
            $second   = strval($t[2]);
        }
        $out = '';
        if (!isset($arr['display_hours']))
        {
            $out .= "<select name=\"{$pre}Hour\">";
            for ($i = 0; $i <= 23; $i++)
            {
                $out .= $i == $hour ? "<option value=\"$i\" selected>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>" : "<option value=\"$i\">" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
            }

            $out .= "</select>&nbsp;";
        }
        if (!isset($arr['display_minutes']))
        {
            $out .= "<select name=\"{$pre}Minute\">";
            for ($i = 0; $i <= 59; $i++)
            {
                $out .= $i == $minute ? "<option value=\"$i\" selected>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>" : "<option value=\"$i\">" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
            }

            $out .= "</select>&nbsp;";
        }
        if (!isset($arr['display_seconds']))
        {
            $out .= "<select name=\"{$pre}Second\">";
            for ($i = 0; $i <= 59; $i++)
            {
                $out .= $i == $second ? "<option value=\"$i\" selected>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>" : "<option value=\"$i\">$i</option>";
            }

            $out .= "</select>&nbsp;";
        }

        return $out;
    }
    function cycle($arr)
    {
        static $k, $old;

        $value = explode(',', $arr['values']);
        if ($old != $value)
        {
            $old = $value;
            $k = 0;
        }
        else
        {
            $k++;
            if (!isset($old[$k]))
            {
                $k = 0;
            }
        }

        echo $old[$k];
    }

    function make_array($arr)
    {
        $out = '';
        foreach ($arr AS $key => $val)
        {
            if ($val{0} == '$')
            {
                $out .= $out ? ",'$key'=>$val" : "array('$key'=>$val";
            }
            else
            {
                $out .= $out ? ",'$key'=>'$val'" : "array('$key'=>'$val'";
            }
        }

        return $out . ')';
    }

    function smarty_create_pages($params)
    {
        extract($params);

        if (empty($page))
        {
            $page = 1;
        }

        if (!empty($count))
        {
            $str = "<option value='1'>1</option>";
            $min = min($count - 1, $page + 3);
            for ($i = $page - 3 ; $i <= $min ; $i++)
            {
                if ($i < 2)
                {
                    continue;
                }
                $str .= "<option value='$i'";
                $str .= $page == $i ? " selected='true'" : '';
                $str .= ">$i</option>";
            }
            if ($count > 1)
            {
                $str .= "<option value='$count'";
                $str .= $page == $count ? " selected='true'" : '';
                $str .= ">$count</option>";
            }
        }
        else
        {
            $str = '';
        }

        return $str;
    }
    
    
    public  function jsonp_display($file,$cache_id="")
    {
    	define("JSONP", 1);
    	ob_start();
    	$this->display($file,$cache_id);
    	$content = ob_get_contents();
    	ob_end_clean();
    	$data['html'] = $content;
    	$json = json_encode($data);
    	echo $_GET['callback']."(".$json.")";
    	exit;
    }
}

?>