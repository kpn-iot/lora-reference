# DevEUI_uplink
The API call your Application Server will receive when an uplink message has been received from one of your devices.

## Method
POST

## Query Parameters
`LrnDevEui=XXXXXXXXXXXXXXXX&LrnFPort=1&LrnInfos=TWA_100000000.000.AS-1-000000&AS_ID=100000000.000&Time=yyyy-mm-ddThh:mm:ss.sss7%2B01:00&Token=0123456789012345678901234567890123456789012345678901234567890123`

## JSON Body
```js
{
  "DevEUI_uplink": {
    "Time": "yyyy-mm-ddThh:mm:ss.sss+01:00",
    "DevEUI": "XXXXXXXXXXXXXXXX",
    "FPort": "1",
    "FCntUp": "1",
    "ADRbit": "1",
    "ACKbit": "1", /* Only present when ACK=1 in message */
    "MType": "2",
    "FCntDn": "1",
    "payload_hex": "00",
    "mic_hex": "00000000",
    "Lrcid": "0059AC01",
    "LrrRSSI": "-100.000000",
    "LrrSNR": "1.000000",
    "SpFact": "12",
    "SubBand": "G1",
    "Channel": "LC1",
    "DevLrrCnt": "1",
    "Lrrid": "XXXXXXXX",
    "Late": "0",
    "LrrLAT": "51.907218",
    "LrrLON": "4.489303",
    "Lrrs": {
      "Lrr": [
        {
          "Lrrid": "XXXXXXXX",
          "Chain": "0",
          "LrrRSSI": "-100.000000",
          "LrrSNR": "1.000000",
          "LrrESP": "-100.000000"
        },
        ...
      ]
    },
    "DevLocTime": "yyyy-mm-ddThh:mm:ss.sss+01:00", /* Only present when device has GEO connectivity plan */
    "DevLAT": "51.907218", /* Only present when device has GEO connectivity plan */
    "DevLON": "4.489303", /* Only present when device has GEO connectivity plan */
    "DevAlt": "0.000000", /* Only present when device has GEO connectivity plan */
    "DevAcc": "0.000000", /* Only present when device has GEO connectivity plan */
    "DevLocRadius": "0.000000", /* Only present when device has GEO connectivity plan */
    "DevAltRadius": "0.000000", /* Only present when device has GEO connectivity plan */
    "CustomerID": "100000000",
    "CustomerData": {
      "alr": {
        "pro": "Static",
        "ver": "1"
      }
    },
    "ModelCfg": "0",
    "InstantPER": "0.000000",
    "MeanPER": "0.000000",
    "DevAddr": "XXXXXXXX"
  }
}
```

## XML Body
```xml
<?xml version="1.0" encoding="UTF-8"?>
<DevEUI_uplink xmlns="http://uri.actility.com/lora">
  <Time>yyyy-mm-ddThh:mm:ss.sss+01:00</Time>
  <DevEUI>XXXXXXXXXXXXXXXX</DevEUI>
  <FPort>1</FPort>
  <FCntUp>1</FCntUp>
  <ADRbit>1</ADRbit>
  <ACKbit>1</ACKbit> <!-- Only present when ACK=1 in message -->
  <MType>2</MType>
  <FCntDn>1</FCntDn>
  <payload_hex>00</payload_hex>
  <mic_hex>00000000</mic_hex>
  <Lrcid>0059AC01</Lrcid>
  <LrrRSSI>-100.000000</LrrRSSI>
  <LrrSNR>1.000000</LrrSNR>
  <SpFact>12</SpFact>
  <SubBand>G1</SubBand>
  <Channel>LC1</Channel>
  <DevLrrCnt>1</DevLrrCnt>
  <Lrrid>XXXXXXXX</Lrrid>
  <Late>0</Late>
  <LrrLAT>51.907218</LrrLAT>
  <LrrLON>4.489303</LrrLON>
  <Lrrs>
    <Lrr>
      <Lrrid>XXXXXXXX</Lrrid>
      <Chain>0</Chain>
      <LrrRSSI>-100.000000</LrrRSSI>
      <LrrSNR>1.000000</LrrSNR>
      <LrrESP>-100.000000</LrrESP>
    </Lrr>
    ...
  </Lrrs>
  <DevLocTime>yyyy-mm-ddThh:mm:ss.sss+01:00</DevLocTime> <!-- Only present when device has GEO connectivity plan -->
  <DevLAT>51.907218</DevLAT> <!-- Only present when device has GEO connectivity plan -->
  <DevLON>4.489303</DevLON> <!-- Only present when device has GEO connectivity plan -->
  <DevAlt>0.000000</DevAlt> <!-- Only present when device has GEO connectivity plan -->
  <DevAcc>0.000000</DevAcc> <!-- Only present when device has GEO connectivity plan -->
  <DevLocRadius>0.000000</DevLocRadius> <!-- Only present when device has GEO connectivity plan -->
  <DevAltRadius>0.000000</DevAltRadius> <!-- Only present when device has GEO connectivity plan -->
  <CustomerID>100000000</CustomerID>
  <CustomerData>{"alr":{"pro":"Static","ver":"1"}}</CustomerData>
  <ModelCfg>0</ModelCfg>
  <InstantPER>0.000000</InstantPER>
  <MeanPER>0.000000</MeanPER>
  <DevAddr>XXXXXXXX</DevAddr>
</DevEUI_uplink>
```
