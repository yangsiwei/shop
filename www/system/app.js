var events = require('events'); 

start_cron("fair","__HOST__",80,"__APP_ROOT__/cron.php",30000,{"auth":"__TYPE_FAIR__"}); 
start_cron("mass","__HOST__",80,"__APP_ROOT__/cron.php",30000,{"auth":"__TYPE_MASS__"});  
start_cron("lottery","__HOST__",80,"__APP_ROOT__/cron.php",3000,{"auth":"__TYPE_LOTTERY__"});
start_cron("logmoving","__HOST__",80,"__APP_ROOT__/cron.php",30000,{"auth":"__TYPE_LOGMOVING__"});
start_cron("mail","__HOST__",80,"__APP_ROOT__/cron.php",3000,{"auth":"__TYPE_MAIL__"});
start_cron("sms","__HOST__",80,"__APP_ROOT__/cron.php",3000,{"auth":"__TYPE_SMS__"});
start_cron("weixin","__HOST__",80,"__APP_ROOT__/cron.php",3000,{"auth":"__TYPE_WEIXIN__"});
start_cron("robot","__HOST__",80,"__APP_ROOT__/cron.php",1000,{"auth":"__TYPE_ROBOT__"});
start_cron("robot_cfg","__HOST__",80,"__APP_ROOT__/cron.php",1000,{"auth":"__TYPE_ROBOT_CFG__"});
start_cron("android","__HOST__",80,"__APP_ROOT__/cron.php",3000,{"auth":"__TYPE_ANDROID__"}); 
start_cron("ios","__HOST__",80,"__APP_ROOT__/cron.php",3000,{"auth":"__TYPE_IOS__"}); 
start_cron("gc","__HOST__",80,"__APP_ROOT__/cron.php",30000,{"auth":"__TYPE_GC__"}); 


function start_cron(name,host,port,url,timespan,postdata)
{
	
	
	var emitter = new events.EventEmitter(); 
	var cronInterval = null;
	
	emitter.on(name, function() { 	
		clearInterval(cronInterval);
		var http = require('http');
		var qs = require('querystring');  
		var content = qs.stringify(postdata); 
		
		var options = {
		    hostname: host,
		    data:postdata,
		    port: port,
		    path: url,
		    method: 'POST',
		    headers: {  
		        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'  
		    }
		};
		var req = http.request(options, function (res) {
			cronInterval = setInterval(function() { 
		    		emitter.emit(name); 
		    }, timespan);
			//console.log('STATUS: ' + res.statusCode);
		    //console.log('HEADERS: ' + JSON.stringify(res.headers));
		    res.setEncoding('utf8');
		    res.on('data', function (chunk) {		    	
				console.log('BODY: ' + chunk);
		    });
		});
		req.on('error', function (e) {
			cronInterval = setInterval(function() { 
	    		emitter.emit(name); 
			}, timespan);
		    console.log('problem with request: ' + e.message);
		});
		
		req.write(content); 
		req.end();		
	});
	
	cronInterval = setInterval(function() { 
		emitter.emit(name); 
	}, timespan);

}