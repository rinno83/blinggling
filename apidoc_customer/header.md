### Dev Server
이름  | 내용
:----------- | :-----------
Server Host | http://pay.crazyfish.co.kr
CMS Host | http://52.68.133.88:8889
Server Upload Host | http://52.68.133.88
Service-Key | 1093a9bd19fbb04c2925bc877af84b9c77f8ec76
Secret-Key  | cfd9f3ed769a1462d5aef567437f0143
모바일 공지사항 URL | [http://pay.crazyfish.co.kr/mobile/notice](http://52.68.20.211:8888/mobile/notice)
모바일 FAQ URL | [http://pay.crazyfish.co.kr/mobile/faq](http://52.68.20.211:8888/mobile/faq)
모바일 약관 URL | [http://pay.crazyfish.co.kr/mobile/terms](http://52.68.20.211:8888/mobile/terms)
예약 정보 | [http://pay.crazyfish.co.kr/api/order/info/:orderCode](http://pay.crazyfish.co.kr/api/order/info/:orderCode)
계좌 확인 | [http://pay.crazyfish.co.kr/pay/vbank/:orderCode](http://pay.crazyfish.co.kr/pay/vbank/:orderCode)


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