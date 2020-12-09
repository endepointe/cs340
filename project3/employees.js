var express = require('express');
var router = express.Router();

router.get('/', (req, res) => {

  //res.redirect('/employees');

  let cbCount = 0;
  var mysql = req.app.get('mysql');
  var context = {}
  context.jsscripts = ["filteremployees.js", "clearfilter.js", "searchemployees.js"];

  // Section A:
  getEmployees(res, mysql, context, complete);
  getProjects(res, mysql, context, complete);
  ////

  function complete() {
    cbCount++;
    console.log(context);
    // needs to be >= number of functions called in section A of the 
    // current router object.
    if (cbCount >= 2) {
      res.render('employee', context)
    }
  }
});

////////////////////////////////////////////////////////////////////////////////
const getEmployees = (res, mysql, context, complete) => {
  console.log(mysql);
  let sql = `
    SELECT 
      Fname, Lname, Salary, Dno
    FROM EMPLOYEE`;
  mysql.pool.query(sql, function (error, results, fields) {
    if (error) {
      res.write(JSON.stringify(error));
      //res.end();
    }
    context.employees = results;
    complete();
    //console.log(results);
  });
}

////////////////////////////////////////////////////////////////////////////////
const getProjects = (res, mysql, context, complete) => {
  console.log(mysql);
  let sql = `
    SELECT 
      Pname, Pnumber, Dnum
    FROM PROJECT`;
  mysql.pool.query(sql, function (error, results, fields) {
    if (error) {
      res.write(JSON.stringify(error));
      //res.end();
    }
    context.projects = results;
    complete();
  });
}

////////////////////////////////////////////////////////////////////////////////
function getEmployeesByProject(req, res, mysql, context, complete) {
  var query = `
    select E.Fname, E.Lname, E.Salary, E.Dno
    from EMPLOYEE E JOIN PROJECT P ON E.Dno = P.Dnum
    where P.Pname = '${req.params.projectname}'
  `;

  var inserts = [req.params.projectname];

  mysql.pool.query(query, function (error, results, fields) {
    if (error) {
      res.write(JSON.stringify(error));
      res.end();
    }
    context.employees = results;
    complete();
  });
}

////////////////////////////////////////////////////////////////////////////////
function getEmployeesWithFname(req, res, mysql, context, complete) {
  //sanitize the input as well as include the % character
  var query = `
    select Fname, Lname, Salary, Dno
    from EMPLOYEE 
    where Fname = '${req.params.s}'
  `;

  console.log(req.params.s);

  mysql.pool.query(query, function (error, results, fields) {
    if (error) {
      res.write(JSON.stringify(error));
      res.end();
    }
    context.employees = results;
    complete();
  });
}

////////////////////////////////////////////////////////////////////////////////
router.get('/filter/:projectname', function (req, res) {

  var callbackCount = 0;
  var context = {};
  context.jsscripts = ["filteremployees.js", "clearfilter.js", "searchemployees.js"];

  var mysql = req.app.get('mysql');
  getEmployeesByProject(req, res, mysql, context, complete);
  getProjects(res, mysql, context, complete)
  function complete() {
    callbackCount++;
    if (callbackCount >= 2) {
      res.render('employee', context);
    }
  }
});

////////////////////////////////////////////////////////////////////////////////
router.get('/search/:s', function (req, res) {
  var callbackCount = 0;
  var context = {};
  context.jsscripts = ["filteremployees.js", "clearfilter.js", "searchemployees.js"];
  var mysql = req.app.get('mysql');

  getEmployeesWithFname(req, res, mysql, context, complete);
  getProjects(res, mysql, context, complete);
  function complete() {
    callbackCount++;
    if (callbackCount >= 2) {
      res.render('employee', context);
    }
  }
});
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
module.exports = router;