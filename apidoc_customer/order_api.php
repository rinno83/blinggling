/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										 바로 예약 화면											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/order/view/:menuId 바로 예약 화면	
	* @apiName Get Order Menu
	* @apiGroup Order
	*
	* @apiParam {String} menuId 메뉴 ID [ Unique ].
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {Object[String]} info 안내 문구 목록.
	* @apiSuccess {String} info.delivery 배달 안내 문구.
	* @apiSuccess {String} info.store 식당 안내 문구
	* @apiSuccess {String} info.deliveryFee  배달 요금표 URL
	* @apiSuccess {String} point 회원 포인트.
	* @apiSuccess {Object[String]} menu 메뉴 목록.
	* @apiSuccess {String} menu.menuId 메뉴 ID [ Unique ].
	* @apiSuccess {String} menu.menuType 메뉴 Type [ menu | pick | topping | delivery ].
	* @apiSuccess {String} menu.menuName 메뉴 이름.
	* @apiSuccess {String} menu.basePrice 기본 가격 [ 기본 2인 기준 ].
	* @apiSuccess {String} menu.priceType 가격 타입 [ one(1인추가) | two(2인추가) ].
	* @apiSuccess {String} menu.pricePerPerson 추가 1인 가격 [ 2인일 수 있음 ].
	* @apiSuccess {String} menu.sale 메뉴 할인률 [ 메뉴 타입이 pick일 경우 ].
	* @apiSuccess {String} menu.salePrice 메뉴 할인가 [ 메뉴 타입이 pick일 경우 ].
	* @apiSuccess {String} menu.isNew 메뉴 신규 여부 [ 메뉴 타입이 pick일 경우, 0(신규 아님) | 1(신규) ].
	* @apiSuccess {String} menu.isBonus 덤증정 여부 [ 메뉴 타입이 pick일 경우, 0(덤증정 안함) | 1(덤증정) ].
	* @apiSuccess {String} menu.buyPoint 포인트 가격 [ 메뉴 타입이 topping일 경우, 해당 메뉴를 살 수 있는 포인트 ].
	* @apiSuccess {Object[Json]} menu.menuService 메뉴 서비스 목록.
	* @apiSuccess {String} menu.menuService.name 메뉴 서비스 이름.
	* @apiSuccess {String} menu.menuService.isRequired 메뉴 서비스 필수 여부 [ 1(필수 서비스) | 0(선택 서비스) ].
	* @apiSuccess {Object[String]} menu.menuImageUrl 메뉴 이미지 URL.
	* @apiSuccess {Object[Json]} receiveType 수령 방식 목록.
	* @apiSuccess {String} receiveType.receiveId 수령 방식 ID [ Unique ].
	* @apiSuccess {String} receiveType.name 수령 방식 이름.
	* @apiSuccess {String} receiveType.isOpen 수령 가능 여부 [ 1(가능) | 0(불가능) ].
	* @apiSuccess {Object[Json]} menuList 메뉴 목록.
	* @apiSuccess {String} menuList.menuId 메뉴 ID.
	* @apiSuccess {String} menu.menuType 메뉴 Type [ menu | pick | topping | delivery ].
	* @apiSuccess {String} menuList.menuName 메뉴 이름.
	* @apiSuccess {String} menuList.basePrice 메뉴 기본 가격 [ 2인 기준 ].
	* @apiSuccess {String} menuList.link 메뉴 상세 URL
	* @apiSuccess {String} menuList.menuPoint 포인트 가격 [ 메뉴 타입이 topping일 경우, 해당 메뉴를 살 수 있는 포인트 ].
	* @apiSuccess {String} menuList.sale 메뉴 할인률 [ 메뉴 타입이 pick일 경우 ].
	* @apiSuccess {String} menuList.salePrice 메뉴 할인가 [ 메뉴 타입이 pick일 경우 ].
	* @apiSuccess {String} menuList.isNew 메뉴 신규 여부 [ 메뉴 타입이 pick일 경우, 0(신규 아님) | 1(신규) ].
	* @apiSuccess {String} menuList.isBonus 덤증정 여부 [ 메뉴 타입이 pick일 경우, 0(덤증정 안함) | 1(덤증정) ].
	* @apiSuccess {Object[Json]} deliveryMenuList 배달 메뉴 목록.
	* @apiSuccess {String} deliveryMenuList.menuId 메뉴 ID.
	* @apiSuccess {String} deliveryMenuList.menuType 메뉴 Type [ menu | pick | topping ].
	* @apiSuccess {String} deliveryMenuList.menuName 메뉴 이름.
	* @apiSuccess {String} deliveryMenuList.basePrice 메뉴 기본 가격 [ 2인 기준 ].
	* @apiSuccess {String} deliveryMenuList.priceType 가격 타입 [ one(1인추가) | two(2인추가) ].
	* @apiSuccess {String} deliveryMenuList.pricePerPerson 추가 1인 가격 [ 2인일 수 있음 ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	*	"info":{
	*		"delivery":"",
	*		"store":""
	*		"deliveryFee":""
	*	},
	*	"point":"1010",
	*	"receiveType":[{
	*		"receiveId":"1",
	*		"name":"포장",
	*		"isOpen":"1"	
	*	}],
	*	"menuList":[{
	*		"menuId":"60",
	*		"menuType":"menu",
	*		"menuName":"미친제철1",
	*		"basePrice":"60000",
	*		"link":"http://52.68.133.88:8889/view/60",
	*		"menuPoint":"0",
	*		"sale":"0",
	*		"salePrice":"0",
	*		"isNew":"0",
	*		"isBonus":"0"
	*	}],
	*	"deliveryMenuList":[{
	*		"menuId":"10",
	*		"menuType":"delivery",
	*		"menuName":"쌈 채소",
	*		"basePrice":"1000",
	*		"priceType":"one",
	*		"pricePerPerson":"1000"
	*	}],
	*	"menu": {
	*		"menuId":"1",
	*		"menuName":"미친자연산",
	*		"menuType":"pick",
	*		"menuSummary":"회에 통달한 회신들에게 바치는 자연산 물고기",
	*		"basePrice":"80000",
	*		"priceType":"one",
	*		"pricePerPerson":"30000",
	*		"description":"자연산(自然産) : 양식한 것이 아니라 자연에서 저절로 생산되는 것 그렇습니다. 자연에서 태어나고 자란것만 선별하여 미식가인 회친님들을 위해 정성껏 만들었습니다. 대광어와 도미 그리고 자연산 제철생선을 담아낸 "미친 자연산" 미친물고기는 회친님들의 입맛을 만족시키기 위해 항상 연구하고 노력하겠습니다. ※ 자연산 특성상 경매사정에 따라 판매가 제한될 수 있는 품목임을 알려드립니다. 서비스 - 초밥 인당 2pcs, 약빤 매운탕, 해산물 or 연어 or 전복(인당 1개) *약빤 매운탕 : 제철생선을 과감하게 투척, 국물 맛을 풍성하게 변신시킨 미친물고기만의 매운탕 미친물고기와 함께라면 다양한 회를 매일 함께 할 수 있어요.​ ​ ​ ​구성 : 대 광어 or 도미 + 자연산 생선 ​2인 기준 가격 : 80,000원 / 1인 추가시 30,000원",
	*		"likeCount":"4",
	*		"isWish":"0",
	*		"totalStar":"0",
	*		"commentCount":"0",
	*		"sale":"10",
	*		"salePrice":"1000",
	*		"isNew":"1",
	*		"isBonus":"1",
	*		"buyPoint":"0",
	*		"menuImageUrl":[
	*			"http://52.68.20.211/images/0000000006.jpg",
	*			"http://52.68.20.211/images/0000000005.jpg"
	*		],
	*		"menuService":[{
	*			"name":"초밥 4ps",
	*			"isRequired":"1"
	*		}]
	*	}
	* }
	*
	*/
	
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										결제 하기												   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /api/order 결제 하기
	* @apiName Set Order
	* @apiGroup Order
	*
	* @apiParam {String} Content-Type 요청 Content-Type [ Value : application/json, Header ].
	* @apiParam {Object[json]} menu 예약 메뉴 목록.
	* @apiParam {String} menu.menuId 예약 메뉴 ID.
	* @apiParam {String} menu.menuType 메뉴 Type [ menu | pick | topping | delivery ].
	* @apiParam {String} menu.menuName 예약 메뉴 이름.
	* @apiParam {String} menu.menuCount 예약 메뉴 세트 갯수.
	* @apiParam {String} menu.addPerson 예약 메뉴 추가 인분.
	* @apiParam {String} menu.usePoint 예약 메뉴 사용 포인트.
	* @apiParam {String} menu.isRepresent 대표 예약 메뉴 [ 1(대표) | 0(비대표) ].
	* @apiParam {String} memo 예약 메모.
	* @apiParam {String} orderDateTime 예약 시간 [ Format : Timestamp (초) ].
	* @apiParam {String} customerPhone 예약자 핸드폰 번호 [ Format : 01011112222 ].
	* @apiParam {String} customerAddress 예약자 주소 [ 없으면 빈칸 ].
	* @apiParam {String} customerEmail 예약자 이메일.
	* @apiParam {String} receiveType 수령 방법 [ 1(포장) | 2(배달) | 3(식당)  ].
	* @apiParam {String} [storeId] 식당 ID [ receiveType이 3일 경우 ]
	* @apiParam {String} totalPrice 예약 총 가격.
	* @apiParam {String} totalPoint 예약 총 사용 포인트.
	* @apiParamExample {json} Parameter-Example:
	* {
	*	"menu":[{
	*		"menuId":"1",
	*		"menuType":"menu",
	*		"menuName":"싱싱커플 2~3인용",
	*		"menuCount":"1",
	*		"addPerson":"1",
	*		"usePoint":"0",
	*		"isRepresent": "1"
	*	}],
	*	"memo":"해산물 1개, 전복 1개 주세요.",
	*	"orderDateTime":"1434775991",
	*	"customerPhone":"01027521038",
	*	"customerAddress":"",
	*	"receiveType":"1",
	*	"storeId":"1",
	*	"totalPrice":"65000",
	*	"totalPoint":"1500",
	*	"customerEmail":"rinno83@naver.com"
	}
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} payLink 결제 URL
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	*	"payLink":"http://localhost:8888/pay/confirm/14234234"
	* }
	*
	*/	


	
