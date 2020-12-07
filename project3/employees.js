module.exports = function () {
  var express = require('express');
  var router = express.Router();

  router.get('/', function (req, res) {
    res.send("from the employees.js file");
  });

  return router;
}