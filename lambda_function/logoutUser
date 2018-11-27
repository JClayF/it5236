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
	//instruct the function to return as soon as the callback is invoked
	context.callbackWaitsForEmptyEventLoop = false;

	//validate input
	var errors = new Array();
	
	 // Validate the user input
	validator.validatEmailValidationID(event.emailvalidationid, errors);
	validator.validateUserId(event.userid, errors);
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
			var sql = "INSERT INTO emailvalidation (emailvalidationid, userid, email, emailsent) " +
            "VALUES (?, ?, ?, NOW())";
			
			conn.query(sql, [event.emailvalidationid, event.userid, event.email], function (err, result) {
				if (err) {
					// Check for duplicate values
					if(err.errno == 1062) {
						console.log(err.sqlMessage);
						if(err.sqlMessage.indexOf('emailvalidationid') != -1) {
							// This should be a "Internal Server Error" error
							callback(formatErrorResponse('BAD_REQUEST', ["Missing emailvalidation id"]));
						} else if(err.sqlMessage.indexOf('userid') != -1) {
							// This should be a "Internal Server Error" error
							callback(formatErrorResponse('BAD_REQUEST', ["Missing user id"]));
						} else if(err.sqlMessage.indexOf('email') != -1) {
							// This should be a "Internal Server Error" error
							callback(formatErrorResponse('BAD_REQUEST', ["Missing Email"]));
						} else {
							// This should be a "Internal Server Error" error
							callback(formatErrorResponse('BAD_REQUEST', ["Email could not be sent."]));
						}
					} else {
						// This should be a "Internal Server Error" error
						callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
					}
	      		} else {
				        	console.log("successful email");
			      			callback(null,"Email Sent");
	      		}
		  	}); //query registration codes
		}); //connect database
	} //no validation errors
} //handler
