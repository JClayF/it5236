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
			var sql = "SELECT attachmenttypeid, name, extension FROM attachmenttypes ORDER BY name";
			
			conn.query(sql, function (err, result) {
			  	if (err) {
					// This should be a "Internal Server Error" error
					callback(formatErrorResponse('INTERNAL_SERVER_ERROR', [err]));
			  	} else {
			  	
			  	// Pull out just the codes from the "result" array (index '1')
		  		var attachlist = [];
		  		for(var i=0; i<result.length; i++) {
					attachlist.push({
						attachmenttypeid  : result[i]['attachmenttypeid'],
						name : result[i]['name'],
						extension : result[i]['extension']}
					);
				}
				// Build an object for the JSON response with the userid and reg codes
				// Return the json object
      			callback(null, attachlist);
		  	} //session deleted
		  	}); //query delete sessopn
		}); //connect database
	} //no validation errors
} //handle
