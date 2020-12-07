var mysql = require('mysql');
var pool = mysql.createPool({
  connectionLimit: 10,
  host: 'classmysql.engr.oregonstate.edu',
  user: 'cs340_johnsal',
  password: 'Dip537What3f#$',
  database: 'cs340_johnsal'
});

module.exports.pool = pool;
