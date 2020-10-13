# DevEUI_uplink
The API call your Application Server will receive when a join request has been received from one of your devices.

## Method
POST

## Query Parameters
`LrnDevEui=XXXXXXXXXXXXXXXX&LrnFPort=1&LrnInfos=TWA_100000000.000.AS-1-000000&AS_ID=100000000.000&Time=yyyy-mm-ddThh:mm:ss.sss7%2B01:00&Token=0123456789012345678901234567890123456789012345678901234567890123`

## JSON Body
```js
{
    "DevEUI_uplink": {
        "Time": "2019-05-29T03:50:43.152+02:00",
        "DevEUI": "XXXXXXXXXXXXXXXX",
        "rawJoinRequest": "0061010100000d5900106000000503d07c649e80d73062",
        "Lrrid": "XXXXXXXX",
        "Chain": "0",
        "Channel": "LC1",
        "Spfact": "12",
        "SubBand": "G1",
        "MType": "0",
        "mic_hex": "XXXXXXXX",
        "airtime": "1.000000",
        "LrrLAT": "00.000000",
        "LrrLON": "0.000000",
        "Lrrs": {
            "Lrr": [
                {
                    "Lrrid": "XXXXXXXX",
                    "LrrRSSI": "-96.000000",
                    "LrrSNR": "1.000000",
                    "LrrESP": "-98.539017"
                }
            ]
        },
        "CustomerID": "100000000",
        "CustomerData": {
            "alr": {
                "pro": "Static",
                "ver": "1"
            }
        }
    }
}
```

## XML Body
n.a.
