# DevEUI_downlink_Sent

## Method
POST

## Query Parameters
`LrnDevEui=XXXXXXXXXXXXXXXX&LrnFPort=1&LrnInfos=TWA_100000000.000.AS-1-000000&AS_ID=100000000.000&Time=yyyy-mm-ddThh:mm:ss.sss7%2B01:00&Token=0123456789012345678901234567890123456789012345678901234567890123`

## Body
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