/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										결제 취소												   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /api/order/cancel 결제 취소
	* @apiName Set Order Cancel
	* @apiGroup Order
	*
	* @apiParam {String} tid LGD 거래 번호.
	* @apiParam {String} orderId 주문 ID.
	*
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	*
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	*	
	* }
	*
	*/	






	
/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										구매 내역												   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/order/history 구매 내역
	* @apiName Get Order History
	* @apiGroup Order
	*
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} orderId  주문 ID [ Unique ].
	* @apiSuccess {String} orderCode  주문 코드.	
	* @apiSuccess {String} sellerId 판매 업체 ID [ Unique ].
	* @apiSuccess {String} sellerName 판매 업체 이름.
	* @apiSuccess {String} receiveTypeName 수령 방법.
	* @apiSuccess {String} storeName 식당 이름 [ 수령방법이 식당일 경우(식당이 아니면 빈 값) ].
	* @apiSuccess {String} orderDateTime 주문 예약 시간 [ Format : Timestamp(초) ].
	* @apiSuccess {String} payType 결제 방법 [ card(카드) | bank(계좌이체) | vbank(무통장입금) ].
	* @apiSuccess {String} vbankUpdateDate 입금 확인 시간 [ 빈값(미입금) | 날짜(Timestamp(초), 입금) ]
	* @apiSuccess {String} workStatus 작업 상태 [ 0(미완료) | 1(완료) -> 미완료시 결제 취소 가능, 완료시 판매 상태에 따라 문의하기로 이동 ].
	* @apiSuccess {String} sellStatus 판매 상태 [ 0(미완료) | 1(완료) -> 미완료시 작업상태에 따라 문의하기로 이동, 완료시 결제취소 불가능 ].
	* @apiSuccess {String} orderPrice  구매 총 가격.	
	* @apiSuccess {String} orderPoint  구매 총 사용 포인트.	
	* @apiSuccess {String} orderStatus  구매 상태 [ cancel | finish ].	
	* @apiSuccess {String} link  결제 완료 모바일 페이지
	* @apiSuccess {String} point  적립 포인트
	* @apiSuccess {String} tid  LGD 결제 코드
	* @apiSuccess {String} registDate  결제일 [ Format : Timestamp(초) ]
	* @apiSuccess {Object[Json]} menu  주문 메뉴.
	* @apiSuccess {String} menu.menuId  메뉴 ID [ Unique ].
	* @apiSuccess {String} menu.menuType 메뉴 Type [ menu | pick | topping | delivery ].
	* @apiSuccess {String} menu.menuName  메뉴 이름.
	* @apiSuccess {String} menu.isRepresent  대표 예약 메뉴 [ 1(대표) | 0(비대표) ].
	* @apiSuccess {String} menu.isWish  찜 여부 [ 1 | 0 ].
	* @apiSuccess {String} menu.isComment  리뷰 작성 여부 [ 1 | 0 ].
	* @apiSuccess {String} menu.menuImageUrl  메뉴 이미지 URL.
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* [{
	*	"orderId":"12",
	*	"orderCode":"1431199255",
	*	"sellerId":"3",
	*	"sellerName":"두화수산",
	*	"receiveTypeName":"배달",
	*	"orderDateTime":"1431198905",
	*	"payType":"vbank",
	*	"vbankUpdateDate":"2015-07-09 21:01:38",
	*	"workStatus":"1",
	*	"sellStatus":"1",
	*	"orderPrice":"65000",
	*	"orderPoint":"1500",
	*	"orderStatus":"finish",
	*	"link":"http://..",
	*	"registDate":"1431193390",
	*	"point":"200",
	*	"tid":"200",
	*	"menu":[{
	*		"menuId":"1",
	*		"menuType":"menu",
	*		"menuName":"싱싱커플",
	*		"isRepresent":"1",
	*		"isWish":"0",
	*		"isComment":"0",
	*		"menuImageUrl":"http://52.68.20.211/images/0000000001.jpg"
	*	}]
	* }]
	*
	*/
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										가능 식당 목록											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/order/store 가능 식당 목록
	* @apiName Get Store List
	* @apiGroup Order
	*
	* @apiParam {String} menuIds 메뉴 IDs [ , 로 구분 (ex : 1,2,3) ].
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} storeId  식당 ID [ Unique ].
	* @apiSuccess {String} name  식당 이름.
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* [{
	*	"storeId":"3",
	*	"name":"문주식당"
	* }]
	*
	*/
	
	
	
	