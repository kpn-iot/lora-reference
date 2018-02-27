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
var crypto = require('crypto');


var keygen = {
  // get a key of (length) bytes in length in hexadecimal representation
  key: function (length) {
    return crypto.randomBytes(length).toString('hex').toUpperCase();
  },

  // Measure the entropy of a string in bits per symbol.
  entropy: function (s) {
    var sum = 0, len = s.length;
    this.entropyProcess(s, function (k, f) {
      var p = f / len;
      sum -= p * Math.log(p) / Math.log(2);
    });
    return sum;
  },

  // Create a dictionary of character frequencies and iterate over it.
  entropyProcess: function (s, evaluator) {
    var h = Object.create(null), k;
    s.split('').forEach(function (c) {
      h[c] && h[c]++ || (h[c] = 1);
    });
    if (evaluator) {
      for (k in h) {
        evaluator(k, h[k]);
      }
    }
    return h;
  }
};

var length = 16;
var key = keygen.key(length);
var entropy = keygen.entropy(key);

console.log('length', length);
console.log('key', key);
console.log('entropy', entropy);
