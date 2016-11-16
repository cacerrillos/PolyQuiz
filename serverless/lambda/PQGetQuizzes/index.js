'use strict';

// We'll again use the AWS SDK to get an instance of our database
var aws = require('aws-sdk');
var db = new aws.DynamoDB();

exports.handler = (event, context, callback) => {
  // We'll modify our response code a little bit so that when the response
  // is ok, we'll return the list of emails in the message
  const RESPONSE = {
    OK : {
      statusCode : 200,
      message: [],
    },
    ERROR : {
      status : 400,
      message: "Something went wrong. Please try again."
    },
    NOT_LOGGED_IN: {
      status: 401,
      message: "Not signed in!"
    }
  };
  if(!event.params || !event.params.header)
    return callback(null, RESPONSE.ERROR);
  if(!event.params.header.pqauthuser)
    return callback(null, RESPONSE.NOT_LOGGED_IN);
  var owner = event.params.header.pqauthuser;
  // We'll use the scan method to get all the data from our database
  db.scan({
    TableName: "PolyQuiz_Quizzes"
    }, function(err, data) {
      if (err) {
        callback(null, RESPONSE.ERROR);
      }
      else {
        // If we get data back, we'll do some modifications to make it easier to read
        for(var i = 0; i < data.Items.length; i++){
          if(data.Items[i].owner.S == owner) {
            RESPONSE.OK.message.push({
              'quizId': data.Items[i].quizId.S,
              'quizName': data.Items[i].quizName.S,
              'questions': data.Items[i].questions.L
            });
          }
        }
        callback(null, RESPONSE.OK);
      }
  });
};