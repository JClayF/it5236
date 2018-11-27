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
	validator.validateUsername(event.username, errors);
	validator.validateEmail(event.email, errors);

	
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
	
	//prevent timeout from waiting event loop
	context.callbackWaitsForEmptyEventLoop = false;

	//attempts to connect to the database
	conn.connect(function(err) {
	  	
		if (err)  {
			// This should be a "Internal Server Error" error
			callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
		};
		console.log("Connected!");
		var sql = "SELECT email, userid FROM users WHERE username = ? OR email = ?";
		
		conn.query(sql, [event.username, event.email], function (err, result) {
		  	if (err) {
				// This should be a "Internal Server Error" error
				callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
		  	} else {
		    	console.log("No User ID");
		    	if (result[0].codecount == 0){
		    		errors.push("No User ID");
					callback(formatErrorResponse('BAD_REQUEST', errors));
		    	} else {
					var sql = "INSERT INTO passwordreset (passwordresetid, userid, email, expires) " +
                        "VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 1 HOUR))";
					
						if (err) {
							// Check for duplicate values
							if(err.errno == 1062) {
								console.log(err.sqlMessage);
								if(err.sqlMessage.indexOf('passwordresetid') != -1) {
									// This should be a "Internal Server Error" error
									callback(formatErrorResponse('BAD_REQUEST', ["No password reset ID"]));
								} else if(err.sqlMessage.indexOf('userid') != -1) {
									// This should be a "Internal Server Error" error
									callback(formatErrorResponse('BAD_REQUEST', ["No user ID"]));
								} else if(err.sqlMessage.indexOf('email') != -1) {
									// This should be a "Internal Server Error" error
									callback(formatErrorResponse('BAD_REQUEST', ["Missing Email"]));
								} else {
									// This should be a "Internal Server Error" error
								callback(formatErrorResponse('BAD_REQUEST', ["Duplicate value"]));	
								}
							} else {
								// This should be a "Internal Server Error" error
								callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
							}
									conn.query(sql, [event.userid, event.registrationcode], function (err, result) {
								if (err) {
						        	console.log("successful registration");
					      			callback(null,"user registration successful");
				      			}
		      				}); //query userregistrations
			      		} //error users
		  			} //good registration
				} //good code count
		  	}); //query registration codes
		}); //connect database
	} //no validation errors
} //handle
