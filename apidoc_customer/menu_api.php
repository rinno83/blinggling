/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										미친 메뉴 리스트										   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/menu/page/:currentPage/:prefer 미친 메뉴 리스트
	* @apiName Get Menu List
	* @apiGroup Menu
	*
	* @apiParam {String} currentPage 현재 페이지 [ Default : 1 ]
	* @apiParam {String} prefer 선호회 타입 [ 선호회 리스트 참고 ]
	*
	* @apiSuccess {String} [Access-Token]  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} menuId  메뉴 ID [ Unique ].
	* @apiSuccess {String} menuName  메뉴 이름.
	* @apiSuccess {String} menuSummary  메뉴 요약 안내.
	* @apiSuccess {String} description  메뉴 상세 설명.
	* @apiSuccess {String} price  메뉴 가격.
	* @apiSuccess {String} commentCount  메뉴 리뷰 갯수.
	* @apiSuccess {String} isWish  회원 찜 여부 [ 0(찜 안함) | 1(찜 함) ].
	* @apiSuccess {String} likeCount  좋아요 갯수 [ 찜 갯수 + 공유 갯수 + 조회 수 ].
	* @apiSuccess {String} menuImageUrl  메뉴 이미지 URL.
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* [{
	*	"menuId":"1",
	*	"menuName":"싱싱커플",
	*	"menuSummary":"노량진 수산시장의 분위기를 느끼고 싶은 커플들이여 오라! ",
	*	"description":"자연산(自然産) : 양식한 것이 아니라 자연에서 저절로 생산되는 것 그렇습니다. 자연에서 태어나고 자란것만 선별하여 미식가인 회친님들을 위해 정성껏 만들었습니다. 대광어와 도미 그리고 자연산 제철생선을 담아낸 "미친 자연산" 미친물고기는 회친님들의 입맛을 만족시키기 위해 항상 연구하고 노력하겠습니다. ※ 자연산 특성상 경매사정에 따라 판매가 제한될 수 있는 품목임을 알려드립니다. 서비스 - 초밥 인당 2pcs, 약빤 매운탕, 해산물 or 연어 or 전복(인당 1개) *약빤 매운탕 : 제철생선을 과감하게 투척, 국물 맛을 풍성하게 변신시킨 미친물고기만의 매운탕 미친물고기와 함께라면 다양한 회를 매일 함께 할 수 있어요.​ ​ ​ ​구성 : 대 광어 or 도미 + 자연산 생선 ​2인 기준 가격 : 80,000원 / 1인 추가시 30,000원",
	*	"price":"25000",
	*	"commentCount":"0",
	*	"isWish":"0",
	*	"likeCount":"125",
	*	"menuImageUrl":"http://52.68.20.211/images/0000000001.jpg"
	* }]
	*
	*/



