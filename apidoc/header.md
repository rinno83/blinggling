### Dev Server
이름  | 내용
:----------- | :-----------
Server Host | http://52.69.180.247:8888
CMS Host | http://52.69.180.247:8889
Server Upload Host | http://52.69.180.247
Service-Key | 7011b2d6837379efaf8ce6fd78c7d862


<br/>

###  Header
Field  | Type  | Description
:----------- | :-----------: | -----------
Signature | String | Signature Value [ HMAC-SHA1(URL + 서비스키 + 디바이스 + [Access-Token] + "\n" + 시간 ( Timestamp ), {SecretKey}) ]
RequestTime | String | Request Datetime [ Timestamp (second) ]
Client-Type | String | Device OS [ANDROID OR IPHONE].
Service-Key  | String | Service Key.
Language-Code | String | ISO CoutryCode 2bit.
Access-Token [optional] | String | 인증 필요시 사용되는 세션 토큰.
Content-Type | String | application/x-www-form-urlencoded

<br/>