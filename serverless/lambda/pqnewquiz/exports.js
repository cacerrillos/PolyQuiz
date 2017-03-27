'use strict';

// Require the AWS SDK and get the instance of our DynamoDB
var aws = require('aws-sdk');
var randomstring = require("randomstring");

var db = new aws.DynamoDB();

// Set up the model for our the email
var model = {
  quizId: {"S" : ""},
  quizName: {"S" : ""},
  owner: {"S" : ""}
};

// This will be the function called when our Lambda function is exectued
exports.handler = (event, context, callback) => {

  // We'll use the same response we used in our Webtask
  const RESPONSE = {
    OK : {
      statusCode : 200,
      message: "You have successfully subscribed to the newsletter!",
    },
    DUPLICATE : {
      status : 400,
      message : "You are already subscribed."
    },
    ERROR : {
      status : 400,
      message: "Something went wrong. Please try again."
    },
    BAD_REQUEST : {
      status : 400,
      message: "Bad request. Please check your request."
    },
    NOT_AUTHORIZED : {
      status : 401,
      message: "Not logged in. Please sign in."
    },
  };

  // Capture the email from our POST request
  // For now, we'll just set a fake email
  var quizId = randomstring.generate(8);
  var quizName = event.body.quizName;
  var owner = event.owner;

  if(!quizName){
    // If we don't get an email, we'll end our execution and send an error
    return callback(null, RESPONSE.BAD_REQUEST);
  }
  if(!owner) {
    return callback(null, RESPONSE.NOT_AUTHORIZED);
  }

  // If we do have an email, we'll set it to our model
  model.quizId.S = quizId;//quizId;
  model.quizName.S = quizName;
  model.owner.S = owner;

  // Insert the email into the database, but only if the email does not already exist.
  db.putItem({
    TableName: 'PolyQuiz_Quizzes',
    Item: model,
    Expected: {
      quizId: { Exists: false }
    }
  }, function (err, data) {
    if (err) {
      console.log(quizId);
      console.log(err);
      // If we get an err, we'll assume it's a duplicate email and send an
      // appropriate message
      context.done('error','putting item into dynamodb failed: '+err);
      //return callback(null, RESPONSE.DUPLICATE);
    }
    // If the data was stored succesfully, we'll respond accordingly
    callback(null, RESPONSE.OK);
  });
};