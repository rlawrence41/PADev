
#	PA_USE_T.SQL -- Creates a database table.
#			This procedure was automatically generated by Ron Lawrence on 
#			01/15/2017 using MAKE_DBF.


	drop table pa_user;
	create table pa_user ( 
		id			integer(10) primary key auto_increment,
		userId     	varchar(10), 
		userCode    varchar(3), 
		password    varchar(20), 
		ownerCntct  integer(10), 
		dspInvStat  boolean, 
		shipImmed  	boolean, 
		recvImmed  	boolean, 
		autoBatch  	boolean, 
		forecast    boolean, 
		confirm     boolean, 
		traceDate  	boolean, 
		damagedisc  decimal(6, 2), 
		minAdjust  	decimal(10, 2), 
		adj1Dflt   	varchar(12), 
		adj2Dflt   	varchar(12), 
		fsLength   	integer(10), 
		freeformad  boolean, 
		resrvcount  integer(10), 
		financechk  boolean, 
		reuserecs   boolean, 
		packprompt  boolean, 
		lastV1inv  	integer(10), 
		letterhead  text, 
		suprLtrhd  	boolean, 
		refrshkeys  boolean, 
		taxWho     	integer(1), 
		boComment  	boolean, 
		sameDateIt  boolean, 
		swoItmDisc  boolean, 
		swoAutoSpl  boolean, 
		swoNetComm  boolean, 
		swoQbRcptC  varchar(30), 
		swoQbRcptD  varchar(30), 
		swoFullCcn  boolean, 
		lDefaultC   boolean, 
		lDefaultR   boolean, 
		lDefaulTp   boolean, 
		lChkBal90   boolean, 
		lBO90Hold   boolean, 
		lSameItem   boolean, 
		lProfitLos  boolean, 
		lBestSales  boolean, 
		lBalanceSh  boolean, 
		lEncrypt    boolean, 
		lChkUpdate  boolean, 
		cno         varchar(5), 
		rcd         varchar(5), 
		uct         varchar(1))