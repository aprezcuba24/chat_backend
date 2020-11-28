var http = require('http');

function makeRequest(input) {
  const data = JSON.stringify(input)
  const options = {
    hostname: 'web',
    port: 80,
    path: '/api/messages',
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Content-Length': data.length,
      'X-AUTH-TOKEN': 'secretKeyTest',
    }
  }
  const req = http.request(options, res => {
    console.log(`statusCode: ${res.statusCode}`)
  
    res.on('data', d => {
      process.stdout.write(d)
    })
  })
  req.on('error', error => {
    console.error(error)
  })
  req.write(data)
  req.end()
}

http.createServer(function (req, res) {
  if (req.method == 'POST') {
    var body = '';
    req.on('data', function (data) {
      body += data;
      if (body.length > 1e6)
        req.connection.destroy();
    });
    req.on('end', function () {
      const data = JSON.parse(body)
      console.log(data)
      if (data['body'].startsWith('calculate ')) {
        const calculate = data['body'].replace("calculate ", "");
        const result = eval(calculate);
        makeRequest({ "body": `Resut: ${result}`, "channel": data['channel'] })
      }
    });
  }
  res.writeHead(200, {'Content-Type': 'text/plain'});
  res.end('Hello World\n');
}).listen(8080, "0.0.0.0");
console.log('Server running at http://0.0.0.0:8080/');
