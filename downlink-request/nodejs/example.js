/*  _  __  ____    _   _ 
 * | |/ / |  _ \  | \ | |
 * | ' /  | |_) | |  \| |
 * | . \  |  __/  | |\  |
 * |_|\_\ |_|     |_| \_|
 * 
 * (c) 2018 KPN
 * License: MIT License
 * Author: Paul Marcelis
 * 
 */

const moment = require('moment');
const sjcl = require('sjcl');
const https = require('https');
const url = require('url');

var devEUI = "0123456789012345";
var fPort = 1;
var payload = "00";
var asId = "100000000.000";
var lrcAsKey = "01234567890123456789012345678901";

var postUrl = getDownlinkUrl(devEUI, fPort, payload, asId, lrcAsKey);
postUrl.method = "POST";

var request = https.request(postUrl, function(res) {
  switch (res.statusCode) {
    case 200:
      console.log("Request succeeded");
      break;
    default:
      console.log("Error code " + res.statusCode);
      res.setEncoding('utf8');
      res.on('data', function (chunk) {
        chunk = chunk.replace("<html><body>", "");
        chunk = chunk.replace("</body></html>", "");
        console.log(chunk);
      });
  }
});

request.on('error', function(e) {
  console.log('Request error: ' + e.message);
});

request.end();

/**
 * 
 * @param {string} devEUI 
 * @param {integer} fPort 
 * @param {string} payload 
 * @param {string} asId 
 * @param {string} lrcAsKey 
 */
function getDownlinkUrl(devEUI, fPort, payload, asId, lrcAsKey, confirmed = false, flushDownlinkQueue = false) {
  var baseUrl = "https://api.kpn-lora.com/thingpark/lrc/rest/downlink";
  var now = new Date();
  var timestamp = moment().utc().format("YYYY-MM-D\THH:mm:ss")

  var queryParameters = {
    "DevEUI": devEUI,
    "FPort": fPort,
    "Payload": payload,
    "AS_ID": asId,
    "Confirmed": (confirmed) ? 1 : 0,
    "FlushDownlinkQueue": (flushDownlinkQueue) ? 1 : 0,
    "Time": timestamp
  };

  var queryString = '', val = '';
  for (var key in queryParameters) {
    val = queryParameters[key];
    queryString += key + "=" + val + "&";
  };
  queryString = queryString.slice(0, -1);

  var hashIn = queryString + lrcAsKey;
  var tokenInt = sjcl.hash.sha256.hash(hashIn);
  var token = sjcl.codec.hex.fromBits(tokenInt);
  var postUrl = url.parse(baseUrl + "?" + queryString + "&Token=" + token);
  postUrl.method = "POST";

  return postUrl;
}
