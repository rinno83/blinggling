/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										Access-Token 체크										   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {get} /member/token_check Access-Token 체크
	* @apiName Check Access Token
	* @apiGroup Member
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
//										로그인												   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /member/login 로그인
	* @apiName Login
	* @apiGroup Member
	*
	* @apiParam {String} memberKey 회원 Key [ Email || Social ID ]
	* @apiParam {String} [password] 회원 비밀번호
	*
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	* @apiSuccess {String} name  회원 이름.
	* @apiSuccess {String} birthday  회원 생년월일 [ yyyymmdd ].
	* @apiSuccess {String} gender  회원 성별 [ male || female ].
	* @apiSuccess {String} profileImageUrl  회원 프로필 사진 URL [ URL || "" ].
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	*	"name":"이두화",
	*	"birthday":"19830615",
	*	"gender":"male",
	*	"profileImageUrl":""
	* }
	*
	*/


/////////////////////////////////////////////////////////////////////////////////////////////////
//																							   //
//										회원가입												   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /member/account 회원가입
	* @apiName Set Member Account
	* @apiGroup Member
	*
	* @apiParam {String} memberKey 회원 Key [ Email || Social ID ]
	* @apiParam {String} [password] 회원 비밀번호 [ memberKey가 Email인 경우는 필수, Social ID인 경우는 옵션(Social ID가 비밀번호로 설정) ]
	* @apiParam {String} [name] 회원 이름
	* @apiParam {String} [gender] 회원 성별 [ male | female ]
	* @apiParam {String} [birthday] 회원 생년월일 [ yyyymmdd ]
	* @apiParam {String} [profileImageUrl] 회원 프로필 사진 URL [ URL ].
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
//										회원 디바이스 설정										   //
//																							   //
/////////////////////////////////////////////////////////////////////////////////////////////////	

	/**
	* @api {post} /member/device 회원 디바이스 설정
	* @apiName Set Member Device
	* @apiGroup Member
	* @apiDescription Access-Token 발급 후 호출, xid 있을 때 UPDATE, 없을 때 INSERT
	*
	* @apiParam {String} uuid 회원 UUID
	* @apiParam {String} pushToken 회원 Push Token
	*
	*
	* @apiSuccess {String} Access-Token  인증 필요시 사용되는 세션 토큰 [ Header ].
	*
	*
	* @apiSuccessExample Success-Response:
	* HTTP/1.1 200 OK
	* {
	* }
	*
	*/		

