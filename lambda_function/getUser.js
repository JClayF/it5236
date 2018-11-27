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
	validator.validateUser(event.userid, errors);
	
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
			var sql = "SELECT userid, username, email, isadmin FROM users WHERE userid = ?";
			
			conn.query(sql, [event.userid], function (err, result) {
			  	if (err) {
					// This should be a "Internal Server Error" error
					callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
			  	} else {
			  		
			  		if (result.length > 0){
			    		console.log("Session retrieved");

						var session = { 
							userid  : result[0]['userid'],
							username : result[0]['username'],
							email : result[0]['email'],
							isadmin : result[0]['isadmin']};
						
						callback(null,session);
					 
					 } else {
						callback(formatErrorResponse('NOT_FOUND', errors));
					 }
				} //session retrieved
		  	}); //query get session
		}); //connect database
	} //no validation errors
} //handle
