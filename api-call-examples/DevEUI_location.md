# DevEUI_location
The API call your Application Server will receive when there is a newly calculated LoRa GeoLocation solution for one of your devices.

## Method
POST

## Query Parameters
`LrnDevEui=XXXXXXXXXXXXXXXX&LrnFPort=0&LrnInfos=TWA_100000000.000.AS-1-000000&AS_ID=100000000.000&Time=yyyy-mm-ddThh:mm:ss.sss7%2B01:00&Token=0123456789012345678901234567890123456789012345678901234567890123`

## JSON Body
```js
{
  "DevEUI_location": {
    "DevEUI": "XXXXXXXXXXXXXXXX",
    "DevAddr": "XXXXXXXX",
    "Lrcid": "0059AC01",
    "NwGeolocAlgo": "0",
    "NwGeolocTdoaOpt": "0",
    "Time": "yyyy-mm-ddThh:mm:ss.sss+01:00",
    "DevLocTime": "yyyy-mm-ddThh:mm:ss.sss+01:00",
    "DevLAT": "51.907218",
    "DevLON": "4.489303",
    "DevAlt": "0.000000",
    "DevAcc": "0.000000",
    "DevLocRadius": "0.000000",
    "DevAltRadius": "0.000000",
    "CustomerID": "100000000"
  }
}
```

## XML Body
```xml
<?xml version="1.0" encoding="UTF-8"?>
<DevEUI_location xmlns="http://uri.actility.com/lora">
  <DevEUI>XXXXXXXXXXXXXXXX</DevEUI>
  <DevAddr>XXXXXXXX</DevAddr>
  <Lrcid>0059AC01</Lrcid>
  <NwGeolocAlgo>0</NwGeolocAlgo>
  <NwGeolocTdoaOpt>0</NwGeolocTdoaOpt>
  <Time>yyyy-mm-ddThh:mm:ss.sss+01:00</Time>
  <DevLocTime>yyyy-mm-ddThh:mm:ss.sss+01:00</DevLocTime>
  <DevLAT>51.907218</DevLAT>
  <DevLON>4.489303</DevLON>
  <DevAlt>0.000000</DevAlt>
  <DevAcc>0.000000</DevAcc>
  <DevLocRadius>0.000000</DevLocRadius>
  <DevAltRadius>0.000000</DevAltRadius>
  <CustomerID>100000000</CustomerID>
</DevEUI_location>
```
