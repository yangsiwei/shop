var events = require('events'); 

start_cron("fair","122.114.94.153",80,"/cron.php",30000,{"auth":"66PXii9DiMrUYh5dWLAjxKN98Ydeo3CWDvcDS7KbiR1fTwBdyDZGeYmrwnjywpgQ"}); 
start_cron("mass","122.114.94.153",80,"/cron.php",30000,{"auth":"0oFZtwqMM4aGHxQFmaiWmaN98Ydeo3CWDvcDS7KbiR1fTwBdyDZGeYmrwnjywpgQ"});  
start_cron("lottery","122.114.94.153",80,"/cron.php",3000,{"auth":"78OmSg6o0+QlnXtImvpRH2In/uy1qxkXPgCtfcSkF4VTZOSPpice/6m8ROEpHyY5"});
start_cron("logmoving","122.114.94.153",80,"/cron.php",30000,{"auth":"6NEgFO4Kgr9f19a7KTiXg/Mrrw4GB5UT8yo+Et7QtF4jqgDjOBPDk7ttaZHwFaFq"});
start_cron("mail","122.114.94.153",80,"/cron.php",3000,{"auth":"6LpxGKUNJZxczDMYcdZccqN98Ydeo3CWDvcDS7KbiR1fTwBdyDZGeYmrwnjywpgQ"});
start_cron("sms","122.114.94.153",80,"/cron.php",3000,{"auth":"hGcqe4FtUdUBvEveDKrCYkPezm47Fvy+YQSO4oQwR6IIk6AtMiDr47bhAgqjNx7h"});
start_cron("weixin","122.114.94.153",80,"/cron.php",3000,{"auth":"6p7A2dJjR84IXSpTIlANrSWOrzOrppnoG1VQoUADZQCxfY1X9MgejYRP6Y/rBn7B"});
start_cron("robot","122.114.94.153",80,"/cron.php",1000,{"auth":"R79E19FvPc4pPCLkai1+lJ5jK/ozSXuZ3Kb0IH5TLYfwq/h1mgBPexvTZE9Zsest"});
start_cron("robot_cfg","122.114.94.153",80,"/cron.php",1000,{"auth":"2zzxOvdSalOAaXJgkJbIsPmfFlr17FRA7eJ9CdrxqR4jqgDjOBPDk7ttaZHwFaFq"});
start_cron("android","122.114.94.153",80,"/cron.php",3000,{"auth":"dT3ehHmsqcdXIE/7QQm6s2In/uy1qxkXPgCtfcSkF4VTZOSPpice/6m8ROEpHyY5"}); 
start_cron("ios","122.114.94.153",80,"/cron.php",3000,{"auth":"e5e0vksSpxqUMkqfRYgHvUPezm47Fvy+YQSO4oQwR6IIk6AtMiDr47bhAgqjNx7h"}); 
start_cron("gc","122.114.94.153",80,"/cron.php",30000,{"auth":"7wElqW6vG2FcjnhomEckLg1W2t6uP5ZE0VlwjRrinrFmS3MODuvVFvhAGWeCwNRp"}); 


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