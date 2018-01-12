# DevEUI_downlink_Sent
The API call your Application Server will receive when a queued downlink message has been sent to one of your devices.

## Method
POST

## Query Parameters
`LrnDevEui=XXXXXXXXXXXXXXXX&LrnFPort=1&LrnInfos=TWA_100000000.000.AS-1-000000&AS_ID=100000000.000&Time=yyyy-mm-ddThh:mm:ss.sss7%2B01:00&Token=0123456789012345678901234567890123456789012345678901234567890123`

## JSON Body
```js
{
  "DevEUI_downlink_Sent": {
    "Time": "yyyy-mm-ddThh:mm:ss.sss+01:00",
    "DevEUI": "XXXXXXXXXXXXXXXX",
    "FPort": "1",
    "FCntDn": "1",
    "FCntUp": "1",
    "Lrcid": "0059AC01",
    "SpFact": "12",
    "SubBand": "G1",
    "Channel": "LC1",
    "Lrrid": "XXXXXXXX",
    "DeliveryStatus": "1",
    "DeliveryFailedCause1": "C0",
    "DeliveryFailedCause2": "00",
    "DeliveryFailedCause3": "00",
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
```xml
<?xml version="1.0" encoding="UTF-8"?>
<DevEUI_downlink_Sent xmlns="http://uri.actility.com/lora">
  <Time>yyyy-mm-ddThh:mm:ss.sss+01:00</Time>
  <DevEUI>XXXXXXXXXXXXXXXX</DevEUI>
  <FPort>1</FPort>
  <FCntDn>1</FCntDn>
  <FCntUp>1</FCntUp>
  <Lrcid>0059AC01</Lrcid>
  <SpFact>12</SpFact>
  <SubBand>G1</SubBand>
  <Channel>LC1</Channel>
  <Lrrid>XXXXXXXX</Lrrid>
  <DeliveryStatus>1</DeliveryStatus>
  <DeliveryFailedCause1>C0</DeliveryFailedCause1>
  <DeliveryFailedCause2>00</DeliveryFailedCause2>
  <DeliveryFailedCause3>00</DeliveryFailedCause3>
  <CustomerID>100000000</CustomerID>
  <CustomerData>{"alr":{"pro":"Static","ver":"1"}}</CustomerData>
</DevEUI_downlink_Sent>
```

## DeliveryFailedCause codes

* Default value: `00`
  * `00`: "Success"
* `Ax`: Class A: Transmission slot busy on RX1
  * `A0`: "Radio stopped"
  * `A1`: "Downlink radio stopped"
  * `A2`: "RX1 not available" // theoretically impossible
  * `A3`: "Radio busy"
  * `A4`: "Listen before talk (LBT): channel busy"
* `Bx`: Class A: Too late for RX1
  * `B0`: "Frame received too late for RX1"
* `Cx`: Class A: LRC selects RX2
  * `C0`: "LRC selected RX2"
* `Dx`: Class A: Duty Cycle constraint on RX1
  * `D0`: "Duty cycle constraint detected by LRR"
  * `DA`: "Duty cycle constraint detected by LRC"
