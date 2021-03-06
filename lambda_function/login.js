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
	validator.validateUserId(event.userid, errors);
	validator.validatePasswordHash(event.passwordhash, errors);
	validator.validateEmail(event.email, errors);
	validator.validateEmailValidated(event.emailvalidated, errors);
	validator.validateUsername(event.username, errors);
	
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
			var sql = "SELECT userid, passwordhash, email, emailvalidated FROM users " +
                "WHERE username = ?";
			
			conn.query(sql, [event.userid, event.passwordhash, event.email, event.emailvalidated, event.username], function (err, result) {
				if (err) {
					// Check for duplicate values
					if(err.errno == 1062) {
						console.log(err.sqlMessage);
						if(err.sqlMessage.indexOf('userid') != -1) {
							// This should be a "Internal Server Error" error
							callback(formatErrorResponse('BAD_REQUEST', ["Missing user id"]));
						} else if(err.sqlMessage.indexOf('passwordhash') != -1) {
							// This should be a "Internal Server Error" error
							callback(formatErrorResponse('BAD_REQUEST', ["Missing password hash"]));
						} else if(err.sqlMessage.indexOf('email') != -1) {
							// This should be a "Internal Server Error" error
							callback(formatErrorResponse('BAD_REQUEST', ["Missing email"]));
						} else if(err.sqlMessage.indexOf('emailvalidated') != -1) {
							// This should be a "Internal Server Error" error
							callback(formatErrorResponse('BAD_REQUEST', ["Missing email validation"]));
						} else {
							// This should be a "Internal Server Error" error
							callback(formatErrorResponse('BAD_REQUEST', ["Duplicate value"]));
						}
					} else {
						// This should be a "Internal Server Error" error
						callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
					}
	      		} else {
	      			
	      					var json = {
	      						userid: event.userid,
	      						username: event.username,
	      						passwordhash: event.passwordhash,
	      						emailvalidated: event.emailvalidated
	      					};
				        	console.log("successful new attachmenttype");
			      			callback(null,json);
	      		}
		  	}); //query registration codes
		}); //connect database
	} //no validation errors
} //handler
