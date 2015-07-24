/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										Access-Token 체크										   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/member/token_check/ Access-Token 체크
	* @apiName Check Signature
	* @apiGroup Member
	*
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
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
//										인증 코드 요청											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /api/cert/code/ 인증 코드 요청
	* @apiName Request Certification Code
	* @apiGroup Certification
	*
	* @apiParam {String} phone 회원 전화번호 [ Format : 01011112222 ]
	* @apiParam {String} part 인증 타입 [ join(가입) | find(찾기) ]
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
//										인증 코드 확인											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/cert/code_validation/ 인증 코드 확인
	* @apiName Confirm Certification Code
	* @apiGroup Certification
	*
	* @apiParam {String} phone 회원 전화번호 [ Format : 01011112222 ]
	* @apiParam {String} certCode 인증 코드 [ 6자리 ]
	* @apiParam {String} part 인증 타입 [ join(가입) | find(찾기) ]
	*
	*
	* @apiSuccess {String} [email]  회원 이메일 [ part의 값이 find일 경우에만 존재 ]
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
		"email":"rinno83@naver.com"
	* }
	*
	*/


/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										로그인												   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /api/member/login 로그인
	* @apiName Login
	* @apiGroup Member
	*
	* @apiParam {String} email 회원 이메일 계정
	* @apiParam {String} password 회원 비밀번호
	*
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	* }
	*
	*/


/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										회원가입												   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /api/member/account 회원가입
	* @apiName Set Member Account
	* @apiGroup Member
	*
	* @apiParam {String} email 회원 이메일 계정
	* @apiParam {String} password 회원 비밀번호
	* @apiParam {String} name 회원 이름
	* @apiParam {String} phone 회원 전화번호
	* @apiParam {String} gender 회원 성별 [ male | female ]
	* @apiParam {String} birthday 회원 생년월일 [ yyyymmdd ]
	*
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	* }
	*
	*/
	

/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										비밀번호 재설정											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /api/member/password 비밀번호 재설정
	* @apiName Set Member Password
	* @apiGroup Member
	*
	* @apiParam {String} [email] 회원 이메일 [ accessToken이 없는 경우에는 필수값 ]
	* @apiParam {String} newPassword 새로운 비밀번호
	* @apiParam {String} newPasswordConfirm 새로운 비밀번호 확인
	*
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
//										회원 디바이스 설정										   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /api/member/device 회원 디바이스 설정
	* @apiName Set Member Device
	* @apiGroup Member
	*
	* @apiParam {String} uuid 회원 UUID
	* @apiParam {String} pushToken 회원 Push Token
	*
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
//									사용자 정보 가져오기											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/member/info 사용자 정보 가져오기
	* @apiName Get Member Info
	* @apiGroup Member
	*
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} email  회원  Email [ Unique ].
	* @apiSuccess {String} name  회원 이름 혹은 닉네임.
	* @apiSuccess {String} phone  회원 전화번호.
	* @apiSuccess {String} point  회원 포인트.
	* @apiSuccess {String} gender  회원 성별 [ male(남), female(여) ].
	* @apiSuccess {String} birthday  회원 생년월일 [ Format : yyyymmdd ].
	* @apiSuccess {String} status  회원 상태 [ normal(정상) | block(블럭) | quit(탈퇴) ].	
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	*	"email":"rinno83@naver.com",
	*	"phone":"01027521038",
	*	"point":"110",
	*	"gender":"male",
	*	"birthday":"19830615",
	*	"status":"normal"
	* }
	*
	*/		



/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										찜 목록												   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/member/wish/:currentPage 찜 목록
	* @apiName Get Member Wish
	* @apiGroup Member
	*
	* @apiParam {String} currentPage 현재 페이지 [ Default : 1 ]
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} menuId  메뉴 ID [ Unique ].
	* @apiSuccess {String} menu.menuType 메뉴 Type [ menu | pick | topping | delivery ].
	* @apiSuccess {String} menuName  메뉴 이름.
	* @apiSuccess {String} totalStar  별점 [ 올림 ].
	* @apiSuccess {String} totalStarCount  별점을 매긴 회원 수.
	* @apiSuccess {String} isWish  회원 찜 여부 [ 0(찜 안함) | 1(찜 함) ].	
	* @apiSuccess {String} likeCount  좋아요 갯수 [ 찜 갯수 + 공유 갯수 + 조회 수 ].
	* @apiSuccess {String} isExpired  판매 종료 여부 [ 0(판매중) | 1(판매 완료), 메뉴 Type이 pick인 메뉴들 중 만료일 체크 ].
	* @apiSuccess {String} menuImageUrl  메뉴 이미지 URL.
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* [{
	*	"menuId":"1",
	*	"menuType":"pick",
	*	"menuName":"싱싱커플",
	*	"totalStar":"85",
	*	"totalStarCount":"10",	
	*	"isWish":"0",	
	*	"likeCount":"125",
	*	"isExpired":"0",
	*	"menuImageUrl":"http://.."
	* }]
	*
	*/		



