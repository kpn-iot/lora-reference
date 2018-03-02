'''
 _  __  ____    _   _ 
| |/ / |  _ \  | \ | |
| ' /  | |_) | |  \| |
| . \  |  __/  | |\  |
|_|\_\ |_|     |_| \_|

Key Generator and Shannon Entropy Calculator
for Python


Entropy Calculator inspired by: https://rosettacode.org/wiki/Entropy

(c) 2018 KPN
License: MIT License
Author: Mark Prins

'''
import os, sys
import math
import binascii

DEFAULT_KEYLENGTH = 16

def generate_key(length=DEFAULT_KEYLENGTH):
    """ Returns a bytestring of <length> (proper) random bytes """
    return os.urandom(length)

def shannon_entropy(inputstring):
    """ Returns the shannon entropy in bits/symbol calculated over the <inputstring> """  
    return sum([-(inputstring.count(c) / float(len(inputstring)) * math.log(inputstring.count(c) / float(len(inputstring)), 2)) for c in set(inputstring)])

if __name__ == "__main__":
    if len(sys.argv) > 1:
        keylength = int(sys.argv[1])
    else:
        keylength = DEFAULT_KEYLENGTH

    key = generate_key(keylength)
    
    print("Key    : {key}".format(key=binascii.b2a_hex(key).decode("utf8").upper()))
    print("Length : {bytes} Bytes / {bits} bits".format(bytes=len(key), bits=len(key)*8))
    print("Entropy: {entropy} bits/symbol".format(entropy=shannon_entropy(key)))
