
var app = require('http').createServer();
var server = app.listen(1666);
var holla = require('holla');
var rtc = holla.createServer(server);

console.log('Server running on port 1666');