/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										찜 하기												   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /api/member/wish/:menuId 찜 하기
	* @apiName Set Member Wish
	* @apiGroup Member
	*
	* @apiParam {String} menuId Menu ID [ Unique ]
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} status  찜하기 상태 [ insert | delete ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	* 	"status":"insert"
	* }
	*
	*/		



/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										내 알림 가져오기										   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/member/alarm 내 알림 가져오기
	* @apiName Get Member Alarm
	* @apiGroup Member
	*
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} alarmId  알림 ID [ Unique ].
	* @apiSuccess {String} xid  회원 ID.
	* @apiSuccess {String} type  알림 타입 [ html | url ].
	* @apiSuccess {String} title  알림 제목.
	* @apiSuccess {String} content  알림 내용 [ 타입에 따라 html(웹뷰), url(링크 오픈) ].
	* @apiSuccess {String} registDate  알림 시간 [ Format : Timestamp(초) ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* [{
	*	"alarmId":"1",
	*	"xid":"5",
	*	"type":"html",
	*	"title":"\ud14c\uc2a4\ud2b8\uc785\ub2c8\ub2e4.",
	*	"content":"",
	*	"registDate":"1435733893"
	* }]
	*
	*/		


/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										내 리뷰 가져오기										   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/member/comment 내 리뷰 가져오기
	* @apiName Get Member Comment
	* @apiGroup Member
	*
	* @apiParam {String} menuId 메뉴 ID [ Unique ]
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} commentId  리뷰 ID [ Unique ].
	* @apiSuccess {String} star  별점 [ 10점 만점 ].
	* @apiSuccess {String} comment  리뷰 내용.
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	*	"commentId":"3",
	*	"star":"10",
	*	"comment":"완전 강추~"
	* }
	*
	*/		



/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										내 리뷰 삭제											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {delete} /api/member/comment 내 리뷰 삭제
	* @apiName Delete Member Comment
	* @apiGroup Member
	*
	* @apiParam {String} commentId 리뷰 ID [ Unique ].
	* @apiParam {String} menuId 메뉴 ID [ Unique ]
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	* }
	*
	*/		





/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										약관 가져오기											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/service/terms 약관 가져오기
	* @apiName Get Service Terms
	* @apiGroup Service
	*
	* @apiParam {String} [lang] 언어 코드 [ Default : ko ]
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} type  약관 타입 [ service | private ].
	* @apiSuccess {String} content  약관 내용 [ Format : Plain Text ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* [{
	*	"type":"service",
	*	"content":"테스트 입니다."
	* }]
	*
	*/		



/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										버전 가져오기											   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/service/version 버전 가져오기
	* @apiName Get Service Version
	* @apiGroup Service
	*
	*
	* @apiSuccess {String} versionName  버전 이름 [ major.minor.fix(minor 이상부터 강제 업데이트) ].
	* @apiSuccess {String} versionCode  버전 코드.
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	*	"versionName":"0.9.13",
	*	"versionCode":"1"
	* }
	*
	*/		



/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										회사 정보 가져오기										   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /api/service/corp_info 회사 정보 가져오기
	* @apiName Get Service Corp
	* @apiGroup Service
	*
	*
	* @apiSuccess {String} name  회사 이름
	* @apiSuccess {String} ceo  회사 대표 이름.
	* @apiSuccess {String} registNumber  사업자 등록 번호
	* @apiSuccess {String} communicationBusinessReport  통신판매업신고.
	* @apiSuccess {String} address  회사 주소
	* @apiSuccess {String} phone  회사 대표 번호.
	* @apiSuccess {String} mobilePhone1  회사 핸드폰 번호 1.
	* @apiSuccess {String} mobilePhone2  회사 핸드폰 번호 2.
	* @apiSuccess {String} mobilePhone3  회사 핸드폰 번호 3.
	* @apiSuccess {String} email  회사 대표 이메일
	* @apiSuccess {String} bankAccount  회사 계좌 번호.
	* @apiSuccess {String} bankName  회사 계좌 은행명.
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	*	"name":"(주)미디어유코프",
	*	"ceo":"이지선",
	*	"registNumber":"107-86-88505",
	*	"communicationBusinessReport":"제 2015 - 서울영등포 - 0693 호",
	*	"address":"서울시 영등포구 63로 32 1024호 (여의도동, 라이프콤비빌딩)",
	*	"phone":"1644-2016",
	*	"mobilePhone1":"01084702016",
	*	"mobilePhone2":"",
	*	"mobilePhone3":"",
	*	"email":"crazyfish@mediau.net",
	*	"bankAccount":"",
	*	"bankName":""
	* }
	*
	*/		
