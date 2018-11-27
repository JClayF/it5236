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
	validator.validateAdmin(event.userid, errors);
	
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
			var sql = "SELECT isadmin FROM users WHERE userid = ?";
			
			conn.query(sql, [event.userid], function (err, result) {
			  	if (err) {
					// This should be a "Internal Server Error" error
					callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
			  	} else {
			  		if (result[0].isadmin == 0){
			  			errors.push("ACCESS_DENIED");
			  			callback(formatErrorResponse('ACCESS_DENIED, errors'));
			  			
			  		} else {
			  			
			  		}
			  		if (result.length > 0){
			    		console.log("Admin");

						var userid = { 
							userid  : event.userid
							
						};
						
						callback(null,userid);
			  		
			  		}
					 }
				}); //session retrieved
		  	}); //query get session
		} //connect database
	} //no validation errors