/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										선호회 리스트											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/menu/prefer 선호회 리스트
	* @apiName Get Prefer List
	* @apiGroup Menu
	*
	*
	* @apiSuccess {String} [Access-Token]  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} preferId  선호회 ID [ Unique ].
	* @apiSuccess {String} preferType  선호회 타입 [ white | red | season | wild | seafood | crab ].
	* @apiSuccess {String} preferName  선호회 이름.
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* [{
	*	"preferId":"1",
	*	"preferType":"white",
	*	"preferName":"흰살생선"
	* }]
	*
	*/
	
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										오늘의 Pick 리스트										   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/menu/pick/:currentPage 오늘의 Pick 리스트
	* @apiName Get Today Pick List
	* @apiGroup Menu
	*
	* @apiParam {String} currentPage 현재 페이지 [ Default : 1 ]
	*
	* @apiSuccess {String} [Access-Token]  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} menuId  메뉴 ID [ Unique ].
	* @apiSuccess {String} menuName  메뉴 이름.
	* @apiSuccess {String} menuSummary  메뉴 요약 안내.
	* @apiSuccess {String} description  메뉴 상세 설명.
	* @apiSuccess {String} price  메뉴 기본 가격 [ 기본 2인분, (기본가격 + 인분당 가격 - 할인가격) / 할인률/100 으로 계산해야함 ].
	* @apiSuccess {String} commentCount  메뉴 리뷰 갯수.
	* @apiSuccess {String} isWish  회원 찜 여부 [ 0(찜 안함) | 1(찜 함) ].	
	* @apiSuccess {String} likeCount  좋아요 갯수 [ 찜 갯수 + 공유 갯수 + 조회 수 ].	
	* @apiSuccess {String} menuImageUrl  메뉴 이미지 URL.
	* @apiSuccess {String} sale  메뉴 할인률 [ 소숫점 ].
	* @apiSuccess {String} salePrice  메뉴 할인가격.
	* @apiSuccess {String} isNew  신규 메뉴 여부 [ 0(아님) | 1(신규) ].
	* @apiSuccess {String} isBonus  덤증정 여부 [ 0(아님) | 1(증정) ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* [{
	*	"menuId":"1",
	*	"menuName":"싱싱커플",
	*	"menuSummary":"회에 통달한 회신들에게 바치는 자연산 물고기",
	*	"description":"자연산(自然産) : 양식한 것이 아니라 자연에서 저절로 생산되는 것 그렇습니다. 자연에서 태어나고 자란것만 선별하여 미식가인 회친님들을 위해 정성껏 만들었습니다. 대광어와 도미 그리고 자연산 제철생선을 담아낸 "미친 자연산" 미친물고기는 회친님들의 입맛을 만족시키기 위해 항상 연구하고 노력하겠습니다. ※ 자연산 특성상 경매사정에 따라 판매가 제한될 수 있는 품목임을 알려드립니다. 서비스 - 초밥 인당 2pcs, 약빤 매운탕, 해산물 or 연어 or 전복(인당 1개) *약빤 매운탕 : 제철생선을 과감하게 투척, 국물 맛을 풍성하게 변신시킨 미친물고기만의 매운탕 미친물고기와 함께라면 다양한 회를 매일 함께 할 수 있어요.​ ​ ​ ​구성 : 대 광어 or 도미 + 자연산 생선 ​2인 기준 가격 : 80,000원 / 1인 추가시 30,000원",
	*	"price":"80000",
	*	"commentCount":"0",
	*	"isWish":"0",	
	*	"likeCount":"125",
	*	"menuImageUrl":"http://52.68.20.211/images/0000000007.jpg",
	*	"sale":"0.1",
	*	"salePrice":"1000",
	*	"isNew":"1",
	*	"isBonus":"1"
	* }]
	*
	*/	
	
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										맞춤 메뉴 검색											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/menu/search 맞춤 메뉴 검색
	* @apiName Get Search Menu
	* @apiGroup Menu
	*
	* @apiParam {String} currentPage 현재 페이지 [ Default : 1 ]
	* @apiParam {String} person 참석인원 [ 적은 인원 기준 ].
	* @apiParam {String} price 가격대 [ 많은 금액 기준 ].
	* @apiParam {String} [prefer] 선호하는 회 [ white(흰살),red(붉은살),season(제철),wild(자연산) ].
	*
	* @apiSuccess {String} [Access-Token]  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} menuId  메뉴 ID [ Unique ].
	* @apiSuccess {String} menuSummary  메뉴 요약 안내.
	* @apiSuccess {String} menuName  메뉴 이름.	
	* @apiSuccess {String} description  메뉴 상세 설명.
	* @apiSuccess {String} price  메뉴 기본 가격 [ 기본 2인분, (기본가격 + 인분당 가격 - 할인가격) / 할인률/100 으로 계산해야함 ].
	* @apiSuccess {String} menu.menuType 메뉴 Type [ menu | pick | topping | delivery ].
	* @apiSuccess {String} totalStar  별점 [ 올림 ].
	* @apiSuccess {String} totalStarCount  별점을 매긴 회원 수.
	* @apiSuccess {String} isWish  회원 찜 여부 [ 0(찜 안함) | 1(찜 함) ].		
	* @apiSuccess {String} likeCount  좋아요 갯수 [ 찜 갯수 + 공유 갯수 + 조회 수 ].	
	* @apiSuccess {String} menuImageUrl  메뉴 이미지 URL.
	* @apiSuccess {String} sale  메뉴 할인률 [ 소숫점 ].
	* @apiSuccess {String} salePrice  메뉴 할인가격.
	* @apiSuccess {String} isNew  신규 메뉴 여부 [ 0(아님) | 1(신규) ].
	* @apiSuccess {String} isBonus  덤증정 여부 [ 0(아님) | 1(증정) ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* [{
	*	"menuId":"1",
	*	"menuSummary":"노량진 수산시장의 분위기를 느끼고 싶은 커플들이여 오라! ",
	*	"menuName":"싱싱커플",
	*	"description":"영화 → 식사 → 카페 다음에 만나도 영화 → 식사 → 카페만 반복하는 커플들이여. 주목하세요! 새로운 데이트 코스를 정해드립니다. ​ ​바로 노량진 수산시장입니다. :) ​ ​ 노량진 수산시장의 분위기도 느끼며, 생선 구경도 하면서 색다른 데이트를 해보세요. ​ 그리고 금강산도 식후경! ​ ​사람들이 회 하면 떠올리는 2가지 대표 회. ​ 부드러운 식감의 연어와 탱글탱글한 식감의 광어로 구성된 미친물고기의 커플메뉴 "싱싱커플"도 그냥 지나칠 수 없죠? 똑똑하고 합리적인 커플들을 위해 가격도 합리적으로 책정했습니다. 맛있게 드시라고 4pcs 초밥구성은 덤! ​​ 미친물고기와 함께라면 다양한 회를 매일 함께 할 수 있어요. ​구성 : 광어 + 연어 + 초밥4pcs ​ 2명 기준 가격 : 25,000원",
	*	"price":"25000",
	*	"menuType":"menu",
	*	"menuImageUrl":"http://52.68.20.211/images/0000000001.jpg",
	*	"totalStar":"10",
	*	"totalStarCount":"1",
	*	"likeCount":"37",
	*	"isWish":"0",
	*	"sale":0,
	*	"salePrice":0,
	*	"isNew":0,
	*	"isBonus":0
	* }]
	*
	*/
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										 메뉴 상세 화면											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/menu/detail/:menuId 메뉴 상세
	* @apiName Get Menu Detail
	* @apiGroup Menu
	*
	* @apiParam {String} menuId 메뉴 ID [ Unique ]
	*
	* @apiSuccess {String} [Access-Token]  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} menuName  메뉴 이름.
	* @apiSuccess {String} menu.menuType 메뉴 Type [ menu | pick | topping | delivery ].
	* @apiSuccess {String} menuSummary  메뉴 요약 안내.
	* @apiSuccess {String} basePrice  기본 가격 [ 기본 2인 기준 ].
	* @apiSuccess {String} priceType  가격 타입 [ one(1인추가) | two(2인추가) ].
	* @apiSuccess {String} pricePerPerson  추가 1인 가격 [ 2인일 수 있음 ].
	* @apiSuccess {String} commentCount  리뷰 갯수.
	* @apiSuccess {String} link  리뷰 갯수.
	* @apiSuccess {String} totalStar  별점 [ 올림 ].
	* @apiSuccess {String} isWish  회원 찜 여부 [ 0(찜 안함) | 1(찜 함) ].		
	* @apiSuccess {String} likeCount  좋아요 갯수 [ 찜 갯수 + 공유 갯수 + 조회 수 ].	
	* @apiSuccess {String} description  메뉴 상세 설명.	
	* @apiSuccess {String} sale  메뉴 할인률 [ 소숫점 ].
	* @apiSuccess {String} salePrice  메뉴 할인가격.
	* @apiSuccess {String} isNew  신규 메뉴 여부 [ 0(아님) | 1(신규) ].
	* @apiSuccess {String} isBonus  덤증정 여부 [ 0(아님) | 1(증정) ].
	* @apiSuccess {String} buyPoint  menuType이 토핑일 경우 살 수 있는 포인트.
	* @apiSuccess {String} deliveryFee  배달 요금표 URL
	* @apiSuccess {Object[String]} menuImageUrl  메뉴 이미지 URL.
	* @apiSuccess {Object[Json]} menuService  서비스 메뉴 목록.
	* @apiSuccess {String} menuService.name  서비스 메뉴 이름.
	* @apiSuccess {String} menuService.isRequired  필수 서비스 여부 [ 0(선택 서비스) | 1(필수 서비스) ].
	* @apiSuccess {Object[Json]} menuComponent  서비스 구성 목록.
	* @apiSuccess {String} menuComponent.name  서비스 구성 이름.
	* @apiSuccess {String} menuComponent.imageUrl  서비스 구성 Image URL.
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	*	"menuName":"싱싱커플",
	*	"menuType":"pick",
	*	"menuSummary":"회에 통달한 회신들에게 바치는 자연산 물고기",
	*	"basePrice":"25000",
	*	"priceType":"one",
	*	"pricePerPerson":"10000",
	*	"commentCount":"11",
	*	"link":"http://..",
	*	"totalStar":"87",
	*	"totalStarCount":"10",
	*	"isWish":"0",		
	*	"likeCount":"124",
	*	"description":"​구성 : 광어 + 연어 + 초밥4pcs 2명 기준 가격 : 25,000원",
	*	"sale":0,
	*	"salePrice":0,
	*	"isNew":0,
	*	"isBonus":0,
	*	"buyPoint":0,
	*	"deliveryFee":"http://pay.crazyfish.co.kr/assets/mobile/images/quick_money.png",
	*	"menuImageUrl":["http://..","http://..","http://.."],
	*	"menuService":[{
	*		"name":"초밥 4ps",
	*		"isRequired":"1"
	*	}],
	*	"menuComponent":[{
	*		"name":"광어",
	*		"imageUrl":"http://.."
	*	}]
	* }
	*
	*/
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										 메뉴 리뷰 목록											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/menu/comment 메뉴 리뷰 목록
	* @apiName Get Menu Comment
	* @apiGroup Menu
	*
	* @apiParam {String} currentPage 현재 페이지 [ Default : 1 ]
	* @apiParam {String} menuId 메뉴 ID [ Unique ]
	*
	* @apiSuccess {String} [Access-Token]  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} isOrder  해당 메뉴 주문 여부 [ 1(주문했음) | 0(주문안했음) ].
	* @apiSuccess {Object[]} comments  리뷰 목록.
	* @apiSuccess {String} comments.commentId  리뷰 ID [ Unique ].
	* @apiSuccess {String} comments.phone  리뷰 작성 회원 전화번호 [ Format : 뒤 4자리 (12**) ].
	* @apiSuccess {String} comments.star  별점.
	* @apiSuccess {String} comments.comment  리뷰.
	* @apiSuccess {String} comments.sorting  리뷰 정렬 [ 0(사용자 리뷰) | 1(관리자 리뷰) ].
	* @apiSuccess {String} comments.status  리뷰 상태 [ normal | delete ].
	* @apiSuccess {String} comments.isMine  내가 쓴 리뷰인지 여부 [ 0(내가 쓰지않은 리뷰) | 1(내가 쓴 리뷰) ].
	* @apiSuccess {String} comments.registDate  리뷰 등록 날짜.
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	*	"isOrder":"1",
	*	"comments":[{
	*		"xid":"5",
	*		"commentId":"1",
	*		"phone":"10**",
	*		"star":"10",
	*		"comment":"haha",
	*		"sorting":"0",
	*		"status":"normal",
	*		"registDate":"2015-05-28 10:50:11",
	*		"isMine":"1"
	*	}]
	* }
	*
	*/					
	
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										 메뉴 공유하기											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /api/menu/share 메뉴 공유하기
	* @apiName Set Menu Share
	* @apiGroup Menu
	*
	* @apiParam {String} menuId 메뉴 ID [ Unique ]
	* @apiParam {String} orderId 주문 ID [ Unique ]
	*
	* @apiSuccess {String} [Access-Token]  인증 필요시 사용되는 세션 토큰 [ Header ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	* }
	*
	*/						
	
	
/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//											 평가하기											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /api/menu/comment 평가하기
	* @apiName Set Comment
	* @apiGroup Menu
	*
	* @apiParam {String} [commentId] 리뷰 ID [ Unique, 수정 시 입력 ].
	* @apiParam {String} menuId 메뉴 ID [ Unique ].
	* @apiParam {String} star 별점 [ 10점 만점 ].
	* @apiParam {String} comment 리뷰.
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	* }
	*
	*/		