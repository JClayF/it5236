var mysql = require('./node_modules/mysql');
var config = require('./config.json');
var validator = require('./validation.js');

function formatErrorResponse(code, errs) {
	return JSON.stringify({ 
		error  : code,
		errors : errs
	});
}

exports.handler = (event, context, callback) => {
	context.callbackWaitsForEmptyEventLoop = false;

	//validate input
	var errors = new Array();
	
	 // Validate the user input
	validator.validateThingId(event.thingid, errors);
	
	if(errors.length > 0) {
		// This should be a "Bad Request" error
		callback(formatErrorResponse('BAD_REQUEST', errors));
	} else {
	
		//getConnection equivalent
		var conn = mysql.createConnection({
			host 	: config.dbhost,
			user 	: config.dbuser,
			password : config.dbpassword,
			database : config.dbname
		});
		
	
		//attempts to connect to the database
		conn.connect(function(err) {
		  	
			if (err)  {
				// This should be a "Internal Server Error" error
				callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
			};
			console.log("Connected!");
			var sql = "SELECT things.thingid, things.thingname, "+
								"convert_tz(things.thingcreated,@@session.time_zone,'America/New_York') as "+
								"thingcreated, things.thinguserid, things.thingattachmentid, things.thingregistrationcode, username, filename " +
                "FROM things LEFT JOIN users ON things.thinguserid = users.userid " +
                "LEFT JOIN attachments ON things.thingattachmentid = attachments.attachmentid "+
                "WHERE thingid = ?";
			
			conn.query(sql, [event.thingid], function (err, result) {
			  	if (err) {
					// This should be a "Internal Server Error" error
					callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
			  	} else {
			  		
			  		if (result.length > 0){
			    		console.log("Thing retrieved");

						var thing = { 
							thingid  : result[0]['thing'],
							thingname : result[0]['thingname'],
							thingcreated : result[0]['thingcreated'],
							email : result[0]['email'],
							thinguserid : result[0]['thinguserid'],
							thingattachmentid : result[0]['thingattachmentid'],
							thingregistrationcode : result[0]['thingregistrationcode']
						};
						
						callback(null,thing);
					 
					 } else {
						callback(formatErrorResponse('NOT_FOUND', errors));
					 }
				} //session retrieved
		  	}); //query get session
		}); //connect database
	} //no validation errors
} //handle
